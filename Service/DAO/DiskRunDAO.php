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

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class DiskRunDAO
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

    public function __construct($directory, $serializer)
    {
        $this->fileSystem = new Filesystem();
        if ($this->fileSystem->exists($directory)) {
            throw new Exception("'$directory' does not exist. It is supposed to be used to store runs. Can't continue without it...");
        }
    }

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    public $fileSystem;
    
    /**
     * @var \
     */
    public $serializer;
}