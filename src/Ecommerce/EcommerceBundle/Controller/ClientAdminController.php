<?php
/**
 * Created by PhpStorm.
 * User: l.fahimi
 * Date: 29/01/2018
 * Time: 13:49
 */

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Entity\Client;
use Ecommerce\EcommerceBundle\Form\ClientType;

class ClientAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EcommerceBundle:Client')->findAll();

        $clients = $this->get('knp_paginator')->paginate($entities,$this->get('request')->query->get('page', 1),15);

        return $this->render('EcommerceBundle:Administration:Client/index.html.twig', array(
            'clients' => $clients,
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Client();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminClient_show', array('id' => $entity->getId())));
        }


        return $this->render('EcommerceBundle:Administration:Client/newClient.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function createCreateForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('adminClient_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function newMediaAction()
    {
        $entity = new Client();
        $form   = $this->createCreateForm($entity);

        return $this->render('EcommerceBundle:Administration:Client/newClient.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function showMediaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }



        return $this->render('EcommerceBundle:Administration:Client/showClient.html.twig', array(
            'entity'      => $entity ));
    }
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EcommerceBundle:Administration:Client/edit.html.twig', array(
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
    private function createEditForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('adminClient_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('EcommerceBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adminClient', array('id' => $id)));
        }

        return $this->render('EcommerceBundle:Administration:Client/edit.html.twig', array(
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
            $entity = $em->getRepository('EcommerceBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminClient'));
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
            ->setAction($this->generateUrl('adminClient_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }



}