<?php

namespace Nfq\Fairytale\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class RequestFormat
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($mime = $request->headers->get('Accept')) {
            if ($format = $request->getFormat($mime)) {
                $request->setRequestFormat($format);
            }
        }
    }
}
