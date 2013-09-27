<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Torine\WorkflowBundle\Model\Utils\Databag;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class WorkflowCommand extends ContainerAwareCommand
{
    protected $running = true;
    
    protected function configure()
    {
        $this
            ->setName('workflow:start')
            ->setDescription('Start a daemonized workflow')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        pcntl_signal(SIGTERM, function () {$this->running = false;});
        pcntl_signal(SIGINT,  function () {$this->running = false;});
        $this->getRunFactory()
             ->createRun("test.echos")
             ->runWhile(array($this, "isRunning"))
             ->run(new Databag([
                       "task.count" => 0, 
                       "task.list"  => ""
                   ]));
    }
    
    public function isRunning()
    {
        pcntl_signal_dispatch();
        return $this->running;
    }
    
    /**
     * 
     * @return \Torine\WorkflowBundle\Service\Run\Factory
     */
    protected function getRunFactory()
    {
        return $this->getContainer()->get("torine.workflow.run.factory");
    }
}