<?php

/**
 * This file is part of the TgaLetsEncryptBundle library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tga\LetsEncryptBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle Let's Encrypt well-known check and renew the certificate.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class RenewalController extends Controller
{
    public function wellKnownAction()
    {
        /** @todo */
        return new Response('OK');
    }
}
