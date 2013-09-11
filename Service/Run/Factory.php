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
     * @var \Torine\WorkflowBundle\Service\DAO\RunDAOInterface
     */
    protected $dao;
    
    /**
     * Constructor
     * 
     * @param \Torine\WorkflowBundle\Service\DAO\RunDAOInterface $dao
     */
    function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * Creates a new run
     * 
     * @param string $id
     * @return \Torine\WorkflowBundle\Model\Task\Run
     */
    public function createRun($id)
    {
        return new Run($id, $this->dao);
    }
}