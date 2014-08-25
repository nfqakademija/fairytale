<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ActionInterface
{
    /**
     * Performs the action
     *
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request);
}
