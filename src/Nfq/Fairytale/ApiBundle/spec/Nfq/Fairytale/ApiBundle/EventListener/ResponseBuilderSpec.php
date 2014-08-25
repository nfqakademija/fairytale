<?php

namespace spec\Nfq\Fairytale\ApiBundle\EventListener;

use JMS\Serializer\Serializer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ResponseBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\EventListener\ResponseBuilder');
    }

    function it_should_generate_response(
        GetResponseForControllerResultEvent $event,
        Request $request,
        Serializer $serializer
    ) {
        $format = uniqid();
        $controllerResult = ['foo' => uniqid()];
        $content = uniqid();
        $mime = 'application/' . $format;
        $status = 200;

        $request->getRequestFormat('json')->willReturn($format);
        $request->getMimeType($format)->willReturn($mime);

        $hasValidateResponse = function (Response $response) use ($content, $mime, $status) {
            return $response->getContent() === $content
            && $response->headers->get('Content-type') === $mime
            && $response->getStatusCode() === $status;
        };

        $event->getRequest()->willReturn($request);
        $event->getControllerResult()->willReturn([$controllerResult, $status]);
        $event->setResponse(Argument::that($hasValidateResponse))->shouldBeCalled();

        $serializer->serialize($controllerResult, $format)->willReturn($content);

        $this->setSerializer($serializer);
        $this->onKernelView($event);
    }
}
