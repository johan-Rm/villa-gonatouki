<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class RegenerateMediaFiltersCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:regenerate-media-filters';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Service\Media
     */
    private $mediaService;

    private $contextParameters;

    public function __construct(
        ContainerInterface $container, EntityManagerInterface $em, Filesystem $filesystem
    ) {
        $this->container = $container;
        $this->em = $em;
        $this->filesystem = $filesystem;

        $this->mediaService = $this->container->get('app.tools.media');
        $this->contextParameters = [
            'mimeTypes' => $this->mediaService->getMimetypesRules(),
        ];

        parent::__construct();
    }

    /**
     *    #command regénération des images.
     *
     *    - générent uniquement les formats (cache/)
     *    ./bin/console app:regenerate-media-filters
     *
     *    - compressent les originaux et génerent les formats (cache/)
     *    ./bin/console app:regenerate-media-filters --compress_original
     *
     *    - récupérent les images déja tamponnées et ne fait pas de compression de l'original pour ne pas avoir un double tampon
     *    ./bin/console app:regenerate-media-filters --original_stamped_image
     *
     *    - avec un media id comme argument pour compresse une seule image
     *    ./bin/console app:regenerate-media-filters 8919 --compress_original
     **/
    protected function configure()
    {
        $this
            ->setDescription('Generate all image formats.')
            ->setHelp('This command allows you to generate all image formats...')
            // ->addOption('stamp', null, InputOption::VALUE_OPTIONAL, 'stamp ?', false)
            ->addArgument('media_id', InputArgument::OPTIONAL, ' ?')
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'filename?', null)
            ->addOption('show_context_parameters_only', null, InputOption::VALUE_OPTIONAL, 'show_context_parameters_only ?', false)
            ->addOption('compress_original', null, InputOption::VALUE_OPTIONAL, 'compressOriginal ?', false)
            // ->addOption('compress_original_webp', null, InputOption::VALUE_OPTIONAL, 'compressOriginalWebp ?', false)
            ->addOption('original_stamped_image', null, InputOption::VALUE_OPTIONAL, 'originalStampedImage ?', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->lock()) {
            $this->regenerateAll($input, $output);
        }

        return 0; //Command::SUCCESS;
    }

    protected function regenerateAll(InputInterface $input, OutputInterface $output)
    {
        $start = date('Y-m-d\ H:i:s.u');

        /************************************************************
        * Define output style
        *************************************************************/
        $outputStyle = new OutputFormatterStyle('red', 'yellow', ['bold', 'blink']);
        $output->getFormatter()->setStyle('fire', $outputStyle);
        $output->writeln([
            sprintf('<info>Execute => %s</>', self::$defaultName),
            '<info>==========================</>',
            '',
        ]);
        $output->writeln('<comment>start to => '.$start.'</comment>');

        /************************************************************
        * Call service
        *************************************************************/
        $consoleService = $this->container->get('app.tools.console');

        /************************************************************
        * Get input option
        *************************************************************/
        // $stamp = (false !== $input->getOption('stamp'))? true: false;
        $showContextParametersOnly = (false !== $input->getOption('show_context_parameters_only')) ? true : false;
        $compressOriginal = (false !== $input->getOption('compress_original')) ? true : false;
        // $compressOriginalWebp = (false !== $input->getOption('compress_original_webp'))? true: false;
        // si true on récuere en base de données les images deja tamponnées à l'original
        // si false on recupere les autres
        $originalStampedImage = (false !== $input->getOption('original_stamped_image')) ? true : false;

        // if($compressOriginal && $originalStampedImage) {

        // throw new \Exception('ATTENTION PAS POSSIBLE DE COMPRESSER DES IMAGES DÉJA TAMPONNÉES!!');
        // }

        /************************************************************
        * Display context parameters
        *************************************************************/
        $output->writeln('<info>Context parameters</info>');
        $rows = [];
        // dump($this->contextParameters); die;
        $rows['mimeTypes'] = implode(',', $this->contextParameters['mimeTypes']);
        // $rows['stamp'] = $stamp ? 'true' : 'false';
        $rows['compressOriginal'] = $compressOriginal ? 'true' : 'false';
        // $rows['compressOriginalWebp'] = $compressOriginalWebp ? 'true' : 'false';
        $rows['originalStampedImage'] = $originalStampedImage ? 'true' : 'false';
        $rows = array_merge($this->contextParameters, $rows);
        $rows = $consoleService->transposeKeyValue($rows);
        $table = new Table($output);
        $table
            ->setHeaders(['Label', 'Value'])
            ->setRows($rows)
        ;
        $table->render();

        if ($showContextParametersOnly) {
            return 0; //Command::SUCCESS;
        }

        /************************************************************
        * STOP PROCESS IF NOT FOUND MIME TYPES
        *************************************************************/
        if (empty($this->contextParameters['mimeTypes'])) {
            $output->writeln('<fire>Missing mimeTypes values</>');
            exit;
        }

        /************************************************************
        * Get all medias
        *************************************************************/
        $params = ['originalStampedImage' => $originalStampedImage];
        $mediaId = $input->getArgument('media_id');
        if (null !== $mediaId) {
            $params['id'] = $mediaId;
        }

        $output->writeln('<info>Get all medias</info>');
        $mediaObjectRepository = $this->em->getRepository(MediaObject::class);
        // force!!
        // $rows['originalStampedImage'] = 1;

        $results = $mediaObjectRepository
        // ->findAll();
        ->findBy($params);

        // dump(count($results));die;
        $totalCount = count($results);
        $output->writeln(sprintf('<comment>total medias : %d</comment>', $totalCount));
        $output->writeln('');
        /**
         * Each all medias.
         **/
        // shuffle($results);
        // foreach (array_slice($results, 0, 3) as $key => $media) {
        $i = 0;
        foreach ($results as $key => $media) {
            $filenameOption = $input->getOption('filename');
            // dump($filenameOption);
            // dump($media->getFilename());
            if (null !== $filenameOption && $filenameOption != $media->getFilename()) {
                continue;
            }


                /************************************************************
                * Traitment if mimeType accepted
                *************************************************************/
                if (in_array($media->getEncodingFormat(), $this->mediaService->getMimetypesRules())) {
                  ++$i;
                    /************************************************************
                    * Generate multi format
                    *************************************************************/
                    // $this->consoleOutput->writeln('<info>Generate multi format</info>');
                    $this->mediaService->generatesMultipleFormats($media);
                }

                $output->writeln(sprintf('<info>%d / %d</>', $i, $totalCount));
                $output->writeln('<info>==========================</>');

                $batchSize = 300;
                if (($i % $batchSize) === 0) {
                    // $this->em->flush();
                    // $this->em->clear(); // Detaches all objects from Doctrine!
                }

        }
        $end = date('Y-m-d\ H:i:s.u');
        $output->writeln('start to => '.$start);
        $output->writeln('<comment>end to => '.$end.'</comment>');
    }
}
