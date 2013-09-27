<?php

namespace Torine\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RunController extends Controller
{
    const ROUTE_GET  = "torine.workflow.run.get";
    const ROUTE_POST = "torine.workflow.run.post";
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Request $request)
    {
        $runId = $request->get("runId");
        $run = $this->getRunFactory()
                    ->getRun($runId);
        if (!isset($run)) {
            throw new NotFoundHttpException("Can't find run with id '$runId'");
        }
        return new Response($this->getSerializer()->serialize($run, "json"));
    }
    
    /**
     * 
     * @return \Torine\WorkflowBundle\Service\Run\Factory
     */
    protected function getRunFactory()
    {
        return $this->get("torine.workflow.run.factory");
    }
    
    /**
     * 
     * @return \JMS\Serializer\SerializerInterface
     */
    protected function getSerializer()
    {
        return $this->get("serializer");
    }
}
