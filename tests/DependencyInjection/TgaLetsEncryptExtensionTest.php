<?php

/**
 * This file is part of the TgaLetsEncryptBundle library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tga\LetsEncryptBundle\Tests\Controller;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use Tga\LetsEncryptBundle\DependencyInjection\TgaLetsEncryptExtension;

class TgaLetsEncryptExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenScriptNotSet()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        unset($config['letsencrypt']);
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenScriptEmpty()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['letsencrypt'] = '';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenRecoveryEmailNotSet()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        unset($config['recovery_email']);
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenRecoveryEmailEmpty()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['recovery_email'] = '';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenDomainsNotSet()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        unset($config['domains']);
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenDomainsEmpty()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['domains'] = [];
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenScriptDoesNotExists()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['script'] = 'invalid';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenLogsDirDoesNotExists()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['logs_directory'] = 'invalid';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenRecoveryEmailNotValid()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['recovery_email'] = 'invalid';
        $loader->load([ $config ], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFailsWhenMonitoringEmailNotValid()
    {
        $loader = new TgaLetsEncryptExtension();
        $config = $this->getEmptyConfig();
        $config['monitoring']['email']['enabled'] = true;
        $config['monitoring']['email']['to'] = ['invalid'];
        $loader->load([ $config ], new ContainerBuilder());
    }

    private function getEmptyConfig()
    {
        $script = __DIR__ . '/../Fixtures/mockscript-ok.php';

        $yaml = <<<EOF
letsencrypt: "$script"
recovery_email: tgalopin@example.org
domains:
    - example.org
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    private function getFullConfig()
    {
        $script = __DIR__ . '/../Fixtures/mockscript.sh';

        $yaml = <<<EOF
letsencrypt: "$script"
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
