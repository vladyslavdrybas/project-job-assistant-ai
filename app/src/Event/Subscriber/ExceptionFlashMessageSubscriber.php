<?php

declare(strict_types=1);

namespace App\Event\Subscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ExceptionFlashMessageSubscriber implements EventSubscriberInterface
{
    protected string $environment;
    protected FlashBagInterface $flash;

    public function __construct(
        protected readonly string $projectEnvironment,
        protected readonly ParameterBagInterface $parameterBag,
        protected readonly RequestStack $requestStack
    ) {
        $this->flash = $this->requestStack->getSession()->getFlashBag();
    }

    public static function getSubscribedEvents(): array
    {
        return [
//            KernelEvents::EXCEPTION => ['onKernelException', 100],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (str_starts_with($event->getRequest()->server->get('REQUEST_URI'), '/api')) {
            return;
        }

        $exception = $event->getThrowable();
        $isAccessDenied = $exception instanceof AccessDeniedException;
        $isNotFound = $exception instanceof NotFoundHttpException;
        $isMethodNotAllowed = $exception instanceof MethodNotAllowedException;
        $isMessageWithChanges = true;

        if ($isAccessDenied) {
            $code = Response::HTTP_UNAUTHORIZED;
            $message = $code . '. Access denied.';
        } else if ($isNotFound) {
            $code = Response::HTTP_NOT_FOUND;
            $message = $code . '. Not found';
        } else if ($isMethodNotAllowed) {
            $code = Response::HTTP_METHOD_NOT_ALLOWED;
            $message = $code . '. Method not allowed.';
        } else {
            $isMessageWithChanges = false;
            $code = Response::HTTP_BAD_REQUEST;
            $message = $code . '. ' . $exception->getMessage();
        }

        if (
            $isMessageWithChanges
            && ($this->projectEnvironment === 'local' || $this->projectEnvironment === 'dev')
        ) {
            $message .= '. ' . $exception->getMessage();
        }

        $this->flash->set('danger', $message);
    }
}
