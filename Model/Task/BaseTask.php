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

use Torine\WorkflowBundle\Model\Utils\Databag;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
abstract class BaseTask implements Task
{
    /**
     * Task name
     * @var string 
     */
    protected $name;
    
    /**
     * The current state of this task
     * @var string 
     */
    protected $state;
    
    /**
     * The textual representation of the result of this task 
     * @var string
     */
    protected $resultMessage;
    
    /**
     * Constructor.
     */
    function __construct()
    {
        $this->state = self::NOT_STARTED;
        $this->resultMessage = "no error message";
        $this->name = get_class($this);
    }
    
    /**
     * @param string $message
     * 
     * @return false
     */
    public function fail($message)
    {
        $this->resultMessage = $message;
        
        return false;
    }
    
    /**
     * Set the state of this task
     * 
     * @param string $state
     * @return \Torine\WorkflowBundle\Model\Task\BaseTask
     */
    public function setState($state)
    {
        $this->state = $state;
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set the task name.
     * 
     * @param string $name
     * @return \Torine\WorkflowBundle\Model\Task\BaseTask
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getResultMessage()
    {
        return $this->resultMessage;
    }
    
    /**
     * {@inheritDoc}
     */
    public function run($inputParameters)
    {
        $this->setState(self::WORKING);
        try {
            $this->setState($this->doRun($inputParameters) ? self::SUCCESS : self::ERROR);
        } catch (\Exception $ex) {
            $this->setState(self::ERROR);
            $this->fail("Exception is : '" . $ex->getMessage() . "'");
        }
        
        return $this->state === self::SUCCESS;
    }
    
    /**
     * @param \Torine\WorkflowBundle\Model\Utils\Databag $inputParameters A Databag containing input parameters
     * @return boolean True in case of success, false otherwise
     */
    public abstract function doRun($inputParameters);

}