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

use JMS\Serializer\Annotation as Ser;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 * @Ser\Discriminator(field = "type", map = {
 *     "run" : "Torine\WorkflowBundle\Model\Task\Run",
 *     "echo": "Torine\WorkflowBundle\Model\Task\EchoTask",
 *     "http": "Torine\WorkflowBundle\Model\Task\HttpTask",
 *     "wait": "Torine\WorkflowBundle\Model\Task\WaitTask"
 * })
 */
class Task
{
    const NOT_STARTED = "not-started";
    const WORKING     = "working";
    const SUCCESS     = "success";
    const ERROR       = "error";
    
    /**
     * Task name
     * @var string
     * @Ser\Type("string")
     */
    protected $name;
    
    /**
     * Task id
     * @var string
     * @Ser\Type("string")
     */
    protected $id;
    
    /**
     * An array of parameters
     * 
     * @Ser\Type("array<string, string>")
     */
    protected $params;
    
    /**
     * The current state of this task
     * @var string
     * @Ser\Type("string")
     */
    protected $state;
    
    /**
     * The textual representation of the result of this task 
     * @var string
     * @Ser\Type("string")
     */
    protected $resultMessage;
    
    /**
     * Constructor.
     */
    function __construct($name = null)
    {
        $this->state = self::NOT_STARTED;
        $this->name = !empty($name) ? $name : get_class($this);
        $this->id = $this->name . "-" . date_format(new \DateTime(), "YmdHisu");
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
     * Check the tash current state
     * 
     * @param string $state
     * @return boolean
     */
    public function is($state)
    {
        return $this->state === $state;
    }

    /**
     * @return string Task name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string Task id
     */
    public function getId()
    {
        return $this->id;
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
     * @return string The textual representation of this Task result
     */
    public function getResultMessage()
    {
        return $this->resultMessage;
    }
    
    /**
     * @param \Torine\WorkflowBundle\Model\Utils\Databag $inputParameters A Databag containing input parameters
     * @return boolean True in case of success, false otherwise
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
    public function doRun($inputParameters){}

}