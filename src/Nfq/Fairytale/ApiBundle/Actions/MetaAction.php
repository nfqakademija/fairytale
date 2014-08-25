<?php

namespace Nfq\Fairytale\ApiBundle\Actions;

use Symfony\Component\HttpFoundation\Request;

class MetaAction implements ActionInterface{

    /**
     * @inheritdoc
     */
    public function execute(Request $request)
    {
        return ['foo'];
    }
}
