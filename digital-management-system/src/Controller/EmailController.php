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

use App\Entity\Event;
use App\Entity\Person;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
* @Route("/email")
* ...
*
* @author Johan REMY <johan.remy@graines-digitales.online>
*/
class EmailController extends AbstractController
{
    /**
    * @var Swift_Mailer
    */
    private $mailer;

    /**
    * @var TranslatorInterface
    */
    private $translator;

    public function __construct(\Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    /**
     * @Route("/send-test", name="send-test")
     * @Method({"GET","OPTIONS"})
     */
    public function testEmail(Request $request): JsonResponse
    {
        $mailerDeveloper = $this->getParameter('mailer_developer');
        $message = (new \Swift_Message('Email de test'))
            ->setFrom(trim(strtolower('test@email.fr')))
            ->setTo(trim(strtolower($mailerDeveloper)))
            ->setBody('<p>this is teh test</p>', 'text/html')
        ;   
        $this->mailer->send($message);

        return new JsonResponse(
            'send test ok',
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/confirmation-contact", name="contact-email")
     * @Method({"GET","OPTIONS"})
     * @ Method({"POST"})
     */
    public function contactEmail(Request $request): JsonResponse
    {
        $date = date('Y');
        $mailerUser = $this->getParameter('mailer_user');
        $mailerAdmin = $this->getParameter('mailer_admin');
        $mailerDeveloper = $this->getParameter('mailer_developer');
        $logo = $this->getParameter('cdn.host').'/uploads/media/files/demo-logo-orange.png';
        $params = $request->request->all();
        // dump($logo);
        // die;
        // dump($params);
        // $params = [
        //     'firstname' => 'johan',
        //     'lastname' => 'maurice',
        //     'to' => 'johan13.remy@gmail.com',
        //     'from' => 'contact@graines-digitales.online',
        //     'message' => 'messaaaaaage',
        //     'locale' => 'fr',
        //     "person" => "/api/people/1",
        //     "event" => "/api/events/21"
        // ];
        // die;
        // $params['booking'] = [
        //     'rooms' => [
        //         'chambre-1' => [
        //             'name' => 'chambre 1',
        //             'numberOfRooms' => 2
        //         ],
        //         'chambre-2' => [
        //             'name' => 'chambre 2',
        //             'numberOfRooms' => 1
        //         ]
        //     ],
        //     'selectedDates' => [
        //         'start' => date("Y/m/d"),
        //         'end' => date("Y/m/d")
        //     ]
        // ];
        $em = $this->getDoctrine()->getManager();
        $params['booking'] = [];
        $person = $em->getRepository(Person::class)->find($params['person']);
        if(isset($params['event'])) {
            $event = $em->getRepository(Event::class)->find($params['event']);
            

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
        }

        $messageType = (!empty($params['booking'])) ? 'réservation' : 'contact';
        $message = (new \Swift_Message('Nouvelle demande de '.$messageType))
            ->setFrom(trim(strtolower($params['to'])))
            ->setTo(trim(strtolower($params['from'])))
            // ->setTo($params['from'])
            ->setCc(trim(strtolower($mailerAdmin)))
            ->setCc(trim(strtolower($mailerDeveloper)))
            ->setBody(
                $this->renderView(
                    'components/contact-email.html.twig', [
                        'firstname' => $params['firstname'],
                        'lastname' => $params['lastname'],
                        'email' => $params['to'],
                        'phone' => $person->getPhone(),
                        'message' => $params['message'],
                        'logo' => $logo,
                        'date' => $date,
                        'room' => (isset($params['booking']['room']) ? $params['booking']['room'] : null),
                        'rooms' => (isset($params['booking']['rooms']) ? $params['booking']['rooms'] : null),
                        'price' => (isset($params['booking']['price']) ? $params['booking']['price'] : null),
                        'details' => (isset($params['booking']['details']) ? $params['booking']['details'] : null),
                        'message_type' => $messageType,
                        'selectedDates' => (isset($params['booking']['selectedDates']) ? $params['booking']['selectedDates'] : null),
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);

        $title = $this->translator->trans('Your message has been sent', [], 'messages', $params['locale']);
        $subTitle = $this->translator->trans('Nous avons bien pris en compte votre message et nous vous répondrons dans les meilleurs délais', [], 'messages', $params['locale']);
        $label = $this->translator->trans('Your message', [], 'messages', $params['locale']);

        $message = (new \Swift_Message($title))
            ->setFrom(trim(strtolower($params['from'])))
            ->setTo(trim(strtolower($params['to'])))
            ->setBody(
                $this->renderView(
                    'components/contact-confirmation-email.html.twig', [
                        'title' => $title,
                        'subTitle' => $subTitle,
                        'label' => $label,
                        'message' => $params['message'],
                        'logo' => $logo,
                        'date' => $date,
                        'form_type' => (isset($params['booking']['form_type']) ? $params['booking']['form_type'] : null),
                        'room' => (isset($params['booking']['room']) ? $params['booking']['room'] : null),
                        'rooms' => (isset($params['booking']['rooms']) ? $params['booking']['rooms'] : null),
                        'price' => (isset($params['booking']['price']) ? $params['booking']['price'] : null),
                        'details' => (isset($params['booking']['details']) ? $params['booking']['details'] : null),
                        'message_type' => $messageType,
                        'selectedDates' => (isset($params['booking']['selectedDates']) ? $params['booking']['selectedDates'] : null),
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);

        return new JsonResponse(
            'contact message ok',
            JsonResponse::HTTP_OK
        );
    }
}
