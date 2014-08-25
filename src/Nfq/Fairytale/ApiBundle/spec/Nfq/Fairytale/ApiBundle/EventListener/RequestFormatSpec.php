<?php

namespace spec\Nfq\Fairytale\ApiBundle\EventListener;

use Nfq\Fairytale\ApiBundle\EventListener\RequestFormat;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @mixin RequestFormat
 */
class RequestFormatSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Nfq\Fairytale\ApiBundle\EventListener\RequestFormat');
    }

    function it_should_set_format_from_headers(FilterControllerEvent $event, Request $request, ParameterBag $headers)
    {
        $format = uniqid();
        $mime = 'mime/' . $format;

        $headers->get('Accept')->willReturn($mime);
        $request->headers = $headers;
        $request->getFormat($mime)->willReturn($format);
        $request->setRequestFormat($format)->shouldBeCalled();

        $event->getRequest()->willReturn($request);

        $this->onKernelController($event);
    }
}
