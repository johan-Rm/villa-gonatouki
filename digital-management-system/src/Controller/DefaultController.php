<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Accommodation;
use App\Entity\Event;
use App\Entity\MediaObject;
use App\Entity\Message;
use App\Entity\Person;
use App\Entity\WebPage;
use App\Form\MessageType;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message as MessageNotification;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Recipient\Topic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/default")
* ...
*
* @author Johan REMY <johan.remy@graines-digitales.online>
*/
class DefaultController extends Controller
{
    /**
     * @Route("/", name="default", methods={"GET"})
     */
    public function index(): Response
    {
        $array = ['2', '4', '8', '5', '1', '7', '6', '9', '10', '3'];
        $result = $this->sortByAscending($array);
        var_dump($result);
        var_dump('END');
        exit();
    }

    private function compare($current, $next)
    {
        if ($current > $next) {
            return $next;
        }

        return false;
    }

    private function sortByAscending($array)
    {
        for ($j = 0; $j < count($array); ++$j) {
            for ($i = 0; $i < count($array) - 1; ++$i) {
                if ($this->compare($array[$i], $array[$i + 1])) {
                    $temp = $array[$i + 1];
                    $array[$i + 1] = $array[$i];
                    $array[$i] = $temp;
                }
            }
        }

        return $array;
    }

    public function division($a, $b)
    {
        $result = $a / $b;
        if ($result > 0) {
            return $result;
        }

        return 0;
    }

    /**
     * @Route("/contact-confirmation-email", name="webPage_testContactConfirmationEmail", methods={"GET"})
     */
    public function contactConfirmationEmail(): Response
    {
        $params = [
            'firstname' => 'johan',
            'lastname' => 'maurice',
            'to' => 'johan13.remy@gmail.com',
            'from' => 'contact@graines-digitales.online',
            'message' => 'messaaaaaage',
            'locale' => 'fr',
            'person' => '1',
            'event' => '30',
        ];
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($params['event']);
        $person = $manager->getRepository(Person::class)->find($params['person']);

        // dump($event);
        // dump($person);
        // die;

        $params['booking'] = [
            // 'room' => [
            //     'name' => $event->getRoom()->getName()
            // ],
            'rooms' => $event->getRooms(),
            'selectedDates' => [
                'start' => $event->getBeginAt()->format('Y-m-d'),
                'end' => $event->getEndAt()->format('Y-m-d'),
            ],
            'price' => $event->getPrice(),
            'details' => $event->getDetails(),
        ];

        $messageType = (isset($params['booking'])) ? 'réservation' : 'contact';
        $date = date('Y');
        $logo = $this->getParameter('cdn.host').'/uploads/media/files/logo_bg_primary_2.png';

        $title = $this->translator->trans('Your message has been sent', [], 'messages', 'fr');
        $subTitle = $this->translator->trans('Nous avons bien pris en compte votre message et nous vous répondrons dans les meilleurs délais', [], 'messages', 'fr');
        $label = $this->translator->trans('Your message', [], 'messages', 'fr');

        // dump('test email');die;
        return $this->render('components/contact-confirmation-email.html.twig', [
                'title' => $title,
                'subTitle' => $subTitle,
                'label' => $label,
                'message' => 'mon message',
                'logo' => $logo,
                'date' => $date,
                'room' => (isset($params['booking']['room']) ? $params['booking']['room'] : null),
                'rooms' => (isset($params['booking']['rooms']) ? $params['booking']['rooms'] : null),
                'price' => (isset($params['booking']['price']) ? $params['booking']['price'] : null),
                'details' => (isset($params['booking']['details']) ? $params['booking']['details'] : null),
                'message_type' => $messageType,
                'selectedDates' => (isset($params['booking']['selectedDates']) ? $params['booking']['selectedDates'] : null),
            ]
        );
    }

