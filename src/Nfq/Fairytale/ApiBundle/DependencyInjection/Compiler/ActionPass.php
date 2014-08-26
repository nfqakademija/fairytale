<?php

namespace Nfq\Fairytale\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ActionPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $collectionActions = $container->findTaggedServiceIds('nfq_fairytale.api.action.collection');
        $instanceActions = $container->findTaggedServiceIds('nfq_fairytale.api.action.instance');

        $actionManager = $container->getDefinition('nfq_fairytale.action.manager');

        $this->addActions($container, $collectionActions, $actionManager, 'addCollectionAction');
        $this->addActions($container, $instanceActions, $actionManager, 'addInstanceAction');

        $container->setDefinition('nfq_fairytale.action.manager', $actionManager);
    }

    /**
     * @param ContainerBuilder $container
     * @param                  $collectionActions
     * @param  Definition      $actionManager
     * @param                  $method
     */
    private function addActions(ContainerBuilder $container, $collectionActions, $actionManager, $method)
    {
        foreach ($collectionActions as $service => $tags) {
            $tag = current($tags);
            $actionManager->addMethodCall(
                $method,
                [$container->getDefinition($service), $tag['resource'], $tag['action'], $tag['method']]
            );
        }
    }
}
