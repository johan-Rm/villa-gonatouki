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
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Cocur\Slugify\Slugify;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
abstract class AbstractFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ConsoleOutput
     **/
    protected $consoleOutput;

    /**
     * @var Table
     **/
    protected $tableOutput;

    /**
     * @var ContainerInterface
     **/
    protected $container;

    /**
     * @var ObjectManager
     **/
    protected $manager;

    /**
     * @var Faker
     **/
    protected $faker;

    /**
     * @var Slugify
     **/
    protected $slugify;

    /**
    * @var array
    */
    protected $contextParameters = [];

    /**
    * @var Tools\Media
    */
    protected $toolsMediaService;

    /**
    * @var bool
    */
    protected $force = false;

    protected function initialize($manager)
    {
      $this->faker = Faker\Factory::create('fr_FR');
      $this->manager = $manager;
      $this->filesystem = new Filesystem();
      $this->slugify = new Slugify();
      $this->consoleOutput = $this->getConsoleOutput();
      $this->tableOutput = $this->getTableOutput();
      $this->toolsMediaService = $this->container->get('app.tools.media');
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getConsoleOutput()
    {
        $consoleOutput = new ConsoleOutput();
        // $outputStyle = new OutputFormatterStyle('red', 'yellow', ['bold', 'blink']);
        $outputStyle = new OutputFormatterStyle('yellow', null, ['bold']);
        $consoleOutput->getFormatter()->setStyle('fire', $outputStyle);

        return $consoleOutput;
    }

    public function getTableOutput()
    {
        $consoleOutput = new ConsoleOutput();

        $section = $consoleOutput->section();
        $tableOutput = new Table($consoleOutput);

        return $tableOutput;
    }

}
