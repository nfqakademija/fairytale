<?php

namespace Nfq\Fairytale\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NfqFairytaleApiExtension extends Extension
{
    public function getAlias()
    {
        return 'nfq_fairytale_api';
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('listeners.yml');
        $loader->load('actions.yml');

        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('nfq_fairytale_api.config.mapping', $config['mapping']);
        $container->setParameter('nfq_fairytale_api.config.default_index_size', $config['index_size']);

        /* ACL */
        $acl = Yaml::parse(
            file_get_contents($container->getParameter('kernel.root_dir') . '/config/' . $config['acl'])
        );
        $container->setParameter('nfq_fairytale_api.config.security.acl', $acl);

        $factory = $container->getDefinition('nfq_fairytale.data_source.factory');

        switch ($config['data']['type']) {
            case 'orm':
                $dataSource = $container->getDefinition('nfq_fairytale.data_source.orm');
                $dataSource->addMethodCall('setManagerName', [$config['data']['source']]);
                break;
            default:
                throw new InvalidConfigurationException("Unsupported type %s in rest_api.data.type");
        }

        $factory->addMethodCall('setDataSource', [$dataSource]);

        $container->setDefinition('nfq_fairytale.data_source.factory', $factory);
    }
}
