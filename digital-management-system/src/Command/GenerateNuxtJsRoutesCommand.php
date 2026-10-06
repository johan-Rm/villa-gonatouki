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
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Twig\Environment;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class GenerateNuxtJsRoutesCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:generate-nuxtjs-routes';

    private $container;

    private $mailer;

    private $templating;

    private $rootBuild = false;

    private $isWriteLn = true;

    private $hasSent = true;

    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container, Environment $templating)
    {
        $this->container = $container;

        $this->mailer = $mailer;

        $this->templating = $templating;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Generate new NuxtJS routes.')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to generate new NuxtJS routes...')
        ->addArgument('full', InputArgument::OPTIONAL, 'Full build ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailerUser = $this->container->getParameter('mailer_user');
        $mailerAdmin = $this->container->getParameter('mailer_admin');
        $mailerDeveloper = $this->container->getParameter('mailer_developer');
        $nuxtProjectDir = $this->container->getParameter('view.project_dir');

        $exportApiToJson = $this->container->get('app.export.api_to_json');

        $viewHost = $this->container->getParameter('view.host');
        $sitewebTitle = $this->container->getParameter('siteweb.title');

        $nuxtRoutesToBuildFolder = $this->container->getParameter('view.update.build_routes.path').DIRECTORY_SEPARATOR;
        $shortLogs = [];
        $logs = [];
        $start = 'start to => '.date('Y-m-d\ H:i:s.u');
        $this->writeln($output, $start);
        $logs[] = $start;

        $success = false;
        if ($this->lock()) {
            $filesystem = new Filesystem();
            if (!$filesystem->exists($this->container->getParameter('view.update.build_routes.path'))) {
                $filesystem->mkdir($this->container->getParameter('view.update.build_routes.path'));
            }

            $full = $input->getArgument('full');
            if ('full' === $full) {
                $successTitle = 'Nouvelle mise à jour des données complètes';
                $status = false;
                // Execution de la commande de génération de toutes les routes
                $command = $nuxtProjectDir.'/synchronization/production-build-full.sh';
                $process = new Process(
                    [$command, $nuxtProjectDir]
                );
                $process->setTimeout(10800); // 3 heures

                /* logs **/
                $this->writeln($output, $command);
                $logs[] = 'run command full';
                $logs[] = $command;

                $shortLogs[] = $viewHost.'/';

                try {
                    $process->mustRun();
                    /* logs **/
                    $this->writeln($output, $process->getOutput());
                    $logs[] = 'success';
                    $logs[] = $process->getOutput();
                    $status = true;
                } catch (ProcessFailedException $exception) {
                    /* logs **/
                    $this->writeln($output, $process->getOutput());
                    $logs[] = 'error';
                    $logs[] = $exception->getMessage();
                }

                // nettoyage complet des dossiers contenant les json de mise à jour des datas
                if (true === $status) {
                    // $finder = new Finder();
                    // $finder->files()->in($waitingFolder);
                    // if ($finder->hasResults()) {
                    //     foreach ($finder as $file) {
                    //         $absoluteFilePath = $file->getRealPath();
                    //         $fileNameWithExtension = $file->getRelativePathname();
                    //         $filesystem->remove($absoluteFilePath);
                    //     }
                    // }

                    // $finder = new Finder();
                    // $finder->files()->in($processFolder);
                    // if ($finder->hasResults()) {
                    //     foreach ($finder as $file) {
                    //         $absoluteFilePath = $file->getRealPath();
                    //         $fileNameWithExtension = $file->getRelativePathname();
                    //         $filesystem->remove($absoluteFilePath);
                    //     }
                    // }

                    $success = true;
                }
            } else { /** MISE A JOUR DU DOSSIER BUILD_ROUTES **/
                $successTitle = 'CONSTRUCTION DES PAGES PARTIELS';
                $nuxtJsBuildService = $this->container->get('app.nuxtjs.build');
                $content = $nuxtJsBuildService->execute();
                // $this->writeln($output, $content);
            } // end build single routes
        } else {
            $message = 'The command is already running in another process.';
            $this->writeln($output, $message);
        }

        $end = 'end to =>  '.date('Y-m-d\ H:i:s.u');
        $logs[] = $end;
        $this->writeln($output, $start);
        $this->writeln($output, $end);

        // If you prefer to wait until the lock is released, use this:
        // $this->lock(null, true);
        // ...
        // if not released explicitly, Symfony releases the lock
        // automatically when the execution of the command ends
        $this->release();

        return 0;
    }

    private function writeln($output, $message)
    {
        if (true === $this->isWriteLn) {
            if (!is_array($message)) {
                $output->writeln($message);
            } else {
                $array = [];
                foreach (array_keys($message) as $key => $value) {
                    $array[] = [$value, $message[$value]];
                }
                $table = new Table($output);
                $table
                    ->setHeaders(['Key', 'Value'])
                    ->setRows($array)
                ;
                $table->render();
            }
        }

        return true;
    }
}
