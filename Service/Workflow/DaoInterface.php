<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Service\Workflow;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
interface DaoInterface
{
    /**
     * Save a workflow into the datastore
     * 
     * @param \Torine\WorkflowBundle\Model\Workflow\Workflow $workflow The workflow to be saved
     */
    public function save($workflow);
    
    /**
     * Get a workflow from the datastore
     * 
     * @param string $workflowId
     * @return \Torine\WorkflowBundle\Model\Workflow\Workflow
     */
    public function get($workflowId);
    
    /**
     * Delete a workflow from the datastore
     * 
     * @param string $workflowId
     * @return boolean true if the workflow has been deleted, false otherwise
     */
    public function delete($workflowId);
}