<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Service\DAO;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class RiakRunDAO
{
    
    /**
     * {@inheritDoc}
     */
    public function save($run)
    {
        return $this->runBucket->put(array($run->getId() => $run));
    }   
    
    /**
     * {@inheritDoc}
     */
    public function get($runId) {
        return $this->runBucket->uniq($runId)->getContent();
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete($runId) {
        return $this->runBucket->delete($runId);
    }

    public function __construct($riakCluster)
    {
        $this->runBucket = $riakCluster->getBucket("workflow_run");
    }

    /**
     * @var \Kbrw\RiakBundle\Model\Bucket\Bucket
     */
    public $runBucket;
}