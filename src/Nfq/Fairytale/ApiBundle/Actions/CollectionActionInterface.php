<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CollectionActionInterface
{
    /**
     * Performs the action
     *
     * @param Request $request
     * @param string  $resource
     * @return Response
     */
    public function execute(Request $request, $resource);
}
