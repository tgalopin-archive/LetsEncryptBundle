<?php

/**
 * This file is part of the TgaLetsEncryptBundle library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tga\LetsEncryptBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class TgaLetsencryptCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var ContainerInterface
     */
    private $container;


    protected function configure()
    {
        $this
            ->setName('tga:letsencrypt')
            ->setDescription('This task should be ran as a CRON to automatically renew your website HTTPS certificate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->container = $this->getContainer();

        $script = $this->container->getParameter('tga_lets_encrypt.letsencrypt');
        $domains = $this->container->getParameter('tga_lets_encrypt.domains');
        $recoveryMail = $this->container->getParameter('tga_lets_encrypt.recovery_email');
        $logDir = $this->container->getParameter('tga_lets_encrypt.logs_directory');

        $output = '';

        foreach ($domains as $domain) {
            $output .= $this->letsencrypt($script, $domain, $recoveryMail, $logDir) . "\n";
        }

        $this->output->write($output);
    }

    /**
     * @param string $script
     * @param string $domain
     * @param string $recoveryMail
     * @param string $logsDir
     */
    private function letsencrypt($script, $domain, $recoveryMail, $logsDir)
    {
        try {

            $command = [];

            // Script
            $command[] = $script;

            // We will use Symfony for the well-known challenge
            $command[] = 'certonly';
            $command[] = '--manual';

            // Disable interaction
            $command[] = '--manual-public-ip-logging-ok';
            $command[] = '--agree-tos';
            $command[] = '--renew-by-default';

            // Recovery e-mail
            $command[] = '--email';
            $command[] = $recoveryMail;

            // Logs directory
            $command[] = '--logs-dir';
            $command[] = $logsDir;

            // Domain
            $command[] = '--domain';
            $command[] = $domain;

            $process = new Process(implode(' ', $command));

            $stdin = fopen('php://temp', 'w+');
            $process->setInput($stdin);

            $process->start(function ($type, $data) {
                if (strpos($data, '.well-known/acme-challenge') !== false) {
                    preg_match('~\.well-known/acme-challenge/([^ ]+) ~U', $data, $match);
                    var_dump($match);
                }
            });

            $gotCert = false;

            while($process->isRunning()) {
                /*if (! $gotCert && strpos($process->getOutput(), 'Press ENTER to continue') !== false) {
                    $gotCert = true;
                    // ...
                    fwrite($stdin, "\n");
                }*/
            }

        } catch (\Exception $e) {
            $this->handleError($e->getMessage(), $domain);
        }
    }

    private function handleError($message, $domain = null)
    {
        $this->output->writeln('<error>'. $message .'</error>');
    }

    private function sendMonitoringSummary()
    {
    }

}
