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

        $this->validateConfiguration($config);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('tga_lets_encrypt.letsencrypt', $config['letsencrypt']);
        $container->setParameter('tga_lets_encrypt.recovery_email', $config['recovery_email']);
        $container->setParameter('tga_lets_encrypt.domains', $config['domains']);
        $container->setParameter('tga_lets_encrypt.logs_directory', $config['logs_directory']);

        // Email monitoring
        $container->setParameter('tga_lets_encrypt.monitoring.email.enabled', $config['monitoring']['email']['enabled']);
        $container->setParameter('tga_lets_encrypt.monitoring.email.to', $config['monitoring']['email']['to']);
    }

    /**
     * @param array $config
     * @throws InvalidConfigurationException
     */
    private function validateConfiguration($config)
    {
        $exception = null;

        if (0 === count($config['domains'])) {
            $exception = new InvalidConfigurationException(
                'You must provide at least one domain in configuration in "tga_lets_encrypt.domains".'
            );

            $exception->setPath('tga_lets_encrypt.domains');

            throw $exception;
        }

        if ($config['logs_directory'] && ! file_exists($config['logs_directory'])) {
            $exception = new InvalidConfigurationException(
                sprintf(
                    'Logs directory "%s" (configured in "tga_lets_encrypt.logs_directory") does not exist.',
                    $config['logs_directory']
                )
            );

            $exception->setPath('tga_lets_encrypt.logs_directory');

            throw $exception;
        }

        if (! filter_var($config['recovery_email'], FILTER_VALIDATE_EMAIL)) {
            $exception = new InvalidConfigurationException(
                sprintf(
                    'Recovery email "%s" (configured in "tga_lets_encrypt.recovery_email") is not a valid email.',
                    $config['recovery_email']
                )
            );

            $exception->setPath('tga_lets_encrypt.recovery_email');

            throw $exception;
        }

        if ($config['monitoring']['email']['enabled']) {
            foreach ($config['monitoring']['email']['to'] as $toEmail) {
                if (! filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                    $exception = new InvalidConfigurationException(
                        sprintf(
                            'Email monitoring recipient email "%s" (configured in "tga_lets_encrypt.monitoring.email.to") '.
                            'is not a valid email.',
                            $toEmail
                        )
                    );

                    $exception->setPath('tga_lets_encrypt.monitoring.email.to');

                    throw $exception;
                }
            }
        }
    }
}
