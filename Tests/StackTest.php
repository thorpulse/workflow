<?php

/*
 * This file is part of the Torine Workflow package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Tests;

class StackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function stackIsEmpty()
    {
        assertThat(array(), is(emptyArray()));
    }
}
