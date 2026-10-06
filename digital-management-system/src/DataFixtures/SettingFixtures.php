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

use App\Entity\Organization;
use App\Entity\OrganizationType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class SettingFixtures extends AbstractFixtures implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['AppFixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $this->initialize($manager);
        $start = date('Y-m-d\ H:i:s.u');

        $this->consoleOutput->writeln(
            sprintf('<fire>Start load SettingFixtures : %s</fire>', $start)
        );

        $this->loadOrganization($manager);
        $this->loadSocialLink();

        $this->consoleOutput->writeln('');
    }

    private function loadSocialLink()
    {
        $type = $this->manager->getRepository(OrganizationType::class)
            ->findOneBy(['slug' => 'lien-reseau-social']);

        $organization = new Organization();
        $organization->setName('Facebook');
        $organization->setUrl('https://web.facebook.com/ImmobilierEssaouira/');
        $organization->setType($type);

        $this->manager->persist($organization);
        $this->manager->flush();

        $organization = new Organization();
        $organization->setName('Youtube');
        $organization->setUrl('https://www.youtube.com/channel/UC_5LBYKZCyI3C54KmHjiTWQ');
        $organization->setType($type);

        $this->manager->persist($organization);
        $this->manager->flush();

        $organization = new Organization();
        $organization->setName('Instagram');
        $organization->setUrl('https://www.instagram.com/explore/locations/276862122394945/limmobiliere-dessaouira/');
        $organization->setType($type);

        $this->manager->persist($organization);
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadSocialLink</comment>');
    }

    private function loadOrganization()
    {
        $address = new \App\Entity\Address();
        $address->setAddress($this->container->getParameter('organization.address.name'));
        $address->setCity($this->container->getParameter('organization.address.city'));
        $address->setPostcode($this->container->getParameter('organization.address.postalcode'));
        $address->setCountry($this->container->getParameter('organization.address.country'));
        $this->manager->persist($address);
        $this->manager->flush();

        $organization = new \App\Entity\Organization();
        $image1 = $this->manager->getRepository(\App\Entity\MediaObject::class)->findOneById(2);
        $image2 = $this->manager->getRepository(\App\Entity\MediaObject::class)->findOneById(3);
        $organization->setPrimaryImage($image1);
        $organization->setSecondaryImage($image2);
        $organization->setName($this->container->getParameter('organization.name'));
        $organization->setLegalName($this->container->getParameter('organization.legal.name'));
        $organization->setPhone($this->container->getParameter('organization.phone'));
        $organization->setMobilePhone($this->container->getParameter('organization.mobile_phone'));
        $organization->setUrl($this->container->getParameter('organization.url'));
        $organization->setEmail($this->container->getParameter('organization.email'));
        $date = new \DateTime($this->container->getParameter('organization.founding.date'));
        $organization->setFoundingDate($date);
        $organization->setNumberOfEmployees('2');
        $organization->setNumberOfProjects('50');
        $organization->addAddress($address);
        $this->manager->persist($organization);
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadOrganization</comment>');
    }
}
