<?php
/**
 * Created by PhpStorm.
 * User: l.fahimi
 * Date: 29/01/2018
 * Time: 13:49
 */

namespace Utilisateurs\UtilisateursBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Utilisateurs\UtilisateursBundle\Entity\Utilisateurs;
use Utilisateurs\UtilisateursBundle\Form\UtilisateurFormType;
use Utilisateurs\UtilisateursBundle\Form\UtilisateursType;

class UtilisateurAdminController extends Controller
{


    public function acceuilAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));

        // Comme vous le voyez, le délais est redondant ici, l'idéale serait de le rendre configurable via votre bundle
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));

        $qb = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')
            ->where('u.lastActivity > :delay')
            ->setParameter('delay', $delay)
            ->getQuery()->getResult();
        /* $entities = $em->getRepository('UtilisateursBundle:Utilisateurs')->findAll();*/


        /* $users = $this->get('knp_paginator')->paginate($entities,$this->get('request')->query->get('page', 1),15);*/
        return $this->render('UtilisateursBundle:Utilisateurs:acceuil.html.twig', array(
            'users_active' => $qb,
        ));
    }
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));

        // Comme vous le voyez, le délais est redondant ici, l'idéale serait de le rendre configurable via votre bundle
        $delay = new \DateTime();
        $delay->setTimestamp(strtotime('2 minutes ago'));

        $qb = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')
            ->where('u.lastActivity > :delay')
            ->setParameter('delay', $delay)
            ->getQuery()->getResult();
        /* $entities = $em->getRepository('UtilisateursBundle:Utilisateurs')->findAll();*/

        $entities = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')->select('u')
            // ->where('u.enabled not LIKE :locked')
            // ->setParameter('locked','0')
            ->getQuery()->getResult();

        /* $users = $this->get('knp_paginator')->paginate($entities,$this->get('request')->query->get('page', 1),15);*/
        return $this->render('UtilisateursBundle:Utilisateurs:index.html.twig', array(
            'users' => $entities,
            'users_active' => $qb,
        ));
    }


    public function showUsersAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UtilisateursBundle:Utilisateurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Utilisateurs entity.');
        }



        return $this->render('UtilisateursBundle:Utilisateurs:showuUsers.html.twig', array(
            'entity'      => $entity ));
    }


    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UtilisateursBundle:Utilisateurs')->find($id);
        /*var_dump($entity);
        die();*/

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Utilisateurs entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('UtilisateursBundle:Utilisateurs:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /*  public function editAction(Request $request,Utilisateurs $user,$id)
      {


          $em = $this->getDoctrine()->getManager();
          $form = $this->createForm(new UtilisateurFormType(),$user);

          $form->handleRequest($request);
          $formView = $form->createView();

          if($form->isSubmitted() && $form->isValid()){

              $em->flush();



          }

          return $this->render('UtilisateursBundle:Utilisateurs:edit.html.twig',array('edit_form'=>$formView,'ids'=>$id, 'entity' => $user));

      }*/


    /**
     * Creates a form to edit a Produits entity.
     *
     * @param Produits $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Utilisateurs $entity)
    {
        $form = $this->createForm(new UtilisateurFormType(), $entity, array(
            'action' => $this->generateUrl('adminUsers_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer' , 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    public function updateAction(Request $request, $id)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UtilisateursBundle:Utilisateurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Utilisateur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','Utilisateur modifié avec succès');
            return $this->redirect($this->generateUrl('adminUsers'));
        }

        return $this->render('UtilisateursBundle:Utilisateurs:edit.html.twig', array(
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
        $session = $this->getRequest()->getSession();
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UtilisateursBundle:Utilisateurs')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Utilisateurs entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success','Utilisateur supprimé avec succès');
        return $this->redirect($this->generateUrl('adminUsers'));
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
            ->setAction($this->generateUrl('adminUsers_delete', array('id' => $id)))
            ->setMethod('DELETE')
            /*->add('submit', 'submit', array('label' => 'Supprimer' , 'attr' => array('class' => 'btn btn-danger ')))*/
            ->getForm()
            ;
    }

}