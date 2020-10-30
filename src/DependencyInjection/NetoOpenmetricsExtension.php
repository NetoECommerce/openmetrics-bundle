<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NetoOpenmetricsExtension extends Extension
{
    const CONFIG_ROOT_KEY = 'neto_openmetrics';

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(self::CONFIG_ROOT_KEY . '.namespace', $config['namespace']);
        $container->setParameter(self::CONFIG_ROOT_KEY . '.ignored_routes', $config['ignored_routes']);
    }
}
