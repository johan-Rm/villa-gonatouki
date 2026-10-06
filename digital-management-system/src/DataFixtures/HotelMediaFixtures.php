<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Gallery;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class HotelMediaFixtures extends AbstractFixtures implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['HotelFixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $this->initialize($manager);
        $start = date('Y-m-d\ H:i:s.u');
        $this->consoleOutput->writeln(
            sprintf('<fire>Start load HotelMediaFixtures : %s</fire>', $start)
        );

        $this->loadDefault();

        $end = date('Y-m-d\ H:i:s.u');
        $this->consoleOutput->writeln(
            sprintf('<fire>End load MediaFixtures : %s</fire>', $end)
        );
    }

    public function loadDefault()
    {
        $path = $this->container->getParameter('resources_dir').'/images-source/hotel';

        $finder = new Finder();
        $galleries = [];
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
              $absoluteFilePath = $file->getRealPath();
              $currentFolder = basename($file->getPath());
              $this->consoleOutput->writeln(sprintf('<comment>%s</comment>', $absoluteFilePath));

              /************************************************************
              * Create Media Entity
              *************************************************************/
              $this->consoleOutput->writeln('<info>Create Media Entity</info>');

              $media = $this->toolsMediaService->defineEntityMedia($file);
              $this->manager->persist($media);
              $this->manager->flush();

              /************************************************************
              * Add image into array galleries
              *************************************************************/
              $this->consoleOutput->writeln('<info>Add image into array galleries</info>');
              $pattern = '/-vertical/';
              $match_string = $media->getFilename();
              if (!preg_match($pattern, $match_string)) {
                  // if (empty($format)) {
                      $galleries[$currentFolder][] = $media;
                  // }
              }

              /************************************************************
              * Skip processing if not force mode and file already uploaded
              *************************************************************/
              if(!$this->force && $this->toolsMediaService->checkIfFileAlreadyUploaded($media->getFilename())) {
                continue;
              }

              /************************************************************
              * Copy file to the uploads folder
              *************************************************************/
              $this->consoleOutput->writeln('<info>Copy file to the uploads folder</info>');
              $this->toolsMediaService->copyFileToUploadsFolder($media->getFilename(), $file->getRealPath());



              /************************************************************
              * Traitment if mimeType accepted
              *************************************************************/
              if (in_array($media->getEncodingFormat(), $this->toolsMediaService->getMimetypesRules())) {
                  /************************************************************
                  * Generate multi format
                  *************************************************************/
                  $this->consoleOutput->writeln('<info>Generate multi format</info>');
                  $this->toolsMediaService->generatesMultipleFormats($media);
              }
            }

            /************************************************************
            * Create Galleries
            *************************************************************/
            $this->consoleOutput->writeln('<info>Create Galleries</info>');
            
            foreach ($galleries as $key => $values) {
                $gallery = new Gallery();
                $gallery->setName($key);
                foreach ($values as $value) {
                    $gallery->addImage($value);
                }
                $this->manager->persist($gallery);
                $this->manager->flush();
            }
        }

        $this->consoleOutput->writeln('<comment>loadDefault</comment>');
    }
}
