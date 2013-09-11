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

use Torine\WorkflowBundle\Model\Utils\Valuable;

/**
 * @author Rémi Alvado <remi.alvado@gmail.com>
 */
class HttpTask extends BaseTask
{
    /**
     * {@inheritDoc}
     */
    public function run($inputParameters)
    {
        
    }
    
    /**
     * The provider used to build Guzzle client
     * 
     * @var \Torine\WorkflowBundle\Service\Guzzle\GuzzleClientProvider
     */
    protected $guzzleClientProvider;

    /**
     * The URI template which should be called
     * 
     * @var string 
     */
    protected $uriTemplate;
    
    /**
     * The method used to perform the call (GET, POST, ...)
     * 
     * @var string 
     */
    protected $method;
    
    /**
     * An array of placeholder. 
     * Each placeholder can be a scalar, an array, a callable or class implementing Valuable
     * 
     * @var array<string, mixed>
     */
    protected $placeholders;
    
    /**
     * Set the guzzle client provider used to execute HTTP requests
     * 
     * @param \Torine\WorkflowBundle\Service\Guzzle\GuzzleClientProvider $guzzleClientProvider
     * @return \Torine\WorkflowBundle\Model\Task\HttpTask
     */
    public function setGuzzleClientProvider($guzzleClientProvider)
    {
        $this->guzzleClientProvider = $guzzleClientProvider;
        
        return $this;
    }
    
    /**
     * Set URI template which should be called
     * 
     * @param string $uriTemplate
     * @return \Torine\WorkflowBundle\Model\Task\HttpTask
     */
    public function setUriTemplate($uriTemplate)
    {
        $this->uriTemplate = $uriTemplate;
        
        return $this;
    }

    /**
     * Set the method used to perform the call (GET, POST, ...)
     * 
     * @param string $method
     * @return \Torine\WorkflowBundle\Model\Task\HttpTask
     */
    public function setMethod($method)
    {
        $this->method = $method;
        
        return $this;
    }

    /**
     * Set placeholders
     * 
     * @param array<string, mixed> $placeholders
     * @return \Torine\WorkflowBundle\Model\Task\HttpTask
     */
    public function setPlaceholders($placeholders)
    {
        $this->placeholders = $placeholders;
        
        return $this;
    }
    
    /**
     * Add a placeholder
     * 
     * @param string $key
     * @param mixed $value
     * @return \Torine\WorkflowBundle\Model\Task\HttpTask
     */
    public function addPlaceholder($key, $value)
    {
        $this->placeholders[$key] = $value;
        
        return $this;
    }
    
    /**
     * Check if placeholders contains a value for this key
     * 
     * @param string $key
     * @return true if it exists, false otherwise
     */
    public function hasPlaceholder($key)
    {
        return array_key_exists($key, $this->placeholders);
    }
    
    /**
     * Get a placeholder by its key.
     * 
     * @param string $key
     * @return null if it does not exists, the actual value otherwise
     */
    public function getPlaceholder($key)
    {
        return $this->hasPlaceholder($key) ? $this->placeholders[$key] : null;
    }
    
    /**
     * Get the placeholder value
     * 
     * @param string $key
     * @return mixed
     */
    public function getPlaceholderValue($key)
    {
        $value = $this->getPlaceholder($key);
        if (is_callable($value)) {
            return call_user_func($value);
        }
        if ($value instanceof Valuable) {
            return $value->getValue();
        }
        
        return $value;
    }

}