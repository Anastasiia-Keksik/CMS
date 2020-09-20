<?php


namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestTypeListener
{

    public function onKernelRequest(RequestEvent $event)
    {
        $event->getRequest()->attributes->set('_request_type', $event->getRequestType());
    }
}