TgaLetsEncryptBundle
====================

> This bundle is currently in development.

[![Build Status](https://travis-ci.org/tgalopin/LetsEncryptBundle.svg?branch=master)](https://travis-ci.org/tgalopin/LetsEncryptBundle)
[![Build status](https://ci.appveyor.com/api/projects/status/b8hp4tr5nqp9hyt7?svg=true)](https://ci.appveyor.com/project/tgalopin/letsencryptbundle)

TgaLetsEncryptBundle provides a simple yet fully configurable Symfony command to renew automatically
the HTTPS certificates of your website. It has never been so easy to use HTTPS in your Symfony application!

TgaLetsEncryptBundle is also able to warn you (by mail for instance) when the renewal of a certificate failed.

Installation
------------

Using Composer:

```
composer require tga/letsencrypt-bundle:1.0.x-dev
```

Enable the bundle in your `AppKernel`:

``` php
<?php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            
            new Tga\LetsEncryptBundle\TgaLetsEncryptBundle(),

            // ...
        ];
        
        // ...
    }
    
    // ...
}
```

Usage
-----

Simply configure the bundle to your needs:

``` yaml
# app/config/config.yml

tga_lets_encrypt:

    # Path to the Let's Encrypt executable (usually your letsencrypt-auto binary)
    letsencrypt:          ~ # Required

    # Recovery email used by Let's Encrypt for registration and recovery contact
    recovery_email:       ~ # Required

    # Logs directory (if not specified, the application logs directory will be used)
    logs_directory:       null

    # Domains to get certificates for (this application should response to these domains)
    domains:              [] # Required

    # Monitorings to be warned if an error occured during the renewal of one of your certificates
    monitoring:

        # Email monitoring
        email:
            enabled:              false

            # If an error occured, emails where a warning should be sent
            to:                   []
```

For a classical setup, you can clone the Let's Encrypt repository in your `bin` directory
(`cd /path/to/your/project/bin && git clone https://github.com/letsencrypt/letsencrypt`)
and use it as script. You probably also want to be warned by email if an error occurs.

``` yaml
# app/config/config.yml

tga_lets_encrypt:
    letsencrypt: "%kernel.root_dir%/../bin/letsencrypt/letsencrypt-auto"
    recovery_email: "youremail@example.org"
    domains:
        - example1.org
        - www.example1.org
        - example2.org
        - www.example2.org
    monitoring:
        email:
            enabled: true
            to: [ "youremail@example.org" ]
```

You can test your parameters work properly by running:

```
$ sudo -H php app/console tga:letsencrypt
# Or for Symfony 3
$ sudo -H php bin/console tga:letsencrypt
```

> You need to run this script as root as Let's Encrypt need root privileges.

And you can check you are properly warned if an error occurs by running:

```
$ sudo -H php app/console tga:letsencrypt --emulate-failure
# Or for Symfony 3
$ sudo -H php bin/console tga:letsencrypt --emulate-failure
```

If everything work as expected, you can configure a CRON task to run this command
every 2 days for instance:

```
$ sudo su
$ crontab -e

0 0 */2 * * sudo -H php /path/to/your/project/app/console tga:letsencrypt
# Or for Symfony 3
0 0 */2 * * sudo -H php /path/to/your/project/bin/console tga:letsencrypt
```
