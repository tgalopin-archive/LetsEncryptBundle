<?php

namespace Tga\LetsEncryptBundle\Tests\Controller;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use Tga\LetsEncryptBundle\DependencyInjection\TgaLetsEncryptExtension;

class TgaLetsEncryptExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThrowsExceptionWhenScriptNotSet()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        unset($config['script']);
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThrowsExceptionWhenScriptEmpty()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['script'] = '';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThrowsExceptionWhenRecoveryEmailNotSet()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        unset($config['recovery_email']);
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThrowsExceptionWhenRecoveryEmailEmpty()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['recovery_email'] = '';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThrowsExceptionWhenDomainsNotSet()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        unset($config['domains']);
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThrowsExceptionWhenDomainsEmpty()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['domains'] = [];
        $loader->load([ $config ], new ContainerBuilder());
    }

    private function getEmptyConfig()
    {
        $yaml = <<<EOF
script: "%kernel.root_dir%/../bin/letsencrypt/letsencrypt-auto"
recovery_email: tgalopin@example.org
domains:
    - example.org
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    private function getFullConfig()
    {
        $yaml = <<<EOF
script: "%kernel.root_dir%/../bin/letsencrypt/letsencrypt-auto"
recovery_email: tgalopin@example.org
logs_directory: /var/log/letsencrypt
domains:
    - example.org
monitoring:
    email:
        enabled: true
        to:
            - tgalopin@example.org
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
