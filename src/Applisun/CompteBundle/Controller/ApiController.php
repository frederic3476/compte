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
use Applisun\CompteBundle\Entity\Compte;
use Applisun\CompteBundle\Entity\Operation;

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
    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of compte by user id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="userId", nullable=false, strict=true, description="id user")
     * @QueryParam(name="year", nullable=false, strict=true, description="year")
     */
    public function getCompteListAction(ParamFetcher $paramFetcher)
    {
        $user = $this->getDoctrine()->getRepository('ApplisunCompteBundle:User')->find($paramFetcher->get('userId'));
        
        if (!$user){
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $comptes = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Compte')->getCompteByUserNotTypeAndYear(array('user' => $user, 'type' => '', 'year' => $paramFetcher->get('year')));
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($comptes, "json");
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of operation by date and compte Id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="month", nullable=false, strict=true, description="month")
     * @QueryParam(name="year", nullable=false, strict=true, description="year")
     * @QueryParam(name="compteId", nullable=false, strict=true, description="id compte")
     */
    public function getOperationListAction(ParamFetcher $paramFetcher)
    {
        $operations = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Operation')->getOperationByDateAndCompte($this->container, 
                                                                                                                        $paramFetcher->get('month'), 
                                                                                                                        $paramFetcher->get('year'), 
                                                                                                                        'all', 
                                                                                                                        $paramFetcher->get('compteId'));
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($operations, "json");
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of evolution by year and compte Id groupe by month",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="year", nullable=false, strict=true, description="year")
     * @QueryParam(name="compteId", nullable=false, strict=true, description="id compte")
     */
    public function getEvolutionListAction(ParamFetcher $paramFetcher)
    {
        $evolutions = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Evolution')->getEvolutionByCompteAndYear($paramFetcher->get('compteId'),
                                                                                                                        $paramFetcher->get('year')
                                                                                                                        );
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($evolutions, "json");
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of evolution by year and compte Id groupe by month",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="year", nullable=false, strict=true, description="year")
     * @QueryParam(name="compteId", nullable=false, strict=true, description="id compte")
     */
    public function getEvolutionByDayAction(ParamFetcher $paramFetcher)
    {
        $evolutions = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Evolution')->getEvolutionByCompteGroupByDayAndMonthAndYear($paramFetcher->get('compteId'),
                                                                                                                        $paramFetcher->get('year')
                                                                                                                        );
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($evolutions, "json");
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of operation by year and compte Id groupe by type",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="month", nullable=false, strict=true, description="month")
     * @QueryParam(name="year", nullable=false, strict=true, description="year")
     * @QueryParam(name="compteId", nullable=false, strict=true, description="id compte")
     */
    public function getOperationByTypeAction(ParamFetcher $paramFetcher)
    {
        $operations = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Operation')->getOperationListForplot($paramFetcher->get('month'),
                                                                                                                        $paramFetcher->get('year'),
                                                                                                                        $paramFetcher->get('compteId')        
                                                                                                                        );
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($operations, "json");
        
        return new response($data, 200);
    }        
    
    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "list of typeoperation",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     */
    public function getTypeOperationAction(ParamFetcher $paramFetcher)
    {
        $tOperations = $this->getDoctrine()->getRepository('ApplisunCompteBundle:TypeOperation')->findAll();
                
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($tOperations, "json");
        
        return new response($data, 200);
    }
    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get operation",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="id", nullable=false, strict=true, description="id")
     */
    public function getOperationAction(ParamFetcher $paramFetcher)
    {
        $view = View::create();
        $operation = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Operation')->find($paramFetcher->get('id'));
        
        if (!$operation){
            $view->setData('Opération non trouvé');
            $view->setStatusCode(400);
            return $view;
        }
        $view->setData($operation);
        $view->setStatusCode(200);
        return $view;
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete operation",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="id", nullable=false, strict=true, description="id")
     */
    public function deleteOperationAction(ParamFetcher $paramFetcher)
    {
        $view = View::create();
        $operation = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Operation')->find($paramFetcher->get('id'));
        
        if (!$operation){
            $view->setData('Opération non trouvé');
            $view->setStatusCode(400);
            return $view;
        }
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($operation);
        $em->flush();
        $view->setData('Opération supprimée');
        $view->setStatusCode(200);
        return $view;
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Modify operation",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="id", nullable=false, strict=true, description="id")
     * @RequestParam(name="libelle", nullable=false, strict=true, description="libelle")
     * @RequestParam(name="montant", nullable=false, strict=true, description="montant")
     * @RequestParam(name="typeOperation", nullable=false, strict=true, description="operation typ")
     */
    public function putOperationAction(ParamFetcher $paramFetcher)
    {
        $view = View::create();
        $operation = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Operation')->find($paramFetcher->get('id'));
        
        if (!$operation){
            $view->setData('Opération non trouvée');
            $view->setStatusCode(400);
            return $view;
        }
        $em = $this->getDoctrine()->getManager();
        
        $em->flush();
        $view->setData('Opération modifiée');
        $view->setStatusCode(200);
        return $view;
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "New operation",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="compteid", nullable=false, strict=true, description="id compte")
     * @RequestParam(name="libelle", nullable=false, strict=true, description="libelle")
     * @RequestParam(name="montant", nullable=false, strict=true, description="montant")
     * @RequestParam(name="typeOperation", nullable=false, strict=true, description="operation typ")
     */
    public function postOperationAction(ParamFetcher $paramFetcher)
    {
        $view = View::create();
        
        $ope = new Operation();
        
        $em = $this->getDoctrine()->getManager();
        $compte = $this->getDoctrine()->getRepository('ApplisunCompteBundle:Compte')->find($paramFetcher->get('compteid'));
        if (!$compte){
            $view->setData('Compte non trouvée');
            $view->setStatusCode(400);
            return $view;
        }
        
        
        
        $em->flush();
        $view->setData('Opération modifiée');
        $view->setStatusCode(200);
        return $view;
    }
    
}

