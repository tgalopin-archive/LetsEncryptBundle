<?php

/**
 * This file is part of the TgaLetsEncryptBundle library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tga\LetsEncryptBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Tga\LetsEncryptBundle\Command\TgaLetsencryptCommand;

class TgaLetsencryptCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $commandTester = $this->execute();

        echo $commandTester->getDisplay();
        exit;
    }

    /**
     * Execute the TgaLetsEncrypt command with specific parameters.
     *
     * @param array $parameters
     * @return CommandTester
     */
    public function execute($parameters = [])
    {
        $parameters = array_merge([
            'letsencrypt' => 'php ' . __DIR__ . '/../Fixtures/mockscript-ok.php',
            'recovery_email' => 'youremail@example.org',
            'domains' => ['example.org'],
            'logs_directory' => __DIR__ . '/../Fixtures/tmp',
            'monitoring.email.enabled' => false,
            'monitoring.email.to' => [],
        ], $parameters);

        $container = new Container();

        foreach ($parameters as $name => $value) {
            $container->setParameter('tga_lets_encrypt.' . $name, $value);
        }

        $command = new TgaLetsencryptCommand();
        $command->setContainer($container);

        $application = new Application();
        $application->add($command);
        $application->setAutoExit(false);

        $command = $application->find('tga:letsencrypt');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        return $commandTester;
    }
}
