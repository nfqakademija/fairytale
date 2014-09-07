<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\ActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\ApiBundle\Helper\RequestValidator;
use Nfq\Fairytale\ApiBundle\Helper\ResponseFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\RoleInterface;

class ApiController extends Controller implements ApiControllerInterface
{
    /**
     * @param Request         $request
     * @param string          $resource
     * @param ActionInterface $action
     * @param mixed|null      $payload
     * @param string|null     $identifier
     * @return \Nfq\Fairytale\ApiBundle\Actions\ActionResult
     */
    public function customAction(Request $request, $resource, ActionInterface $action, $payload, $identifier = null)
    {
        $roles = $this->stringifyRoles(
            $this->get('security.role_hierarchy')->getReachableRoles(
                $this->get('security.context')->getToken()->getRoles()
            )
        );

        /** @var RequestValidator $requestValidator */
        $requestValidator = $this->container->get('nfq_fairytale.api.security.request_validator');
        $requestValidator->validate($resource, $action->getName(), $payload, $roles);

        /** @var DataSourceFactory $factory */
        $factory = $this->container->get('nfq_fairytale.data_source.factory');

        switch (true) {
            case ($action instanceof CollectionActionInterface):
                $result = $action->execute($request, $factory->create($resource));
                break;
            case ($action instanceof InstanceActionInterface):
                $result = $action->execute($request, $factory->create($resource), $identifier);
                break;
            default:
                throw new \InvalidArgumentException(
                    'Action must implement CollectionActionInterface or InstanceActionInterface'
                );
        }

        /** @var ResponseFilter $responseFilter */
        $responseFilter = $this->container->get('nfq_fairytale.api.security.response_filter');

        return new Response(
            $this->renderView(
                'NfqFairytaleApiBundle:Api:custom.json.twig',
                ['data' => $responseFilter->filterResponse($result, $resource, $action->getName(), $roles)]
            ),
            $result->getStatusCode()
        );
    }

    /**
     * @param RoleInterface[] $roles
     * @return string[]
     */
    private function stringifyRoles($roles)
    {
        return array_map(
            function (RoleInterface $role) {
                return $role->getRole();
            },
            $roles
        );
    }
}
