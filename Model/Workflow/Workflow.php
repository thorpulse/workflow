<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Model\Workflow;

use JMS\Serializer\Annotation as Ser;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 * @Ser\XmlRoot("workflow")
 */
class Workflow
{
    /**
     * The workflow id
     * 
     * @var string
     * @Ser\Type("string")
     */
    protected $id;
    
    /**
     * An array of tasks belonging to this workflow
     * 
     * @var array<\Torine\WorkflowBundle\Model\Task\Task>
     * @Ser\Type("array<Torine\WorkflowBundle\Model\Task\Task>")
     * @Ser\XmlList(inline = true, entry = "task")
     */
    protected $tasks;
    
    /**
     * An array of required parameters for this workflow
     * @var array<Torine\WorkflowBundle\Model\Workflow\Parameter>
     * @Ser\Type("array<Torine\WorkflowBundle\Model\Workflow\Parameter>")
     * @Ser\XmlList(inline = true, entry = "parameter")
     */
    protected $requiredParameters;
    
    /**
     * @param string $id
     * @param array<\Torine\WorkflowBundle\Model\Task\Task> $tasks
     * @param array<\Torine\WorkflowBundle\Model\Workflow\Parameter> $requiredParameters
     */
    function __construct($id, $tasks = array(), $requiredParameters = array())
    {
        $this->id = $id;
        $this->tasks = $tasks;
        $this->requiredParameters = $requiredParameters;
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return array<\Torine\WorkflowBundle\Model\Task\Task>
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @return array<\Torine\WorkflowBundle\Model\Workflow\Parameter>
     */
    public function getRequiredParameters()
    {
        return $this->requiredParameters;
    }

    /**
     * @return array<\Torine\WorkflowBundle\Model\Workflow\Workflow>
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return array<\Torine\WorkflowBundle\Model\Workflow\Workflow>
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
        return $this;
    }

    /**
     * @return array<\Torine\WorkflowBundle\Model\Workflow\Workflow>
     */
    public function setRequiredParameters($requiredParameters)
    {
        $this->requiredParameters = $requiredParameters;
        return $this;
    }
}