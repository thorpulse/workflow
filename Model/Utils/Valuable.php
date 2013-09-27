<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Model\Utils;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
interface Valuable
{
    /**
     * @return a scalar or an array
     */
    public function getValue();
}