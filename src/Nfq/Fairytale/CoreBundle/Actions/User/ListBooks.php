<?php

namespace Nfq\Fairytale\CoreBundle\Actions\User;

use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\Actions\Instance\BaseInstanceAction;
use Nfq\Fairytale\ApiBundle\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class ListBooks extends BaseInstanceAction
{

    const NAME = 'user.books';

    /** @var  SecurityContext */
    protected $securityContext;

    /**
     * Performs the action
     *
     * @param Request             $request
     * @param DataSourceInterface $resource
     * @param string              $identifier
     * @return ActionResult
     */
    public function execute(Request $request, DataSourceInterface $resource, $identifier)
    {
        $uid = $this->securityContext->getToken()->getUser()->getId();

        return ActionResult::collection(200, $resource->query(['user' => $uid]));
    }

    /**
     * @param SecurityContext $securityContext
     */
    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }
}
