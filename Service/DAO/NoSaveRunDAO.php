<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Service\DAO;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class NoSaveRunDAO
{
    
    /**
     * {@inheritDoc}
     */
    public function save($run)
    {
        return true;
    }   
    
    /**
     * {@inheritDoc}
     */
    public function get($runId) {
        return null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete($runId) {
        return true;
    }
}