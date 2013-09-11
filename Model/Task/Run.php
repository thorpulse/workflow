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
class Run extends BaseTask
{
    const NOT_STARTED = "not-started";
    const WORKING     = "working";
    const SUCCESS     = "success";
    const ERROR       = "error";
    
    /**
     * @Ser\Type("string") 
     */
    protected $id;
    
    /**
     * An array of tasks belonging to this run
     * 
     * @var array<\Torine\WorkflowBundle\Model\Task\Task>
     * @Ser\Type("array<Torine\WorkflowBundle\Model\Task\Task>")
     * @Ser\XmlList(inline = true, entry = "parameter")
     */
    protected $tasks;
    
    /**
     * A DAO service used to save the run at each step of its process
     * 
     * @var \Torine\WorkflowBundle\Service\DAO\RunDAOInterface
     * @Ser\Excludes()
     */
    protected $dao;
    
    /**
     * Constructor.
     */
    function __construct($id, $dao)
    {
        parent::__construct();
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
     * {@inheritDoc}
     */
    public function run($parameters)
    {
        $parameters = isset($parameters) ? $parameters : new Databag();
        $this->start($parameters);
        foreach($this->tasks as $task) {
            try {
                $this->startTask($task, $parameters);
                if (!$task->run($parameters)) {
                    $this->fail("Run fails since task " . $task->getName() . " has failed with the following message : \n" . $task->getResultMessage());
                    $this->stopTask($task, $parameters);
                    return $this->stop(self::ERROR, $parameters);
                }
                $this->stopTask($task, $parameters);
            } catch (Exception $ex) {
                $this->fail("Run fails since task " . $task->getName() . " has failed with the following exception : \n" . $ex->getMessage());
                $this->stopTask($task, $parameters);
                return $this->stop(self::ERROR, $parameters);
            }
        }
        return $this->stop(self::SUCCESS, $parameters);
    }
    
    /**
     * @param \Torine\WorkflowBundle\Model\Task\Task $task
     */
    public function startTask($task, $parameters)
    {
        $name = $task->getName();
        $parameters["__metrics.tasks.current"]     = $name;
        $parameters["__metrics.tasks.$name.start"] = microtime(true);
        $this->save();
    }
    
    /**
     * @param \Torine\WorkflowBundle\Model\Task\Task $task
     */
    public function stopTask($task, $parameters)
    {
        $name = $task->getName();
        $parameters["__metrics.tasks.$name.stop"]     = microtime(true);
        $parameters["__metrics.tasks.$name.duration"] = $parameters["__metrics.tasks.$name.stop"] - $parameters["__metrics.tasks.$name.start"];
        $this->save();
    }
    
    public function start($parameters)
    {
        $this->setState(self::WORKING);
        $parameters["__metrics.timer.start"]  = microtime(true);
        $parameters["__metrics.tasks.number"] = count($this->tasks);
        $this->save();
    }
    
    public function stop($state, $parameters)
    {
        $this->setState($state);
        $parameters["__metrics.timer.stop"]     = microtime(true);
        $parameters["__metrics.timer.duration"] = $parameters["__metrics.timer.stop"] - $parameters["__metrics.timer.start"];
        $this->save();
        return $state === self::SUCCESS;
    }
    
    public function save()
    {
        $this->dao->save($this);
    }
    
    public function doRun($inputParameters) {}

}