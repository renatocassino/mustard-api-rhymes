<?php

namespace App\EventSubscriber;

use App\Controller\AuthenticatedController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthSubscriber implements EventSubscriberInterface {
    // private $tokens;

    // public function __construct($tokens)
    // {
        // $this->tokens = $tokens;
    // }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AuthenticatedController) {
            // $token = $event->getRequest()->query->get('token');

            // throw new AccessDeniedHttpException('This action needs a valid token!');
        }

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiZW1haWwiOiJyZW5hdG9jYXNzaW5vQGdtYWlsLmNvbSIsImlhdCI6MTM1Njk5OTUyNCwibmJmIjoxMzU3MDAwMDAwfQ.c1C1_agMuohqr519P0TMX_pLGqykZWgK-E-7TL_gi5o';
        $event->getRequest()->attributes->set('auth_token', $token);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
