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

/**
 * You can inject GuzzleClientProvider that will log requests, handle cache, ... using symfony EventDispatcher.
 * See Guzzle documentation for specific details.
 *
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
interface GuzzleClientProvider
{

    /**
     * @return \Guzzle\Service\Client
     */
    public function getClient($baseUrl = null, $config = null);
}
