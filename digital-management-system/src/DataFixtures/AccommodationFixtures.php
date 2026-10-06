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

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AccommodationFixtures extends AbstractFixtures implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['real_estate'];
    }

    public function load(ObjectManager $manager)
    {
        dump('load AccommodationFixtures');
        $this->initialize($manager);

        $this->loadRealEstateAgents($manager);
    }

    private function loadRealEstateAgents()
    {
        $person = new \App\Entity\Person();
        $person->setLastname('LAURENT-SCHOPPE');
        $person->setFirstname('Natacha');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('+212 (0) 673 256 389');
        $person->setEmail('contact@immobiliere-essaouira.com');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('employé interne');
        $this->manager->persist($person);
        $this->manager->flush();
        $photo = $this->manager->getRepository(\App\Entity\MediaObject::class)->findOneById(8);
        $realEstateAgent = new \App\Entity\RealEstateAgent();
        $realEstateAgent->setPrimaryImage($photo);
        $realEstateAgent->setPerson($person);
        $realEstateAgent->setPhone('+212673256389');
        $realEstateAgent->setEmail('natacha.schoppe@immobiliere-essaouira.com');
        $realEstateAgent->setDescription('Directrice d\'agence');
        $this->manager->persist($realEstateAgent);
        $this->manager->flush();

        $person = new \App\Entity\Person();
        $person->setLastname('LAURENT');
        $person->setFirstname('Stéphane');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('+212 (0) 673 018 201');
        $person->setEmail('contact@immobiliere-essaouira.com');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('employé interne');
        $this->manager->persist($person);
        $this->manager->flush();
        $photo = $this->manager->getRepository(\App\Entity\MediaObject::class)->findOneById(9);
        $realEstateAgent = new \App\Entity\RealEstateAgent();
        $realEstateAgent->setPrimaryImage($photo);
        $realEstateAgent->setPerson($person);
        $realEstateAgent->setPhone('+212673018201');
        $realEstateAgent->setEmail('stephane.laurent@immobiliere-essaouira.com');
        $realEstateAgent->setDescription('Directeur d’agence, Responsable transactions et suivis de chantiers');
        $this->manager->persist($realEstateAgent);
        $this->manager->flush();

        $person = new \App\Entity\Person();
        $person->setLastname('TIBARI');
        $person->setFirstname('Irina');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('+212 (0) 524 785 823');
        $person->setEmail('irina.essaouira@gmail.com');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('employé interne');
        $this->manager->persist($person);
        $this->manager->flush();
        $photo = $this->manager->getRepository(\App\Entity\MediaObject::class)->findOneById(7);
        $realEstateAgent = new \App\Entity\RealEstateAgent();
        $realEstateAgent->setPrimaryImage($photo);
        $realEstateAgent->setPerson($person);
        $realEstateAgent->setPhone('+212607855935');
        $realEstateAgent->setEmail('irina.tibari@immobiliere-essaouira.com');
        $realEstateAgent->setDescription('Directeur d\'agence, Responsable transactions et suivis de chantiers');
        $this->manager->persist($realEstateAgent);
        $this->manager->flush();

        $person = new \App\Entity\Person();
        $person->setLastname('BOURGEAUX');
        $person->setFirstname('Grégory');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('+212(0) 672 839 714 ');
        $person->setEmail('greg.essaouira@gmail.com');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('employé interne');
        $this->manager->persist($person);
        $this->manager->flush();
        $photo = $this->manager->getRepository(\App\Entity\MediaObject::class)->findOneById(10);
        $realEstateAgent = new \App\Entity\RealEstateAgent();
        $realEstateAgent->setPrimaryImage($photo);
        $realEstateAgent->setPerson($person);
        $realEstateAgent->setPhone('+212672839714');
        $realEstateAgent->setEmail('greg.bourgeaux@immobiliere-essaouira.com');
        $realEstateAgent->setDescription('Négociateur en locations longues durées, chargé d\'accueil clientèle saisonnière');
        $this->manager->persist($realEstateAgent);
        $this->manager->flush();

        // dump($organization);
        // die();

        $person = new \App\Entity\Person();
        $person->setLastname('HAFIANE');
        $person->setFirstname('Selma');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('');
        $person->setEmail('');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('');
        $this->manager->persist($person);
        $this->manager->flush();

        $person = new \App\Entity\Person();
        $person->setLastname('REMY');
        $person->setFirstname('Johan');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('');
        $person->setEmail('');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('');
        $this->manager->persist($person);
        $this->manager->flush();

        $person = new \App\Entity\Person();
        $person->setLastname('HANINI');
        $person->setFirstname('Mohamed');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('');
        $person->setEmail('');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('');
        $this->manager->persist($person);
        $this->manager->flush();
    }
}
