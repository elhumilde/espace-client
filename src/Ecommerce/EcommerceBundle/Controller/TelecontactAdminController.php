<?php
/**
 * Created by PhpStorm.
 * User: l.fahimi
 * Date: 06/02/2018
 * Time: 14:53
 */

namespace Ecommerce\EcommerceBundle\Controller;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Entity\Telecontact;
use Ecommerce\EcommerceBundle\Form\TelecontactType;
class TelecontactAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EcommerceBundle:Telecontact')->findAll();

        return $this->render('EcommerceBundle:Administration:Telecontact/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Telecontact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminTelecontact', array('id' => $entity->getId())));
        }

        return $this->render('EcommerceBundle:Administration:Telecontact/newTelecontact.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function createCreateForm(Telecontact $entity)
    {
        $form = $this->createForm(new TelecontactType(), $entity, array(
            'action' => $this->generateUrl('adminTelecontact_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function newTelecontactAction()
    {
        $entity = new Telecontact();
        $form   = $this->createCreateForm($entity);

        return $this->render('EcommerceBundle:Administration:Telecontact/newTelecontact.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function showTelecontactAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:Telecontact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telecontact entity.');
        }



        return $this->render('EcommerceBundle:Administration:Telecontact/showTelecontact.html.twig', array(
            'entity'      => $entity ));
    }
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:Telecontact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telecontact entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EcommerceBundle:Administration:Telecontact/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Produits entity.
     *
     * @param Telecontact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Telecontact $entity)
    {
        $form = $this->createForm(new TelecontactType(), $entity, array(
            'action' => $this->generateUrl('adminTelecontact_update', array('id' => $entity->getId())),
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

        $entity = $em->getRepository('EcommerceBundle:Telecontact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telecontact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adminTelecontact', array('id' => $id)));
        }

        return $this->render('EcommerceBundle:Administration:Telecontact/edit.html.twig', array(
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
            $entity = $em->getRepository('EcommerceBundle:Telecontact')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Telecontact entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminTelecontact'));
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
            ->setAction($this->generateUrl('adminTelecontact_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }

}