    /**
     * @Route("/contact-email", name="webPage_testContactEmail", methods={"GET"})
     */
    public function contactEmail(): Response
    {
        $params = [
            'firstname' => 'johan',
            'lastname' => 'maurice',
            'to' => 'johan13.remy@gmail.com',
            'from' => 'contact@graines-digitales.online',
            'message' => 'messaaaaaage',
            'locale' => 'fr',
            'person' => '1',
            'event' => '30',
        ];
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($params['event']);
        $person = $manager->getRepository(Person::class)->find($params['person']);

        // dump($event);
        // dump($person);
        // die;

        $params['booking'] = [
            // 'room' => [
            //     'name' => $event->getRoom()->getName()
            // ],
            'rooms' => $event->getRooms(),
            'selectedDates' => [
                'start' => $event->getBeginAt()->format('Y-m-d'),
                'end' => $event->getEndAt()->format('Y-m-d'),
            ],
            'price' => $event->getPrice(),
            'details' => $event->getDetails(),
        ];

        $messageType = (isset($params['booking'])) ? 'réservation' : 'contact';
        $date = date('Y');
        $logo = $this->getParameter('cdn.host').'/uploads/media/files/logo_bg_primary_2.png';

        // dump('test email');die;
        return $this->render('components/contact-email.html.twig', [
                'firstname' => 'remy',
                'lastname' => 'johan',
                'email' => 'johan@email.fr',
                'phone' => '(+33) 0102030201',
                'message' => 'mon message',
                'logo' => $logo,
                'date' => $date,
                'room' => (isset($params['booking']['room']) ? $params['booking']['room'] : null),
                'rooms' => (isset($params['booking']['rooms']) ? $params['booking']['rooms'] : null),
                'price' => (isset($params['booking']['price']) ? $params['booking']['price'] : null),
                'details' => (isset($params['booking']['details']) ? $params['booking']['details'] : null),
                'message_type' => $messageType,
                'selectedDates' => (isset($params['booking']['selectedDates']) ? $params['booking']['selectedDates'] : null),
            ]
        );
    }

    /**
     * @Route("/test-technical-card", name="webPage_testTechnicalCard", methods={"GET"})
     */
    public function buildPdf(): Response
    {
        $accommodation = $this->getDoctrine()
            ->getRepository(Accommodation::class)
            ->findOneById(139);

        $cdnHost = $this->container->getParameter('cdn.host');
        $logo = $cdnHost.'/uploads/media/files/logo_bg_primary_2.png';

        $plan = false;
        foreach ($accommodation->getPdfs() as $pdf) {
            if ('plan' === $pdf->getType()->getSlug()) {
                $plan = $cdnHost.'/uploads/document/files/'.$pdf->getFilename();
            }
        }

        $host = $cdnHost.'/uploads/media/files/';

        return $this->render('components/technical-card.html.twig', [
            'accommodation' => $accommodation,
            'logo' => $logo,
            'host' => $host,
            'plan' => $plan,
            'locale' => 'en',
        ]);
    }

    /**
     * @ Route(
     *  "/{tag}"
     *  , name="webPage_list"
     *  , methods={"GET"}
     *  , requirements={
     *      "tag": "^(?!management|contact|login|logout|api|register|resetting|form|build).[0-9a-zA-Z\-]*"
     *  }
     * )
     */
    public function list(): Response
    {
        $media = $this->getDoctrine()
            ->getRepository(MediaObject::class)
            ->findOneById(3);

        $webpages = $this->getDoctrine()
            ->getRepository(WebPage::class)
            ->findAll();

        return $this->render('frontend/pages/list.html.twig', [
            'webPages' => $webpages,
            'media' => $media,
        ]);
    }

