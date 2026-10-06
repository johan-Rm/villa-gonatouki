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

use App\Entity\Accommodation;
use App\Entity\Article;
use App\Entity\MediaObject;
use App\Entity\WebPage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class GenerateMediaSEOAltBaliseCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:generate-media-seo-alt-balise';

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
        ->setDescription('Generate all media object alt')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to generate all media object alt...')
        // ->addOption('write-only', null, InputOption::VALUE_OPTIONAL, 'Write only ?', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->lock()) {
            $start = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => '.$start);

            // $mediaObjectRepository = $this->em->getRepository(MediaObject::class);
            // $results = $mediaObjectRepository->findAll();

            $mediaService = $this->container->get('app.tools.media');
            $endTitle = ' | L\'Immobilière d\'Essaouira';

            $webPageObjectRepository = $this->em->getRepository(WebPage::class);
            $results = $webPageObjectRepository->findAll();
            /*
            * Each all pages
            **/
            foreach ($results as $key => $page) {
                $primaryImage = $page->getPrimaryImage();
                if (!empty($primaryImage)) {
                    $metaTitle = $page->getMetaTitle().$endTitle;
                    $mediaName = pathinfo($primaryImage->getFilename(), PATHINFO_FILENAME);
                    $metaTitle = $mediaName.' | '.$metaTitle;
                    $primaryImage->setAlt($metaTitle);
                    $this->em->persist($primaryImage);
                    $output->writeln($primaryImage->getAlt());
                }
                $secondaryImage = $page->getSecondaryImage();
                if (!empty($secondaryImage)) {
                    $metaTitle = $page->getMetaTitle().$endTitle;
                    $mediaName = pathinfo($secondaryImage->getFilename(), PATHINFO_FILENAME);
                    $metaTitle = $mediaName.' | '.$metaTitle;
                    $secondaryImage->setAlt($metaTitle);
                    $this->em->persist($secondaryImage);
                    $output->writeln($secondaryImage->getAlt());
                }
                $gallery = $page->getGallery();
                if (!empty($gallery)) {
                    foreach ($gallery->getImageGalleries() as $media) {
                        $image = $media->getImage();
                        $metaTitle = $page->getMetaTitle().$endTitle;
                        $mediaName = pathinfo($image->getFilename(), PATHINFO_FILENAME);
                        $metaTitle = $mediaName.' | '.$metaTitle;
                        $image->setAlt($metaTitle);
                        $this->em->persist($image);
                        $output->writeln($image->getAlt());
                    }
                }
                $this->em->flush();
            }

            $articleObjectRepository = $this->em->getRepository(Article::class);
            $results = $articleObjectRepository->findAll();
            /*
            * Each all articles
            **/
            foreach ($results as $key => $article) {
                $primaryImage = $article->getPrimaryImage();
                if (!empty($primaryImage)) {
                    $metaTitle = $article->getMetaTitle().$endTitle;
                    $mediaName = pathinfo($primaryImage->getFilename(), PATHINFO_FILENAME);
                    $metaTitle = $mediaName.' | '.$metaTitle;
                    $primaryImage->setAlt($metaTitle);
                    $this->em->persist($primaryImage);
                    $output->writeln($primaryImage->getAlt());
                }
                $secondaryImage = $article->getSecondaryImage();
                if (!empty($secondaryImage)) {
                    $metaTitle = $article->getMetaTitle().$endTitle;
                    $mediaName = pathinfo($secondaryImage->getFilename(), PATHINFO_FILENAME);
                    $metaTitle = $mediaName.' | '.$metaTitle;
                    $secondaryImage->setAlt($metaTitle);
                    $this->em->persist($secondaryImage);
                    $output->writeln($secondaryImage->getAlt());
                }
                $gallery = $article->getGallery();
                if (!empty($gallery)) {
                    foreach ($gallery->getImageGalleries() as $media) {
                        $image = $media->getImage();
                        $metaTitle = $article->getMetaTitle().$endTitle;
                        $mediaName = pathinfo($image->getFilename(), PATHINFO_FILENAME);
                        $metaTitle = $mediaName.' | '.$metaTitle;
                        $image->setAlt($metaTitle);
                        $this->em->persist($image);
                        $output->writeln($image->getAlt());
                    }
                }
                $this->em->flush();
            }

            $accommodationsObjectRepository = $this->em->getRepository(Accommodation::class);
            $results = $accommodationsObjectRepository->findAll();
            /*
            * Each all accommodations
            **/
            foreach ($results as $key => $accommodation) {
                $primaryImage = $accommodation->getPrimaryImage();
                if (!empty($primaryImage)) {
                    $metaTitle = $accommodation->getMetaTitle().$endTitle;
                    $mediaName = pathinfo($primaryImage->getFilename(), PATHINFO_FILENAME);
                    $metaTitle = $mediaName.' | '.$metaTitle;
                    $primaryImage->setAlt($metaTitle);
                    $this->em->persist($primaryImage);
                    $output->writeln($primaryImage->getAlt());
                }

                $secondaryImage = $accommodation->getSecondaryImage();
                if (!empty($secondaryImage)) {
                    $metaTitle = $accommodation->getMetaTitle().$endTitle;
                    $mediaName = pathinfo($secondaryImage->getFilename(), PATHINFO_FILENAME);
                    $metaTitle = $mediaName.' | '.$metaTitle;
                    $secondaryImage->setAlt($metaTitle);
                    $this->em->persist($secondaryImage);
                    $output->writeln($secondaryImage->getAlt());
                }
                $gallery = $accommodation->getGallery();
                if (!empty($gallery)) {
                    foreach ($gallery->getImageGalleries() as $media) {
                        $image = $media->getImage();
                        $metaTitle = $accommodation->getMetaTitle().$endTitle;
                        $mediaName = pathinfo($image->getFilename(), PATHINFO_FILENAME);
                        $metaTitle = $mediaName.' | '.$metaTitle;
                        $image->setAlt($metaTitle);
                        $this->em->persist($image);
                        $output->writeln($image->getAlt());
                    }
                }
                $this->em->flush();
            }

            $end = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => '.$start);
            $output->writeln('end to => '.$end);
        }
    }
}
