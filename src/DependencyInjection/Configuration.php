<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(NetoOpenmetricsExtension::CONFIG_ROOT_KEY);
        $rootNode = $this->getRootNode($treeBuilder, NetoOpenmetricsExtension::CONFIG_ROOT_KEY);

        $rootNode
            ->children()
                ->scalarNode('namespace')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('ignored_routes')
                    ->prototype('scalar')->end()
                    ->defaultValue([ 'neto_openmetrics' ])
                ->end()
            ->end();

        return $treeBuilder;
    }

    private function getRootNode(TreeBuilder $treeBuilder, $name)
    {
        // BC layer for symfony/config 4.1 and older
        if (!\method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->root($name);
        }

        return $treeBuilder->getRootNode();
    }
}