    /**
     * @ Route(
     *  "/{tag}/{slug}"
     *  , name="webPage_show"
     *  , methods={"GET"}
     *  , defaults={"tag": "open_source", "slug": "price"}
     *  , requirements={
     *      "tag": "^(?!management|contact|login|logout|api|register|resetting|form|build).[0-9a-zA-Z\-]*",
     *      "page": "[0-9a-zA-Z\/\-]*"
     *  }
     * )
     */
    public function show(Request $request, WebPage $webpage): Response
    {
        // dump($request->attributes->all());
        // dump($webpage);
        // die();

        return $this->render('frontend/pages/show.html.twig', [
            'webPage' => $webpage,
        ]);
    }

    /**
     * @ Route(
     *   "/{tag}/{_locale}/{year}/{page}.{_format}"
     *   ,name="webPage_article"
     *   ,defaults={"_format": "html"}
     *   ,requirements={
     *       "_locale": "en|fr",
     *       "_format": "html|rss",
     *       "year": "\d+"
     *   }
     * )
     */
    public function article(WebPage $webpage): Response
    {
        // return $this->render('frontend/pages/article.html.twig', [
        //     'webPage' => $webpage,
        // ]);
    }

    /**
     * @ Route("/contact", name="webPage_contact", methods={"GET"})
     */
    public function contact(Request $request): Response
    {
        // $message = new Message();
        // $form = $this->createForm(MessageType::class, $message);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($message);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('webPage_index');
        // }

        // return $this->render('frontend/pages/contact.html.twig', [
        //     'message' => $message,
        //     'form' => $form->createView(),
        // ]);
    }

    /**
     * @Route("/notification-push-with-token", name="webPage_notificationPushWithToken", methods={"GET"})
     */
    public function notificationWithToken(Request $request): Response
    {
        $client = $this->get('moskalyovd_fcm.client');
        $now = new \DateTime();

        $data = [
            'title' => 'jjjjjjjjjjjjjjjjj', 'is_background' => false, 'message' => 'mmmmmmmmmmmmmmmmm', 'image' => "https:\/\/api.androidhive.info\/images\/minion.jpg",
        ];

        // $data = json_encode($data);

        $token = 'dk5FOCWqntU:APA91bG1b6zXbrBfpVZegIDhGZ5X0YjJOi5yzTgXh9ttp6tOMZYANj6TNyXoN_c0n-Q3CANx_1GI9DJfcCV-SA6GbWgsXYv_60uwGUlwrMbCkPxVRH5tmSlqD20BSNWdr_ERwMxegjhm';
        $token = 'd3C28FQ9JvA:APA91bG3BIZLulPCZYaN205hvtYZBBEVeDWX-u_skB6i43rcSgyu52ylvbQPnIsx3wSzCBq5DtMbGeqfHEJ2Veb19HsTVXUe_sCg5aTi7nDoqAb8wpamC4rhpC8gMLzz8KELI7RGR2y1';
        $message = new MessageNotification();
        $message->addRecipient(new Device($token));
        $message->setNotification(new Notification('The big notification', 'Youhouuuuuuu il est '.$now->format('Y-m-d\TH:i:s'), "https:\/\/api.androidhive.info\/images\/minion.jpg"));

        $response = $client->send($message);
        dump($response);
        exit();
    }

    /**
     * @Route("/notification-push-with-topic", name="webPage_notificationPushWithTopic", methods={"GET"})
     */
    public function notificationWithtopic(Request $request): Response
    {
        $client = $this->get('moskalyovd_fcm.client');

//         $apiKey = 'YOUR_GOOGLE_MAPS_API_KEY';
//         $client = new Client();
//         $client->setApiKey($apiKey);
//         $client->setProxyApiUrl('https://fcm.googleapis.com/fcm/send');
//         $client->injectHttpClient(new \GuzzleHttp\Client());

        $message = new MessageNotification();
        $message->addRecipient(new Topic('com.kaizen.cms.app'));
        $notif = new Notification('test title', 'testing body');
        $notif = ['test title', 'testing body'];
        $message->setNotification($notif)
            ->setData(['someId' => 111]);
        $response = $client->send($message);
        dump($response);
        exit;
    }
}
