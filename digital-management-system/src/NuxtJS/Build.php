<?php

namespace App\NuxtJS;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Twig\Environment;


class Build
{
    private $container;

    private $em;

    private $mailer;

    private $templating;

    private $logs = [];

    public function __construct(ContainerInterface $container, EntityManager $em, \Swift_Mailer $mailer, Environment $templating)
    {
        $this->container = $container;

        $this->em = $em;

        $this->mailer = $mailer;

        $this->templating = $templating;
    }

    public function execute($full = false)
    {
        $nuxtProjectDir = $this->container->getParameter('view.project_dir');
        $logs = [];
        if ($this->generateJsonData($full)) {

            $tDate = date('Y-m-d\ H:i:s.u');
            $logs[] = 'build single START to => ' . $tDate;


            /**
             * on lance un seul process
             **/
            $command = $nuxtProjectDir . "/synchronization/production-build-full.sh";
            // $command = $nuxtProjectDir . "/synchronization/production-build-root.sh";
            $process = new Process(
                [$command]
            );
            $process->setTimeout(1200);

            /** logs **/

            $logs[] = '<comment>' . $command;
            $logs[] = 'run command root';
            $logs[] = $command;

            $filesystem = new Filesystem();
            try {

                $process->mustRun();

                /** logs **/
                // $logs[] = $process->getOutput();
                $logs[] = 'success';
            } catch (ProcessFailedException $exception) {

                /** logs **/
                // $logs[] = $process->getOutput();
                $logs[] = 'error';
                $logs[] = $exception->getMessage();

                throw new \RuntimeException($exception->getMessage());
            }

            $tDate = date('Y-m-d\ H:i:s.u');
            $logs[] = 'build single END to => ' . $tDate;

            /**
             * A créé avant d'envoyer l'email
             **/
            // $logsText = implode("\r\n", $logs);
            // $filesystem = new Filesystem();
            // $filesystem->dumpFile(
            //     'log_info_generate_single_route.txt'
            //     , $logsText
            // );

            $title = 'Nouvelle mise à jour des données';

            $logs[] = $this->sendMail($title, true, $this->logs['routes']);
        } else {

            // throw new \RuntimeException('Build:execute() error generate json data');
        }
        $this->setLogs($logs);

        return $this->logs['standard'];
    }

    private function generateJsonData($full)
    {
        $exportApiToJsonService = $this->container->get('app.export.api_to_json');
        $viewHost = $this->container->getParameter('view.host');
        $nuxtRoutesToBuildPath = $this->container->getParameter(
            'view.update.build_routes.path'
        ) . DIRECTORY_SEPARATOR;
        $finder = new Finder();
        // find all files in the current directory
        $finder->files()->in($nuxtRoutesToBuildPath);
        // check if there are any search results
        if ($finder->hasResults()) {


            // si une les données ont déja été récupérés on ne refait pas le call api
            $apiCalls = [];
            $logs = [];
            $routesLog = [];
            foreach ($finder as $file) {

                /**
                 *  l'export se fait via tache cron ...
                 **/

                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $routeConfig = json_decode($file->getContents(), true);
                // if(!isset($apiCalls[$routeConfig['route']])) {
                //     $exportApiToJsonService->generateList($routeConfig['route']);
                //     $apiCalls[$routeConfig['route']] = true;

                //     $logs[] =  'generate json datas => ' . $routeConfig['route'];
                //     $tDate = date('Y-m-d\ H:i:s.u');
                //     $logs[] = 'end of generate json datas => ' . $tDate;
                // }

                $routesLog[] = $viewHost . $routeConfig['baseUrl'] . $routeConfig['slug'];
            }
            $this->setLogs($logs);
            $this->setLogs($routesLog, 'routes');
        } else {

            return false;
        }

        return true;
    }

    private function setLogs($list, $flux = 'standard')
    {
        $this->logs['routes'] = [];
        $this->logs['standard'] = [];
        if ('routes' === $flux) {
            $this->logs['routes'] = array_merge($this->logs['routes'], $list);
        } else {
            $this->logs['standard'] = array_merge($this->logs['standard'], $list);
        }
    }

    private function sendMail($title, $status, $logs)
    {
        $viewHost = $this->container->getParameter('view.host');
        $mailerUser = $this->container->getParameter('mailer_user');
        $mailerAdmin = $this->container->getParameter('mailer_admin');
        $mailerDeveloper = $this->container->getParameter('mailer_developer');
        $sitewebTitle = $this->container->getParameter('siteweb.title');
        $logo = $this->container->getParameter('cdn.host') . '/uploads/media/files/logo_bg_primary_2.png';


        $title = 'Nouvelle mise à jour des données';
        $status = true;

        $message = (new \Swift_Message($sitewebTitle . ' - nouvelle mise à jour des données'))
            ->setFrom($mailerUser)
            // ->addTo($mailerAdmin[0])
            // ->addTo($mailerAdmin[1])
            ->setTo($mailerAdmin)
            ->setCc($mailerDeveloper)
            // ->setTo($params['from'])
            ->setBody(
                $this->templating->render(
                    'components/production-build-email.html.twig',
                    [
                        'title' => $title,
                        'logs' => $logs,
                        'logo' => $logo,
                        'status' => $status,
                        'viewHost' => $viewHost,
                        'mailerAdmin' => $mailerAdmin
                    ]
                ),
                'text/html'
            )
            ->attach(\Swift_Attachment::fromPath(
                $this->container
                    ->get('kernel')
                    ->getProjectDir() . '/log_info_generate_single_route.txt'
            ));

        $this->mailer->send($message);
        $logs[] = 'message has been sent';
    }
}
