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

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * TgaLetsEncryptBundle dependency injection extension.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class TgaLetsEncryptExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (0 === count($config['domains'])) {
            $exception = new InvalidConfigurationException(
                'You must provide at least one domain in configuration on path "tga_lets_encrypt.domains".'
            );

            $exception->setPath('tga_lets_encrypt.domains');

            throw $exception;
        }

        $container->setParameter('tga_lets_encrypt.script', $config['script']);
        $container->setParameter('tga_lets_encrypt.recovery_email', $config['recovery_email']);
        $container->setParameter('tga_lets_encrypt.domains', $config['domains']);

        // Logs
        $container->setParameter('tga_lets_encrypt.logs_directory', $container->getParameter('kernel.logs_dir'));

        if ($config['logs_directory']) {
            $container->setParameter('tga_lets_encrypt.logs_directory', $config['logs_directory']);
        }

        // Email monitoring
        $container->setParameter('tga_lets_encrypt.monitoring.email.enabled', $config['monitoring']['email']['enabled']);
        $container->setParameter('tga_lets_encrypt.monitoring.email.to', $config['monitoring']['email']['to']);
    }
}
