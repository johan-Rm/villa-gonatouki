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
class ComponentFixtures extends AbstractFixtures implements FixtureGroupInterface
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
            sprintf('<fire>Start load ComponentFixtures : %s</fire>', $start)
        );

        $this->loadComponents($manager);
        $this->loadComponentInformations($manager);
        $this->loadComponentGoogleAnalytics($manager);

        $this->consoleOutput->writeln('');
    }

    private function loadComponentGoogleAnalytics()
    {
        $setting = new \App\Entity\Component();
        $name = 'Google Analytics';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        $setting->setMainEntity('http://schema.org/MyEntity');

        $propertyValue = new \App\Entity\PropertyValue();
        $propertyValue->setName('Account');
        $propertyValue->setValue('UA-XXXXXXXX-X');
        $this->manager->persist($propertyValue);
        $setting->addPropertyValue($propertyValue);

        $this->manager->persist($setting);
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadComponentGoogleAnalytics</comment>');
    }

    private function loadComponentInformations()
    {
        $setting = new \App\Entity\Component();
        $name = 'Informations';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        // $setting->setMainEntity('http://schema.org/MyEntity');

        $propertyValue = new \App\Entity\PropertyValue();
        $propertyValue->setName('Nom du site');
        $propertyValue->setValue($this->container->getParameter('organization.name'));
        $this->manager->persist($propertyValue);
        $setting->addPropertyValue($propertyValue);

        $propertyValue = new \App\Entity\PropertyValue();
        $propertyValue->setName('Slogan');
        $propertyValue->setValue($this->container->getParameter('organization.push.forward'));
        $this->manager->persist($propertyValue);
        $setting->addPropertyValue($propertyValue);

        $propertyValue = new \App\Entity\PropertyValue();
        $propertyValue->setName('Email');
        $propertyValue->setValue($this->container->getParameter('organization.email'));
        $this->manager->persist($propertyValue);
        $setting->addPropertyValue($propertyValue);

        $this->manager->persist($setting);
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadComponentInformations</comment>');
    }

    public function loadComponents()
    {
        $setting = new \App\Entity\Component();
        $name = 'Contact form';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        $setting->setMainEntity('http://schema.org/Messsage');
        $setting->setService('form');
        $setting->setType('contact');

        // $propertyValue = new \App\Entity\PropertyValue();
        // $propertyValue->setName('Name of the site');
        // $propertyValue->setValue('Graines Digitales');
        // $this->manager->persist($propertyValue);
        // $setting->addPropertyValue($propertyValue);

        // $propertyValue = new \App\Entity\PropertyValue();
        // $propertyValue->setName('Slogan');
        // $propertyValue->setValue('Mon joli slogan');
        // $this->manager->persist($propertyValue);
        // $setting->addPropertyValue($propertyValue);

        $this->manager->persist($setting);
        $this->manager->flush();

        $setting = new \App\Entity\Component();
        $name = 'Newsletter form';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        $setting->setMainEntity('http://schema.org/Messsage');
        $setting->setService('form');
        $setting->setType('newsletter');

        // $propertyValue = new \App\Entity\PropertyValue();
        // $propertyValue->setName('Account');
        // $propertyValue->setValue('UA-XXXXXXXX-X');
        // $this->manager->persist($propertyValue);
        // $setting->addPropertyValue($propertyValue);

        $this->manager->persist($setting);
        $this->manager->flush();

        // $messages = $this->manager->getRepository(Message::class)->findAll();
        // $component = $this->manager->getRepository(Component::class)->findOneById(2);
        // foreach($messages as $message) {

        //     $message->setComponent($component);
        //     $this->manager->persist($message);

        // }

        $setting = new \App\Entity\Component();
        $name = 'Related articles';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        $setting->setMainEntity('null');
        $setting->setService('list');
        $setting->setType('article');
        $this->manager->persist($setting);
        $this->manager->flush();

        $setting = new \App\Entity\Component();
        $name = 'Welcome';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        $setting->setMainEntity('null');
        $setting->setService('article');
        $setting->setType('lagence');
        $this->manager->persist($setting);
        $this->manager->flush();

        $setting = new \App\Entity\Component();
        $name = 'WhoWeAre';
        $setting->setName($name);
        $setting->setSlugPath($this->slugify->slugify($name));
        $setting->setMainEntity('null');
        $setting->setService('article');
        $setting->setType('nos-locaux');
        $this->manager->persist($setting);
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadComponents</comment>');
    }
}
