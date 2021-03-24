<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class MyEventListener {
    public function onKernelException(ExceptionEvent $event) {
        $exception = $event->getThrowable();

        $event->setResponse(new Response($exception->getMessage()));
    }
}
