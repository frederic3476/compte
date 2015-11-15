<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use Symfony\Component\Validator\ConstraintViolationList;

use Applisun\CompteBundle\Entity\Message;

class ApiController extends Controller {
    
         
        
    
    /**
     * Get the validation errors
     *
     * @param ConstraintViolationList $errors Validator error list
     *
     * @return View
     */
    protected function getErrorsView(ConstraintViolationList $errors)
    {
        $msgs = array();
        $errorIterator = $errors->getIterator();
        foreach ($errorIterator as $validationError) {
            $msg = $validationError->getMessage();
            $params = $validationError->getMessageParameters();
            $msgs[$validationError->getPropertyPath()][] = $this->get('translator')->trans($msg, $params, 'validators');
        }
        $view = View::create($msgs);
        $view->setStatusCode(400);
        return $view;
    }
    
    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of departement with number of playground",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     */
     
    public function getCategoryListAction()
    {
        $cats = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Category')->findAll();
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($cats, "json");
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of message",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="catId", nullable=false, strict=true, description="id category")
     */
     
    public function getMessageListAction(ParamFetcher $paramFetcher)
    {
        $messages = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Message')->getMessageByCategory($paramFetcher->get('catId'));
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($messages, "json");
        
        return new response($data, 200);
    }
    
    
}

