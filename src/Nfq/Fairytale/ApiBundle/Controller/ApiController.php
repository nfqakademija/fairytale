<?php

namespace Nfq\Fairytale\ApiBundle\Controller;

use JMS\Serializer\Serializer;
use Nfq\Fairytale\ApiBundle\Actions\ActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\ActionResult;
use Nfq\Fairytale\ApiBundle\Actions\Collection\CollectionActionInterface;
use Nfq\Fairytale\ApiBundle\Actions\Instance\InstanceActionInterface;
use Nfq\Fairytale\ApiBundle\DataSource\Factory\DataSourceFactory;
use Nfq\Fairytale\ApiBundle\Security\PermissionManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Role\RoleInterface;

class ApiController extends Controller implements ApiControllerInterface
{
    /** @var  PermissionManager */
    private $permissions;

    /**
     * @param Request $request
     * @param         $resource
     * @param         $action
     * @param null    $identifier
     * @return \Nfq\Fairytale\ApiBundle\Actions\ActionResult
     */
    public function customAction(Request $request, $resource, ActionInterface $action, $payload, $identifier = null)
    {
        $roles = $this->stringifyRoles(
            $this->get('security.role_hierarchy')->getReachableRoles(
                $this->get('security.context')->getToken()->getRoles()
            )
        );

        /** @var PermissionManager $permissions */
        $this->permissions = $this->get('nfq_fairytale.api.security.permissions');

        if (!$this->permissions->isReadable($resource, $action->getName(), $roles)) {
            throw new AccessDeniedHttpException('You are not allowed to access this resource');
        }

        if ($payload) {
            if (!$this->permissions->isWritable($resource, $action->getName(), $roles)) {
                throw new AccessDeniedHttpException('You are not allowed to access this resource');
            }

            foreach ($payload as $field => $value) {
                if (!$this->permissions->isWritable($resource . ':' . $field, $action->getName(), $roles)) {
                    throw new AccessDeniedHttpException('You are not allowed to access this resource');
                }
            }
        }

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

        return new Response(
            $this->renderView(
                'NfqFairytaleApiBundle:Api:custom.json.twig',
                ['data' => $this->filterResponse($result, $resource, $action->getName(), $roles)]
            ),
            $result->getStatusCode()
        );
    }

    private function filterResponse(ActionResult $result, $resource, $action, $roles)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $serializeAndFilter = function ($item) use ($serializer, $resource, $action, $roles) {
            $data = $serializer->deserialize($serializer->serialize($item, 'json'), 'array', 'json');

            $fields = array_filter(
                array_keys($data),
                function ($field) use ($resource, $action, $roles) {
                    return $this->permissions->isReadable($resource . ':' . $field, $action, $roles);
                }
            );

            return array_intersect_key($data, array_flip($fields));
        };

        switch ($result->getType()) {
            case ActionResult::SIMPLE:
            case ActionResult::INSTANCE:
                $data = $serializeAndFilter($result->getResult());
                break;
            case ActionResult::COLLECTION:
                $data = array_map($serializeAndFilter, $result->getResult());
                break;
            default:
                throw new \InvalidArgumentException('Unsupported ActionResult type ' . $result->getType());
        }

        return $data;
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
