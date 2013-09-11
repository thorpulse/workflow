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
interface RunDAOInterface
{
    /**
     * Save a run into the datastore
     * 
     * @param \Torine\WorkflowBundle\Model\Task\Run $run The run to be saved
     */
    public function save($run);
    
    /**
     * Get a run from the datastore
     * 
     * @param string $runId
     * @return \Torine\WorkflowBundle\Model\Task\Run
     */
    public function get($runId);
    
    /**
     * Delete a run from the datastore
     * 
     * @param string $runId
     * @return boolean true if the run has been deleted, false otherwise
     */
    public function delete($runId);
}