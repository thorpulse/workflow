<?php

/*
 * This file is part of the Torine package.
 *
 * (c) Rémi Alvado <remi.alvado@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Torine\WorkflowBundle\Service\Run;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class DiskDao implements DaoInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function save($run)
    {
        $content = $this->serializer->serialize($run, "json");
        $filename = $this->directory . "/" . $run->getId();
        try {
            $this->filesystem->dumpFile($filename, $content);
            //readline("press any key to continue...");
        } catch (\Exception $e) {
            $this->logger->error("Can't save run with id '" . $run->getId() . "' in '$filename'. Error was : " . $e->getMessage());
            return false;
        }
        return true;
    }   
    
    /**
     * {@inheritDoc}
     */
    public function get($runId) {
        $filename = $this->directory . "/" . $runId;
        if (!$this->filesystem->exists($filename)) {
            $this->logger->error("Can't read run with id '" . $runId . "' from '$filename'. Error was : File does not exist.");
            return null;
        }
        $content = file_get_contents($filename);
        try {
            return $this->serializer->deserialize($content, "Torine\WorkflowBundle\Model\Task\Run", "json");
        } catch (\Exception $e) {
            $this->logger->error("Can't deserialize run with id '" . $runId . "' from '$filename'. Error was : " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete($runId) {
        $filename = $this->directory . "/" . $runId;
        if (!$this->filesystem->exists($filename)) {
            $this->logger->warning("Can't delete run with id '" . $runId . "' from '$filename'. Error was : File does not exist.");
            return false;
        }
        $this->filesystem->remove($filename);
        return true;
    }

    public function __construct($directory, $serializer, $logger)
    {
        $this->directory  = $directory;
        $this->filesystem = new Filesystem();
        $this->serializer = $serializer;
        $this->logger     = $logger;
        if (!$this->filesystem->exists($directory)) {
            throw new \Exception("'$directory' does not exist. It is supposed to be used to store runs. Can't continue without it...");
        }        
    }

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    public $filesystem;
    
    /**
     * @var string
     */
    public $directory;
    
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    public $serializer;
    
    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $logger;
}