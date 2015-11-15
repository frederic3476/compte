<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Applisun\CompteBundle\Entity\TypeOperation;
use Applisun\CompteBundle\Form\TypeOperationType;

/**
 * TypeOperation controller.
 *
 */
class TypeOperationController extends Controller
{

    /**
     * Lists all TypeOperation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplisunCompteBundle:TypeOperation')->findAll();

        return $this->render('ApplisunCompteBundle:TypeOperation:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TypeOperation entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TypeOperation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('typeoperation_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplisunCompteBundle:TypeOperation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TypeOperation entity.
     *
     * @param TypeOperation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TypeOperation $entity)
    {
        $form = $this->createForm(new TypeOperationType(), $entity, array(
            'action' => $this->generateUrl('typeoperation_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TypeOperation entity.
     *
     */
    public function newAction()
    {
        $entity = new TypeOperation();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplisunCompteBundle:TypeOperation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TypeOperation entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:TypeOperation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeOperation entity.');
        }

        return $this->render('ApplisunCompteBundle:TypeOperation:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing TypeOperation entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:TypeOperation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeOperation entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('ApplisunCompteBundle:TypeOperation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TypeOperation entity.
    *
    * @param TypeOperation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeOperation $entity)
    {
        $form = $this->createForm(new TypeOperationType(), $entity, array(
            'action' => $this->generateUrl('typeoperation_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TypeOperation entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:TypeOperation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeOperation entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('typeoperation', array('id' => $id)));
        }

        return $this->render('ApplisunCompteBundle:TypeOperation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a TypeOperation entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplisunCompteBundle:TypeOperation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TypeOperation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('typeoperation'));
    }

    /**
     * Creates a form to delete a TypeOperation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeoperation_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
