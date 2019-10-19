<?php

namespace App\EventSubscriber;

use App\Controller\AuthenticatedController;
use App\Helper\JwtParse;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


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
            try {
            $token = $_GET['token'];
            JwtParse::decode($token);
            $event->getRequest()->attributes->set('auth_token', $token);
            } catch (\Exception $e) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            }
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        // Skip if request is not an API-request
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), '/api/') !== 0) {
            return;
        }

        $exception = $event->getException();
        $error = [
            'type' => $this->getErrorTypeFromException($exception),
            'message' => $exception->getMessage(),
        ];
        $response = new JsonResponse($error, $this->getStatusCodeFromException($exception));
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    private function getErrorTypeFromException(\Throwable $exception): string
    {
        $parts = explode('\\', get_class($exception));

        return end($parts);
    }

    private function getStatusCodeFromException(\Throwable $exception): int
    {
        if ($exception instanceof HttpException) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
