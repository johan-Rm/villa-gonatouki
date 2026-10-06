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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class GenerateJsonApiCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:generate-json-api';

    private $container;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getEntityManager();

        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Generate Json Api.')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to generate json api...')
        ->addOption('full', null, InputOption::VALUE_OPTIONAL, ' ?', false)
        ->addOption('list', null, InputOption::VALUE_OPTIONAL, ' ?', false)
        ->addArgument('entity', InputArgument::OPTIONAL, ' ?')
        ->addArgument('slug', InputArgument::OPTIONAL, ' ?')
        // ->addOption('write-only', null, InputOption::VALUE_OPTIONAL, 'Write only ?', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->lock()) {
            $start = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => '.$start);

            $exportApiToJson = $this->container->get('app.export.api_to_json');

            $option = $input->getOption('full');

            if (false !== $option) {
                // $exportApiToJson->generate('full');
                $exportApiToJson->generateSerializable('full');
            } else {
                exit('nooo full');
                $entity = $input->getArgument('entity');
                $slug = $input->getArgument('slug');
                $options = [
                    'query' => [
                        'slug' => $slug,
                        'pagination' => false,
                    ],
                ];

                $option = $input->getOption('list');
                if (false !== $option) {
                    $exportApiToJson->generateList($entity, $options);
                } else {
                    $exportApiToJson->generateOne($entity, $options);
                }
            }

            $end = date('Y-m-d\ H:i:s.u');
            $output->writeln('end to => '.$end);
        }
    }
}
