<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Service\Run;

use Torine\WorkflowBundle\Model\Task\Run;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class Factory
{
    /**
     * The DAO used to manipulate runs
     * @var \Torine\WorkflowBundle\Service\Run\DaoInterface
     */
    protected $runDao;
    
    /**
     * The DAO used to manipulate workflows
     * @var \Torine\WorkflowBundle\Service\Workflow\DaoInterface
     */
    protected $workflowDao;
    
    /**
     * Constructor
     * 
     * @param \Torine\WorkflowBundle\Service\Run\DaoInterface $runDao
     * @param \Torine\WorkflowBundle\Service\Workflow\DaoInterface $workflowDao
     */
    function __construct($runDao, $workflowDao)
    {
        $this->runDao = $runDao;
        $this->workflowDao = $workflowDao;
    }

    /**
     * Creates a new run
     * 
     * @param string $workflowId
     * @return \Torine\WorkflowBundle\Model\Task\Run
     */
    public function createRun($workflowId)
    {
        $workflow = $this->workflowDao->get($workflowId);
        if (!isset($workflow)) {
            throw new \Exception("can't find workflow with id '$workflowId'");
        }
        $run = new Run($workflowId, $workflowId . "-" . date_format(new \DateTime(), "YmdHisu"), $this->runDao);
        foreach($workflow->getTasks() as $task) {
            $run->addTask(clone $task);
        }
        
        return $run;
    }

    /**
     * Get a run from the datastore
     * 
     * @param string $runId
     * @return \Torine\WorkflowBundle\Model\Task\Run
     */
    public function getRun($runId)
    {
        return $this->runDao->get($runId);
    }
}