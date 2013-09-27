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
use JMS\Serializer\Annotation as Ser;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 * @Ser\XmlRoot("run")
 */
class Run extends Task
{
    const NOT_STARTED = "not-started";
    const WORKING     = "working";
    const SUCCESS     = "success";
    const ERROR       = "error";
    const ON_HOLD     = "on-hold";
    
    /**
     * An array of tasks belonging to this run
     * 
     * @var array<\Torine\WorkflowBundle\Model\Task\Task>
     * @Ser\Type("array<Torine\WorkflowBundle\Model\Task\Task>")
     * @Ser\XmlList(inline = true, entry = "task")
     */
    protected $tasks;
    
    /**
     * A Databag of parameters
     * 
     * @var \Torine\WorkflowBundle\Model\Utils\Databag 
     * @Ser\Type("Torine\WorkflowBundle\Model\Utils\Databag")
     */
    protected $parameters;
    
    /**
     * A DAO service used to save the run at each step of its process
     * 
     * @var \Torine\WorkflowBundle\Service\DAO\RunDAOInterface
     * @Ser\Exclude()
     */
    protected $dao;
    
    /**
     * A callable used to determine if we should continue to run
     * 
     * @var callable
     * @Ser\Exclude()
     */
    protected $runCondition;
    
    /**
     * Constructor.
     */
    function __construct($name, $id, $dao)
    {
        parent::__construct($name);
        $this->id = $id;
        $this->dao = $dao;
        $this->tasks = array();
    }
    
    /**
     * Add a new task at the end of this run
     * 
     * @param \Torine\WorkflowBundle\Model\Task\Task $task
     * @return \Torine\WorkflowBundle\Model\Task\Run
     */
    public function addTask(\Torine\WorkflowBundle\Model\Task\Task $task)
    {
        $this->tasks[] = $task;
        
        return $this;
    }
    
    /**
     * @return boolean whatever the run should continue
     */
    public function shouldRun()
    {
        return !is_callable($this->runCondition) || call_user_func($this->runCondition);
    }
    
    /**
     * Define the condition that need to be fulfilled to continue running
     * 
     * @param callable $runCondition
     * @return \Torine\WorkflowBundle\Model\Task\Run
     */
    public function runWhile($runCondition)
    {
        $this->runCondition = $runCondition;
        
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function run($parameters)
    {
        $this->parameters = isset($parameters) ? $parameters : new Databag();
        $this->start($parameters);
        foreach($this->tasks as $task) {
            if (!$this->shouldRun()) {
                var_dump($this->runCondition);
                echo "should not run";
                return $this->stop(self::ON_HOLD);
            }
            if ($task->is(self::SUCCESS)) {
                echo "task already succeed";
                continue;
            }
            try {
                $this->startTask($task);
                if (!$task->run($this->parameters)) {
                    $this->fail("Run fails since task " . $task->getName() . " has failed with the following message : \n" . $task->getResultMessage());
                    $this->stopTask($task);
                    return $this->stop(self::ERROR);
                }
                $this->stopTask($task);
            } catch (Exception $ex) {
                $this->fail("Run fails since task " . $task->getName() . " has failed with the following exception : \n" . $ex->getMessage());
                $this->stopTask($task);
                return $this->stop(self::ERROR);
            }
        }
        return $this->stop(self::SUCCESS);
    }
    
    /**
     * @param \Torine\WorkflowBundle\Model\Task\Task $task
     */
    public function startTask($task)
    {
        $name = $task->getName();
        $this->parameters["__metrics.tasks.current"]     = $name;
        $this->parameters["__metrics.tasks.$name.start"] = microtime(true);
        $this->save();
    }
    
    /**
     * @param \Torine\WorkflowBundle\Model\Task\Task $task
     */
    public function stopTask($task)
    {
        $name = $task->getName();
        $this->parameters["__metrics.tasks.$name.stop"]     = microtime(true);
        $this->parameters["__metrics.tasks.$name.duration"] = $this->parameters["__metrics.tasks.$name.stop"] - $this->parameters["__metrics.tasks.$name.start"];
        $this->save();
    }
    
    public function start()
    {
        $this->setState(self::WORKING);
        $this->parameters["__metrics.timer.start"]  = microtime(true);
        $this->parameters["__metrics.tasks.number"] = count($this->tasks);
        $this->save();
    }
    
    public function stop($state)
    {
        $this->setState($state);
        $this->parameters["__metrics.timer.stop"]     = microtime(true);
        $this->parameters["__metrics.timer.duration"] = $this->parameters["__metrics.timer.stop"] - $this->parameters["__metrics.timer.start"];
        $this->save();
        return $state === self::SUCCESS;
    }
    
    public function save()
    {
        $this->dao->save($this);
    }

}