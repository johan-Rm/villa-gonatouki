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

use Faker;
use App\Entity\Event;
use App\Entity\Gallery;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class HotelFixtures extends AbstractFixtures implements DependentFixtureInterface, FixtureGroupInterface
{
    /**
    * @var array
    */
    private $tags = [];

    /**
    * @var array
    */
    private $amenities = [];

    public function getDependencies(): array
    {
        return [
            HotelMediaFixtures::class,
            // HotelWebPageFixtures::class
            WebPageFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['HotelFixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $this->initialize($manager);

        $tags = $manager->getRepository(
            Tag::class
        )->findAll();

        foreach ($tags as $key => $value) {
            $this->tags[$value->getSlug()] = $value;
        }
        // $randomNumber = rand(0, count($this->tags) - 1);
        // $tag = $this->tags[$randomNumber];

        // dump($this->tags[$randomNumber]);
        // dump($randomNumber);
        // die;
        /*
        * Init vars
        **/
        $this->manager = $manager;
        $this->consoleOutput = $this->getConsoleOutput();
        $this->tableOutput = $this->getTableOutput();
        $start = date('Y-m-d\ H:i:s.u');

        $this->consoleOutput->writeln(
            sprintf('<fire>Start load HotelFixtures : %s</fire>', $start)
        );

        $this->loadDefault();
        $this->loadActivities();
        $this->loadAmenities();
        $this->loadServices();
        $this->loadRooms();

        $this->consoleOutput->writeln('');
    }

    private function loadDefault()
    {
        $medias = $this->toolsMediaService->getMediaArray($this->manager);

        // $randomNumber = rand(0, count($this->tags) - 1);
        // $tag = $this->tags[$randomNumber];
        $tag = $this->tags['accueil'];

        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/typical_days.json';
        $typicalDays = file_get_contents($pathJsonFile);
        $typicalDays = json_decode($typicalDays, true);

        foreach ($typicalDays as $value) {
            $hotelTypicalDay = new \App\Entity\HotelTypicalDay();
            $hotelTypicalDay->setName($value['name']);
            $hotelTypicalDay->setCategory($tag);
            $hotelTypicalDay->addTag($tag);
            $this->manager->persist($hotelTypicalDay);
            $this->manager->flush();

            foreach ($value['elements'] as $element) {
                // dump($element);
                // die;
                $filename = $element['primaryImage'];
                if (isset($medias[$filename])) {
                    $slug = $this->slugify->slugify($element['category']);
                    if (!isset($this->tags[$slug])) {
                        $category = new \App\Entity\Tag();
                        $category->setName($element['category']);
                        $this->manager->persist($category);
                        $this->manager->flush();
                        $this->tags[$slug] = $category;
                    } else {
                        $category = $this->tags[$slug];
                    }

                    $media = $medias[$filename];

                    $hotelTypicalDayElement = new \App\Entity\HotelTypicalDayElement();
                    $hotelTypicalDayElement->setHotelTypicalDay($hotelTypicalDay);
                    $hotelTypicalDayElement->setLabel($element['label']);
                    $hotelTypicalDayElement->setValue($element['value']);
                    $hotelTypicalDayElement->setCategory($category);
                    $hotelTypicalDayElement->setDescription($element['description']);
                    $hotelTypicalDayElement->setHours($element['hours']);
                    $hotelTypicalDayElement->setPrimaryImage($media);
                    $hotelTypicalDayElement->setSecondaryImage($media);
                    $hotelTypicalDayElement->setLabelBgTransparent(true);

                    if (isset($element['event'])) {
                        $slug = $this->slugify->slugify($element['event']);
                        $result = $this->manager->getRepository(Event::class)->findOneBy(['slug' => $slug]);
                        $hotelTypicalDayElement->setEvent($result);
                    }

                    $this->manager->persist($hotelTypicalDayElement);
                    $this->manager->flush();
                }
            }
        }

        /*
        * Each Day type
        */

        // $finder = new Finder();
        // $pathFolderImages =  $this->container->getParameter('resources_dir') . '/images-source/hotel/day_type';
        // $finder->files()->in($pathFolderImages);
        // if ($finder->hasResults()) {
        //     foreach ($finder as $file) {
        //         $absoluteFilePath = $file->getRealPath();
        //         $currentFolder = basename($file->getPath());
        //         $relativePathname = $file->getRelativePathname();
        //         $originalFilename = basename($relativePathname);
        //         $ext = pathinfo($relativePathname, PATHINFO_EXTENSION);
        //         $filename = pathinfo($originalFilename, PATHINFO_FILENAME);
        //         $name = $this->slugify->slugify($currentFolder . ' ' . $filename);
        //         $filename = $name . '.' . $ext;

        //         if(isset($medias[$filename])) {
        //             $media = $medias[$filename];

        //             $element = new \App\Entity\HotelTypicalDayElement();
        //             $element->setHotelTypicalDay($hotelTypicalDay);
        //             $element->setLabel($this->faker->sentence(4, true));
        //             $element->setValue($this->faker->sentence(4, true));
        //             $element->setDescription($this->faker->realText);
        //             $element->setHours($this->faker->time('H:i'));
        //             $element->setPrimaryImage($media);
        //             $element->setSecondaryImage($media);
        //             $element->setLabelBgTransparent(true);

        //             $this->manager->persist($element);
        //             $this->manager->flush();
        //         }
        //     }
        // }

        $this->consoleOutput->writeln('<comment>loadDefault</comment>');
    }

    private function loadRooms()
    {
        $faker = Faker\Factory::create('fr_FR');
        $results = $this->manager->getRepository(Gallery::class)->findAll();
        $galleries = [];
        foreach ($results as $key => $result) {
            $galleries[$result->getSlug()] = $result;
        }
        $medias = $this->toolsMediaService->getMediaArray($this->manager);
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/rooms.json';
        $rooms = file_get_contents($pathJsonFile);
        $rooms = json_decode($rooms, true);
        foreach ($rooms as $value) {
            $index = $value['primaryImage'];

            if (isset($medias[$index])) {
                $media = $medias[$index];
                $room = new \App\Entity\Room();

                $category = $this->slugify->slugify($value['category']);
                if (!isset($this->tags[$category])) {
                    $tag = new \App\Entity\Tag();
                    $tag->setName($value['category']);
                    $this->manager->persist($tag);
                    $this->manager->flush();
                    $this->tags[$category] = $tag;
                }

                if (isset($value['offers'])) {
                    foreach ($value['offers'] as $key => $offer) {
                        $aggregateOffer = new \App\Entity\AggregateOffer();
                        $aggregateOffer->setName($offer['name']);
                        $aggregateOffer->setPrice($offer['price']);
                        $addOn = (isset($offer['addOn'])) ? $offer['addOn'] : null;
                        $aggregateOffer->setAddOn($addOn);
                        $start = new \DateTime($offer['start']);
                        $aggregateOffer->setAvailabilityStart($start);
                        $end = new \DateTime($offer['end']);
                        $aggregateOffer->setAvailabilityEnd($end);
                        $room->addOffer($aggregateOffer);
                    }
                }

                $room->setCategory($this->tags[$category]);
                $room->setName($value['name']);
                $room->setDescription($value['description']);
                $room->setDescriptionResume($faker->text);
                $room->setMinimumOccupants($value['minimumOccupants']);
                $room->setMaximumOccupants($value['maximumOccupants']);
                // $room->setPrice($value['price']);
                $room->setNumberOfRooms($value['numberOfRooms']);
                $room->setPrimaryImage($media);
                $room->setLabelBgTransparent(true);
                if (isset($value['amenities'])) {
                    foreach ($value['amenities'] as $key => $amenity) {
                        $slug = $this->slugify->slugify($amenity['name']);
                        if (isset($amenity['category'])) {
                            $slug = $this->slugify->slugify($amenity['name'].' '.$amenity['category']);
                        }
                        $room->addAmenity($this->amenities[$slug]);
                    }
                }

                $slug = $this->slugify->slugify($value['name']);

                if (isset($galleries[$slug])) {
                    $room->setGallery($galleries[$slug]);
                }

                $this->manager->persist($room);
                $this->manager->flush();
            }
        }

        $this->consoleOutput->writeln('<comment>loadRooms</comment>');
    }

    private function loadActivities()
    {
        $medias = $this->toolsMediaService->getMediaArray($this->manager);
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/activities.json';
        $activities = file_get_contents($pathJsonFile);
        $activities = json_decode($activities, true);
        foreach ($activities as $activity) {
            $slug = $this->slugify->slugify($activity['category']);
            if (!isset($this->tags[$slug])) {
                $tag = new \App\Entity\Tag();
                $tag->setName($activity['category']);
                $this->manager->persist($tag);
                $this->manager->flush();
                $this->tags[$slug] = $tag;
            }

            $index = $activity['primaryImage'];
            // dump($medias);
            // dump(array_keys($medias));
            // dump($index);
            // die;
            $hotelActivity = new \App\Entity\HotelActivity();
            if (isset($medias[$index])) {
                $media = $medias[$index];
                $hotelActivity->setPrimaryImage($media);
            }
            if (isset($activity['pushForward'])) {
                $hotelActivity->setPushForward($activity['pushForward']);
            }
            $hotelActivity->setName($activity['name']);
            $hotelActivity->setDescription($activity['description']);
            $hotelActivity->setCategory($tag);
            $hotelActivity->addTag($tag);
            $this->manager->persist($hotelActivity);
            $this->manager->flush();
        }
        $this->consoleOutput->writeln('<comment>loadActivities</comment>');
    }

    private function loadAmenities()
    {
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/rooms.json';
        $rooms = file_get_contents($pathJsonFile);
        $rooms = json_decode($rooms, true);
        $tagDefault = $this->tags['accueil'];

        foreach ($rooms as $key => $value) {
            if (isset($value['amenities'])) {
                $slug = $this->slugify->slugify($value['name']);
                if (!isset($this->tags[$slug])) {
                    $tag = new \App\Entity\Tag();
                    $tag->setName($value['name']);
                    $this->manager->persist($tag);
                    $this->manager->flush();
                    $this->tags[$slug] = $tag;
                    foreach ($value['amenities'] as $key => $amenity) {
                        $slug = $this->slugify->slugify($amenity['name']);
                        if (isset($amenity['category'])) {
                            $slug = $this->slugify->slugify($amenity['category']);
                            if (!isset($this->tags[$slug])) {
                                $category = new \App\Entity\Tag();
                                $category->setName($amenity['category']);
                                $this->manager->persist($tag);
                                $this->manager->flush();
                                $this->tags[$slug] = $category;
                            } else {
                                $category = $this->tags[$slug];
                            }
                            $slug = $this->slugify->slugify($amenity['name'].' '.$amenity['category']);
                        } else {
                            $category = $tagDefault;
                        }

                        if (!isset($this->amenities[$slug])) {
                            $hotelAmenity = new \App\Entity\HotelAmenity();
                            $name = null;
                            if (isset($amenity['name'])) {
                                $name = $amenity['name'];
                            }
                            $hotelAmenity->setName($name);
                            $moreInfo = '';
                            if (isset($amenity['moreInfo'])) {
                                $moreInfo = $amenity['moreInfo'];
                            }
                            $hotelAmenity->setMoreInfo($moreInfo);
                            $hotelAmenity->setCategory($category);
                            $hotelAmenity->addTag($tag);
                            $this->manager->persist($hotelAmenity);
                            $this->manager->flush();
                            $this->amenities[$slug] = $hotelAmenity;
                        } else {
                            $entity = $this->amenities[$slug];
                            $entity->addTag($tag);
                            $this->manager->persist($entity);
                            $this->manager->flush();
                        }
                    } // end foreach amenities
                } // endif tags
            }
        }

        // $randomNumber = rand(0, count($this->tags) - 1);
        // $tag = $this->tags[$randomNumber];

        $pathJsonFile = $kernelProjectDir.'/data/amenities.json';
        $amenities = file_get_contents($pathJsonFile);
        $amenities = json_decode($amenities, true);
        foreach ($amenities as $amenity) {
            $hotelAmenity = new \App\Entity\HotelAmenity();
            $hotelAmenity->setName($amenity);
            $hotelAmenity->setCategory($tagDefault);
            // $hotelAmenity->addTag($tag);
            $this->manager->persist($hotelAmenity);
            $this->manager->flush();
        }
        $this->consoleOutput->writeln('<comment>loadAmenities</comment>');
    }

    private function loadServices()
    {
        //     $randomNumber = rand(0, count($this->tags) - 1);
        //     $tag = $this->tags[$randomNumber];
        $tag = $this->tags['accueil'];
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/services.json';
        $services = file_get_contents($pathJsonFile);
        $services = json_decode($services, true);
        foreach ($services as $service) {
            $slug = $this->slugify->slugify($service['category']);
            if (!isset($this->tags[$slug])) {
                $tag = new \App\Entity\Tag();
                $tag->setName($service['category']);
                $this->manager->persist($tag);
                $this->manager->flush();
                $this->tags[$slug] = $tag;
            }
            $hotelService = new \App\Entity\HotelService();
            $hotelService->setName($service['name']);
            $hotelService->setMoreInfo($service['moreInfo']);
            $hotelService->setCategory($tag);
            $hotelService->addTag($tag);
            $this->manager->persist($hotelService);
            $this->manager->flush();
        }
        $this->consoleOutput->writeln('<comment>loadServices</comment>');
    }
}
