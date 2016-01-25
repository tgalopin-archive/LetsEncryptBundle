<?php

echo 'Updating letsencrypt and virtual environment dependencies';

for ($i = 0; $i < 5; $i++) {
    usleep(300);
    echo '.';
}

echo <<<EOF

Requesting root privileges to run with virtualenv: /root/.local/share/letsencrypt/bin/letsencrypt certonly --manual --text --manual-public-ip-logging-ok --agree-tos --staging --renew-by-default --email galopintitouan@gmail.com -d example.org
Make sure your web server displays the following content at
http://example.org/.well-known/acme-challenge/Afl0IFJxeE97D7hRM_BiUZ5_782q4ZVuSuhD9fZKi10 before continuing:

Afl0IFJxeE97D7hRM_BiUZ5_782q4ZVuSuhD9fZKi10.9VLNC343KgujDQZ8GhGEZYi8iIN4atTDMeKv9RnecWU

If you don't have HTTP server configured, you can run the following
command on the target server (as root):

mkdir -p /tmp/letsencrypt/public_html/.well-known/acme-challenge
cd /tmp/letsencrypt/public_html
printf "%s" Afl0IFJxeE97D7hRM_BiUZ5_782q4ZVuSuhD9fZKi10.9VLNC343KgujDQZ8GhGEZYi8iIN4atTDMeKv9RnecWU > .well-known/acme-challenge/Afl0IFJxeE97D7hRM_BiUZ5_782q4ZVuSuhD9fZKi10
# run only once per server:
(command -v python2 || command -v python2.7 || command -v python2.6) -c \
"import BaseHTTPServer, SimpleHTTPServer; \
s = BaseHTTPServer.HTTPServer(('', 80), SimpleHTTPServer.SimpleHTTPRequestHandler); \
s.serve_forever()"
Press ENTER to continue
EOF;

$handle = fopen('php://stdin', 'r');
$line = fgets($handle);
