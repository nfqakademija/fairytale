<?php

namespace Nfq\Fairytale\ApiBundle\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ResponseBuilder
{
    /** @var  Serializer */
    protected $serializer;

    /**
     * @param Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        list($content, $code) = $event->getControllerResult();

        $format = $request->getRequestFormat('json');

        $response = new Response(
            $this->serializer->serialize($content, $format),
            $code,
            [
                'Content-type' => $request->getMimeType($format)
            ]
        );

        $event->setResponse($response);
    }
}
