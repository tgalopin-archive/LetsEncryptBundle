<?php

/**
 * This file is part of the TgaLetsEncryptBundle library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

echo 'Updating letsencrypt and virtual environment dependencies';

for ($i = 0; $i < 5; $i++) {
    usleep(300000);
    echo '.';
}

echo <<<EOF

Requesting root privileges to run with virtualenv: sudo /home/tgalopin/.local/share/letsencrypt/bin/letsencrypt certonly --manual --manual-public-ip-logging-ok --agree-tos --renew-by-default --email youremail@example.org --logs-dir /home/tgalopin/devenv/www/letsencrypt/bundle/tests/Command/../Fixtures/tmp --domain example.org --staging
Make sure your web server displays the following content at
http://example.org/.well-known/acme-challenge/SBBEo6f2YSglhV1d3wYJKDIhDtNtS76XTWVH_OPXmiI before continuing:

SBBEo6f2YSglhV1d3wYJKDIhDtNtS76XTWVH_OPXmiI.9VLNC343KgujDQZ8GhGEZYi8iIN4atTDMeKv9RnecWU

If you don't have HTTP server configured, you can run the following
command on the target server (as root):

mkdir -p /tmp/letsencrypt/public_html/.well-known/acme-challenge
cd /tmp/letsencrypt/public_html
printf "%s" SBBEo6f2YSglhV1d3wYJKDIhDtNtS76XTWVH_OPXmiI.9VLNC343KgujDQZ8GhGEZYi8iIN4atTDMeKv9RnecWU > .well-known/acme-challenge/SBBEo6f2YSglhV1d3wYJKDIhDtNtS76XTWVH_OPXmiI
# run only once per server:
$(command -v python2 || command -v python2.7 || command -v python2.6) -c \
"import BaseHTTPServer, SimpleHTTPServer; \
s = BaseHTTPServer.HTTPServer(('', 80), SimpleHTTPServer.SimpleHTTPRequestHandler); \
s.serve_forever()"
Press ENTER to continue
EOF;

fgets(fopen('php://stdin', 'r'));

echo "OK\n";



