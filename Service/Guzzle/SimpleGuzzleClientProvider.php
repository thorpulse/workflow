<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Service\Guzzle;

use Guzzle\Service\Client;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class SimpleGuzzleClientProvider implements GuzzleClientProvider
{
    /**
     * {@inheritDoc}
     */
    public function getClient($baseUrl = null, $config = null)
    {
        return new Client($baseUrl, $config);
    }
}
