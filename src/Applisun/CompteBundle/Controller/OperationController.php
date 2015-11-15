<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Applisun\CompteBundle\Entity\Operation;
use Applisun\CompteBundle\Form\OperationType;

/**
 * Operation controller.
 *
 */
class OperationController extends Controller
{

    /**
     * Lists all Operation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplisunCompteBundle:Operation')->findAll();

        return $this->render('ApplisunCompteBundle:Operation:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Operation entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Operation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('operation_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplisunCompteBundle:Operation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Operation entity.
     *
     * @param Operation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Operation $entity)
    {
        $form = $this->createForm(new OperationType(), $entity, array(
            'action' => $this->generateUrl('operation_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Operation entity.
     *
     */
    public function newAction()
    {
        $entity = new Operation();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplisunCompteBundle:Operation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Operation entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplisunCompteBundle:Operation:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Operation entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $editForm = $this->createEditForm($entity);
        //$deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplisunCompteBundle:Operation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Operation entity.
    *
    * @param Operation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Operation $entity)
    {
        $form = $this->createForm(new OperationType(), $entity, array(
            'action' => $this->generateUrl('operation_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Mettre à jour'));

        return $form;
    }
    /**
     * Edits an existing Operation entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Operation')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }
        
        //save old data
        $oldTypeOperation = $entity->getTypeOperation();
        $oldMontant = $entity->getMontant();
        $compte = $entity->getCompte();
        
        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {  
            //persist compte entity            
            
            $tempSolde = ($oldTypeOperation->getIsDebit()?$compte->getSolde()+$oldMontant:$compte->getSolde()-$oldMontant);            
            $newSolde=  ($entity->getTypeOperation()->getIsDebit()?$tempSolde-$entity->getMontant():$tempSolde+$entity->getMontant());
            $compte->setSolde($newSolde);
            $em->persist($compte);
            
            //pb compte is null if not set
            $entity->setCompte($compte);
            $em->persist($entity);
            
            $em->flush();
            
            return $this->redirect($this->generateUrl('compte_show', array('id' => $compte->getId(), 'year' => date('Y', time()))));
        }

        return $this->render('ApplisunCompteBundle:Operation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Operation entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplisunCompteBundle:Operation')->find($id);

        if (!$entity) {
           throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $em->remove($entity);
        //persist compte entity 
        // thank you listener
        
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Opération supprimée avec succès.');  
        
        return $this->redirect($this->generateUrl('compte_show', array('id' => $entity->getCompte()->getId(), 'year' => date('Y'))));
    }

    /**
     * Creates a form to delete a Operation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operation_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    public function operationListAction(Request $request, $month, $year, $compteId, $page)
    {        
        $em = $this->getDoctrine()->getManager();
        $operations = $em->getRepository('ApplisunCompteBundle:Operation')->getOperationByDateAndCompte($this->container, $month, $year, $page, $compteId);        
        
        $info = array('id' => $compteId, 'year' => $year, 'page' => $page, 'nb_operation' => count($operations));
        
        $response = $this->render('ApplisunCompteBundle:Operation:operation_list.html.twig', array('operations' => $operations, 'info' => $info));
        
        $response->setPrivate();
        
        if (date('y', time()) == $year && date('m', time()) == $month)
        {
            $response->setMaxAge(600);
        }
        else{
            
        }
        
        return $response;
    }
    
     public function searchAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $this->getRequest()->get('query');
 
        if(!$query) {
            return $this->redirect($this->generateUrl('compte'));
        }
 
        $operations = $em->getRepository('ApplisunCompteBundle:Operation')->getForLuceneQuery($this->container, $query, $page);
        
        $operations_count = $em->getRepository('ApplisunCompteBundle:Operation')->getCountForLuceneQuery($query);        
        
        $pagination = array(
            'page' => $page,
            'route' => 'operation_search',
            'pages_count' => (!is_array($operations_count)?ceil($operations_count / $this->container->getParameter('maxperpage')):1),
            'query' => $query,
            'nbr_operation' => (!is_array($operations_count)?$operations_count:0),
            'route_params' => array()
        );        
 
        return $this->render('ApplisunCompteBundle:Operation:search.html.twig', array('operations' => $operations,
                                                                                        'pagination' => $pagination));
    }
}
