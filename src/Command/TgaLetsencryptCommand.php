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

        foreach ($domains as $domain) {
            $this->letsencrypt($script, $domain, $recoveryMail, $logDir);
        }


    }

    /**
     * @param string $script
     * @param string $domain
     * @param string $recoveryMail
     * @param string $logsDir
     */
    private function letsencrypt($script, $domain, $recoveryMail, $logsDir)
    {
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


        // Build command
        $command = implode(' ', $command);

        var_dump($command);
        exit;



        // Script: ./letsencrypt-auto

        // certonly
        // --manual-public-ip-logging-ok
        // --agree-tos
        // --renew-by-default
        // -d example.org
        // --logs-dir

        // sudo -H ./letsencrypt-auto certonly --manual-public-ip-logging-ok --agree-tos --staging --renew-by-default --email galopintitouan@gmail.com -d example.org

    }

    private function handleError($message, $domain = null)
    {
        $this->output->writeln('<error>'. $message .'</error>');
    }

    private function sendMonitoringSummary()
    {
    }

}
