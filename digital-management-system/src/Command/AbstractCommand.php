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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
    * @var $locale
    */
    protected $locale;

    public function __construct(ContainerInterface $container, TranslatorInterface $translator)
    {
        $this->container = $container;
        $this->translator = $translator;
        $this->locale = $this->container->getParameter('locale');

        parent::__construct();
    }

    // public function setContainer(ContainerInterface $container): void
  // {
  //      $this->container = $container;
  // }
  //
  // public function setTranslator(TranslatorInterface $translator): void
  // {
  //      $this->translator = $translator;
  // }
}
