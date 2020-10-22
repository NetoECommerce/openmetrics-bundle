<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NetoOpenmetricsBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('neto_openmetrics.namespace', $config['namespace']);
        $container->setParameter('neto_openmetrics.type', $config['type']);
        $container->setParameter('neto_openmetrics.ignored_routes', $config['ignored_routes']);

        if ('redis' === $config['type']) {
            $container->setParameter('neto_openmetrics.redis', $config['redis']);
        }
    }
}
