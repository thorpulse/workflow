<?php

namespace Torine\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Torine\WorkflowBundle\Model\Utils\Databag;

class WorkflowController extends Controller
{
    const MODE_SYNCHRONE  = "synchrone";
    const MODE_ASYNCHRONE = "asynchrone";
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $parameters = $request->query->all();
        $workflowId = $request->get("workflowId");
        $run = $this->getRunFactory()->createRun($workflowId);
        $run->save();
        
        fsockopen($parts['host'], 
          isset($parts['port'])?$parts['port']:80, 
          $errno, $errstr, 30);
        
        
        
        if ($request->query->get("mode", self::MODE_ASYNCHRONE) === self::MODE_ASYNCHRONE) {
            $runId = $this->runAsynchrone($workflowId, $parameters);
        }
        else {
            $runId = $this->runSynchrone($workflowId, $parameters);
        }
        return new Response(json_encode(["location" => $this->generateUrl(RunController::ROUTE_GET, ["runId" => $run->getId()], true)]));
    }
    
    public function runSynchrone($workflowId, $parameters)
    {
        $run = $this->getRunFactory()->createRun($workflowId);
        $run->run(new Databag($parameters));
        return $run->getId();
    }
    
    public function runAsynchrone($workflowId, $parameters)
    {
        
        
        $url = $this->generateUrl(RunController::ROUTE_POST, array_merge($parameters, ["runId" => $runId]), true);
        return $run->getId();
    }
    
    public function backgroundPost($url){
  $parts=parse_url($url);
 
  $fp = fsockopen($parts['host'], 
          isset($parts['port'])?$parts['port']:80, 
          $errno, $errstr, 30);
           
  if (!$fp) {
      return false;
  } else {
      $out = "POST ".$parts['path']." HTTP/1.1\r\n";
      $out.= "Host: ".$parts['host']."\r\n";
      $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
      $out.= "Content-Length: ".strlen($parts['query'])."\r\n";
      $out.= "Connection: Close\r\n\r\n";
      if (isset($parts['query'])) $out .= $parts['query'];
   
      fwrite($fp, $out);
      fclose($fp);
      return true;
  }
}
    
    /**
     * 
     * @return \Torine\WorkflowBundle\Service\Run\Factory
     */
    protected function getRunFactory()
    {
        return $this->get("torine.workflow.run.factory");
    }
}
