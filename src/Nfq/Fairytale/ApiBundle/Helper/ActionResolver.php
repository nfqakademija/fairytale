<?php

namespace Nfq\Fairytale\ApiBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class ActionResolver
{
    /**
     * @param Request $request
     * @return string
     */
    public function resolve(Request $request)
    {
        switch (true) {
            case ($request->getMethod() == 'POST'):
                return 'CREATE';
            case ($request->getMethod() == 'GET'):
                return 'READ';
            case ($request->getMethod() == 'PUT'):
                return 'UPDATE';
            case ($request->getMethod() == 'DELETE'):
                return 'DELETE';
            default:
                return null;
        }
    }
}
