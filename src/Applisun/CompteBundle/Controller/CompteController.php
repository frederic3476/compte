<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Applisun\CompteBundle\Entity\Compte;
use Applisun\CompteBundle\Form\CompteType;
use Applisun\CompteBundle\Form\OperationType;
use Applisun\CompteBundle\Entity\Operation;
use Applisun\CompteBundle\Form\Model\OperationModel;
/**
 * Compte controller.
 *
 */
class CompteController extends Controller
{

    /**
     * Lists all Compte entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $compteCourant = $em->getRepository('ApplisunCompteBundle:Compte')->findBy(array('user' => $this->getUser(), 'type' => 'courant'));
        $compteEpargne = $em->getRepository('ApplisunCompteBundle:Compte')->getCompteByUserNotType(array('user' => $this->getUser(), 'type' => 'courant'));
        
        return $this->render('ApplisunCompteBundle:Compte:index.html.twig', array(
            'comptesCourant' => $compteCourant,
            'comptesEpargne' => $compteEpargne,
        ));
    }
    
    /**
     * Lists of Compte by type.
     *
     */
    public function typeAction($type)
    {
        $em = $this->getDoctrine()->getManager();        
        $entities = $em->getRepository('ApplisunCompteBundle:Compte')->findBy(array('user' => $this->getUser(), 'type' => $type));
        
        return $this->render('ApplisunCompteBundle:Compte:index.html.twig', array(
            'comptesCourant' => $entities,
        ));
    }
    
    /**
     * Creates a new Compte entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Compte();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $entity->setUser($user);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('compte_show', array('id' => $entity->getId(), 'year' => date('Y'))));
        }

        return $this->render('ApplisunCompteBundle:Compte:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Compte entity.
     *
     * @param Compte $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Compte $entity)
    {
        $form = $this->createForm(new CompteType(), $entity, array(
            'action' => $this->generateUrl('compte_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Compte entity.
     *
     */
    public function newAction()
    {
        $entity = new Compte();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplisunCompteBundle:Compte:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Compte entity.
     *
     */
    public function showAction($id, $year, $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }        
        
        $compteManager =  $this->get('applisun_compte.compte_manager');
        
        $operation = $compteManager->getNewOperation($entity);
        $model = new OperationModel($operation);
                       
        //create form to add operation
        $form = $this->createForm(new \Applisun\CompteBundle\Form\OperationFormType($entity, $this->container->get('security.context')), $model, array(
            'action' => $this->generateUrl('compte_show', array('id' => $entity->getId(), 'year' => date('Y'))),
            'method' => 'POST'));
        
        $form->handleRequest($request);
         if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager(); 
            $compteManager->saveOperation($operation, $entity);
            $model->getUpdatedOperation();
            //cas du virement interne appel listener ?
            if ($form->get('debitType')->getData() == $this->container->getParameter('virementInterne'))
            {
                $compteDest = $form->get('compte')->getData();
                $operationDest = $compteManager->getNewOperation($compteDest);
                $typeOperation = $em->getRepository('ApplisunCompteBundle:TypeOperation')->findByType($this->container->getParameter('virementExterne'));
                if (!$typeOperation[0]) {
                    throw $this->createNotFoundException('Unable to find typeOperation entity.');
                }
                $operationDest->setLibelle('Virement depuis le compte '.$entity->getBanque()->getName().' n°'.$entity->getNumero());
                $operationDest->SetTypeOperation($typeOperation[0]);
                $operationDest->SetMontant($form->get('montant')->getData());
                $compteDest->addOperation($operationDest);
                $operation->SetLibelle('Virement vers le compte '.$form->get('compte')->getData()->getBanque()->getName().' n°'.$form->get('compte')->getData()->getNumero());
            }
                        
            //persist compte entity
            // thank you listener CompteListener !!!
            
            $em->flush();  
            $request->getSession()->getFlashBag()->add('notice', 'Opération bien enregistrée.');

            return $this->redirect($this->generateUrl('compte_show', array('id' => $entity->getId(), 'year' => date('Y'))));
          }
        //first operation of current compte
        $firstOpe = $em->getRepository('ApplisunCompteBundle:Operation')->getOperationFirstByCompte($id);        
        
        return $this->render('ApplisunCompteBundle:Compte:show.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
            'page' => $page,
            'year' => $year,
            'firstYear' => ($firstOpe?date('Y', $firstOpe->getCreatedAt()->getTimeStamp()):date('Y', time())),
        ));
    }

    /**
     * Displays a form to edit an existing Compte entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('ApplisunCompteBundle:Compte:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Compte entity.
    *
    * @param Compte $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Compte $entity)
    {
        $form = $this->createForm(new CompteType(), $entity, array(
            'action' => $this->generateUrl('compte_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Mettre à jour'));

        return $form;
    }
    /**
     * Edits an existing Compte entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compte', array('id' => $id)));
        }

        return $this->render('ApplisunCompteBundle:Compte:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Compte entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplisunCompteBundle:Compte')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Impossible de trouver Compte entity.');
            }

            $em->remove($entity);
            $em->flush();        
            $request->getSession()->getFlashBag()->add('notice', 'Compte supprimé avec succès.');      
        return $this->redirect($this->generateUrl('compte'));
    }

}
