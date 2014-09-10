<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use Nfq\Fairytale\ApiBundle\Actions\ActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\ApiBundle\Helper\OwnershipResolver;
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
     * @return Response
     */
    public function customAction(Request $request, $resource, ActionInterface $action, $payload, $identifier = null)
    {
        $token = $this->get('security.context')->getToken();
        $roles = $this->stringifyRoles(
            $this->get('security.role_hierarchy')->getReachableRoles(
                $token->getRoles()
            )
        );

        /** @var RequestValidator $requestValidator */
        $requestValidator = $this->container->get('nfq_fairytale.api.security.request_validator');
        $requestValidator->validate($resource, $action->getName(), $payload, $roles);

        /** @var DataSourceFactory $factory */
        $factory = $this->container->get('nfq_fairytale.data_source.factory');

        $roles = $this->handleObjectOwnership($resource, $identifier, $factory, $token, $roles);
        $result = $this->execute($request, $resource, $action, $identifier, $factory);

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

    /**
     * @param Request         $request
     * @param                 $resource
     * @param ActionInterface $action
     * @param                 $identifier
     * @param                 $factory
     * @return \Nfq\Fairytale\ApiBundle\Actions\ActionResult
     */
    private function execute(Request $request, $resource, ActionInterface $action, $identifier, $factory)
    {
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
        return $result;
    }

    /**
     * @param $resource
     * @param $identifier
     * @param $factory
     * @param $token
     * @param $roles
     * @return array
     */
    private function handleObjectOwnership($resource, $identifier, $factory, $token, $roles)
    {
        if ($identifier) {
            /** @var OwnershipResolver $ownershipResolver */
            $ownershipResolver = $this->container->get('nfq_fairytale.api.security.ownership_resolver');

            $instance = $factory->create($resource)->read($identifier);
            $isOwner = $ownershipResolver->resolve($token->getUser()->getId(), $instance);
            if ($isOwner) {
                $roles[] = 'ROLE_OWNER';
                return $roles;
            }
            return $roles;
        }
        return $roles;
    }
}
