<?php

/**
 * This file is part of the TgaLetsEncryptBundle library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tga\LetsEncryptBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * TgaLetsEncryptBundle configuration rules.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tga_lets_encrypt');

        $rootNode
            ->children()
                ->scalarNode('letsencrypt')
                    ->info('Path to the Let\'s Encrypt executable (usually your letsencrypt-auto binary)')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('recovery_email')
                    ->info('Recovery email used by Let\'s Encrypt for registration and recovery contact')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('logs_directory')
                    ->info('Logs directory (if not specified, the application logs directory will be used)')
                    ->defaultNull()
                ->end()
                ->arrayNode('domains')
                    ->info('Domains to get certificates for (this application should response to these domains)')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->prototype('scalar')->cannotBeEmpty()->end()
                ->end()
                ->arrayNode('monitoring')
                    ->info('Monitorings to be warned if an error occured during the renewal of one of your certificates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('email')
                            ->info('Email monitoring')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('enabled')->defaultFalse()->end()
                                ->arrayNode('to')
                                    ->info('If an error occured, emails where a warning should be sent')
                                    ->defaultValue([])
                                    ->prototype('scalar')->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
