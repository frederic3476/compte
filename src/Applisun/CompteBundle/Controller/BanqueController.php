<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Applisun\CompteBundle\Entity\Banque;
use Applisun\CompteBundle\Form\BanqueType;

/**
 * Banque controller.
 *
 */
class BanqueController extends Controller
{

    /**
     * Lists all Banque entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplisunCompteBundle:Banque')->findAll();

        return $this->render('ApplisunCompteBundle:Banque:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Banque entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Banque();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('banque'));
        }

        return $this->render('ApplisunCompteBundle:Banque:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Banque entity.
     *
     * @param Banque $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Banque $entity)
    {
        $form = $this->createForm(new BanqueType(), $entity, array(
            'action' => $this->generateUrl('banque_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Banque entity.
     *
     */
    public function newAction()
    {
        $entity = new Banque();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplisunCompteBundle:Banque:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Banque entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Banque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banque entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplisunCompteBundle:Banque:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Banque entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Banque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banque entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('ApplisunCompteBundle:Banque:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Banque entity.
    *
    * @param Banque $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Banque $entity)
    {
        $form = $this->createForm(new BanqueType(), $entity, array(
            'action' => $this->generateUrl('banque_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Banque entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Banque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banque entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('banque'));
        }

        return $this->render('ApplisunCompteBundle:Banque:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Banque entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplisunCompteBundle:Banque')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Impossible de trouver Banque entity.');
            }

            $em->remove($entity);
            $em->flush();        
            $request->getSession()->getFlashBag()->add('success', 'Banque supprimée avec succès.');      
        return $this->redirect($this->generateUrl('banque'));
    }

    /**
     * Creates a form to delete a Banque entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('banque_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
