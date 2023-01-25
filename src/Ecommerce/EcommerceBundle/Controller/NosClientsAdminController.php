<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\NosClients;
use Ecommerce\EcommerceBundle\Form\NosClientsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class NosClientsAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EcommerceBundle:NosClients')->findAll();

        $nosclients = $this->get('knp_paginator')->paginate($entities,$this->get('request')->query->get('page', 1),15);

        return $this->render('EcommerceBundle:Administration:NosClients/layout/index.html.twig', array(
            'nosclients' => $nosclients,
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new NosClients();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminNosClients', array('id' => $entity->getId())));
        }


        return $this->render('EcommerceBundle:Administration:NosClients/layout/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function createCreateForm(NosClients $entity)
    {
        $form = $this->createForm(new NosClientsType(), $entity, array(
            'action' => $this->generateUrl('adminNosClients_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function newAction()
    {
        $entity = new NosClients();
        $form   = $this->createCreateForm($entity);

        return $this->render('EcommerceBundle:Administration:NosClients/layout/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:NosClients')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NosClients entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EcommerceBundle:Administration:NosClients/layout/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Produits entity.
     *
     * @param Produits $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(NosClients $entity)
    {
        $form = $this->createForm(new NosClientsType(), $entity, array(
            'action' => $this->generateUrl('adminNosClients_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Produits entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:NosClients')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NosClients entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adminNosClients', array('id' => $id)));
        }

        return $this->render('EcommerceBundle:Administration:NosClients/layout/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Produits entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EcommerceBundle:NosClients')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NosClients entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminNosClients'));
    }

    /**
     * Creates a form to delete a Produits entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminNosClients_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }



}