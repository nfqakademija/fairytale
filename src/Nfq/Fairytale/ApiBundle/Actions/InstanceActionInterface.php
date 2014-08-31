<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface InstanceActionInterface extends ActionInterface
{
    /**
     * Performs the action
     *
     * @param Request $request
     * @param string  $resource
     * @param string  $identifier
     * @return Response
     */
    public function execute(Request $request, $resource, $identifier);
}
