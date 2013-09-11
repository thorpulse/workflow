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
class EchoTask extends BaseTask
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
        echo "Name : $this->name\n";
        foreach($inputParameters as $key => $value) {
            echo "  -> $key : '$value'\n";
        }
        echo "#########################################\n";
        $inputParameters["task.count"] += 1;
        $inputParameters["task.list"] .= " " . $this->name;
        $inputParameters["last.run.time"] = microtime(true);
        
        return true;
    }
}