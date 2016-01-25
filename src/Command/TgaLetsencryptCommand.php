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

class TgaLetsencryptCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tga:letsencrypt')
            ->setDescription('This task should be ran as a CRON to automatically renew your website HTTPS certificate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandParts = [];

        if ($input->getOption('option')) {
            // Script: ./letsencrypt-auto

            // certonly
            // --manual-public-ip-logging-ok
            // --agree-tos
            // --renew-by-default
            // -d example.org
            // --logs-dir

            // sudo -H ./letsencrypt-auto certonly --manual-public-ip-logging-ok --agree-tos --staging --renew-by-default --email galopintitouan@gmail.com -d example.org
        }

    }

}
