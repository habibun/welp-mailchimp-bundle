<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Welp\MailchimpBundle\Event\SubscriberEvent;
use Welp\MailchimpBundle\Subscriber\Subscriber;

class MailchimpController extends AbstractController
{
    /**
     * @Route("/mailchimp", name="app_mailchimp")
     */
    public function index(): Response
    {
        return $this->render('mailchimp/index.html.twig');
    }

    /**
     * @Route("/subscribe", name="app_mailchimp_subscribe")
     */
    public function subscribe(Request $request, EventDispatcherInterface $eventdispatcher)
    {
        $subscriber = new Subscriber($request->request->get('email'), [], [
            'language' => $request->getLocale(),
        ]);

        try {
            $eventdispatcher->dispatch(
                SubscriberEvent::EVENT_SUBSCRIBE, new SubscriberEvent('b6909a170e', $subscriber)
            );
        } catch (\Exception $e) {
            throw $e;
            return new JsonResponse(['error' => 'An error has occured']);
        }

        return new JsonResponse(['success' => 'You have successfully subscribed to our newsletter']);
    }
}
