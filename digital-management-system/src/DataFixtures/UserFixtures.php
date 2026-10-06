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

use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class UserFixtures extends AbstractFixtures implements FixtureGroupInterface
{
    /**
    * @var UserPasswordEncoderInterface
    */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getGroups(): array
    {
        return ['AppFixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $this->initialize($manager);
        $start = date('Y-m-d\ H:i:s.u');

        $this->consoleOutput->writeln(
            sprintf('<fire>Start load UserFixtures : %s</fire>', $start)
        );

        $person = new \App\Entity\Person();
        $person->setLastname('REMY');
        $person->setFirstname('Johan');
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('');
        $person->setPhoneCountry('');
        $person->setEmail('johan.remy@graines-digitales.online');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('');
        $this->manager->persist($person);
        $this->manager->flush();

        // php bin/console fos:user:change-password testuser iep@ssword
        $user = new User();
        $user->setUsername(sprintf('johan.remy'));
        $user->setEmail($person->getEmail());
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            sprintf('grainesDigitalesP@ssword')
        ));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setEnabled(true);
        $user->setPerson($person);
        $this->manager->persist($user);
        $this->manager->flush();

        $person = new \App\Entity\Person();
        $person->setLastname($this->container->getParameter('organization.person.lastname'));
        $person->setFirstname($this->container->getParameter('organization.person.firstname'));
        // $person->setGender();
        // $person->setBirthday();
        // $person->setPlaceOfBirth();
        $person->setPhone('');
        $person->setEmail('johan.remy@graines-digitales.online');
        // $person->setComment();
        // $person->addAddress();
        $person->setOrigin('');
        $this->manager->persist($person);
        $this->manager->flush();
        $person = $this->manager->getRepository(Person::class)->findOneById(2);
        $user = new User();

        $format = '%s.%s';
        $username = sprintf(
            $format, strtolower($this->container->getParameter('organization.person.firstname')), strtolower($this->container->getParameter('organization.person.lastname'))
        );
        $user->setUsername($username);
        $user->setEmail($this->container->getParameter('organization.email'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            sprintf('grainesDigitalesP@ssword')
        ));
        $user->setPerson($person);
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();

        $this->consoleOutput->writeln('');
    }
}
