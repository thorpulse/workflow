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
 * @Ser\XmlRoot("parameter")
 */
class Parameter
{
    /**
     * The parameter name
     * @var string
     * @Ser\Type("string")
     */
    protected $name;
    
    /**
     * The parameter value
     * @var string
     * @Ser\Type("string")
     */
    protected $value;
    
    /**
     * The parameter description
     * @var string
     * @Ser\Type("string")
     */
    protected $description;
    
    function __construct($name, $value = null, $description = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \Torine\WorkflowBundle\Model\Workflow\Parameter
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \Torine\WorkflowBundle\Model\Workflow\Parameter
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \Torine\WorkflowBundle\Model\Workflow\Parameter
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}