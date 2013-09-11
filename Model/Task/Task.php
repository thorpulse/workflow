<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Model\Task;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
interface Task
{
    const NOT_STARTED = "not-started";
    const WORKING     = "working";
    const SUCCESS     = "success";
    const ERROR       = "error";
    
    /**
     * @param \Torine\WorkflowBundle\Model\Utils\Databag $inputParameters A Databag containing input parameters
     * @return boolean True in case of success, false otherwise
     */
    public function run($inputParameters);
    
    /**
     * @return string Task name
     */
    public function getName();
    
    /**
     * @return string The textual representation of this Task result
     */
    public function getResultMessage();
}