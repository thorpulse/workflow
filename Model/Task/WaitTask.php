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
class WaitTask extends Task
{
    
    function __construct($name)
    {
        parent::__construct();
        $this->setName($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function doRun($inputParameters)
    {
        echo "Waiting for : " . $this->params["duration"] . "\n";
        sleep($this->params["duration"]);
        
        return true;
    }
}