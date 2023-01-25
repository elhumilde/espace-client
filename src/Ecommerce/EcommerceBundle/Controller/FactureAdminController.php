<?php


namespace Ecommerce\EcommerceBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Entity\Telecontact;
use Ecommerce\EcommerceBundle\Form\TelecontactType;
class FactureAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /*$entities = $em->getRepository('EcommerceBundle:Finals')->findAll();*/
        $entities = $em->getRepository('EcommerceBundle:Finals')->createQueryBuilder('t')->select('t')
            ->orderBy('t.dateCreation', 'desc')
            ->getQuery()->getResult();

        $factures = $this->get('knp_paginator')->paginate($entities,$this->get('request')->query->get('page', 1),50);
        return $this->render('EcommerceBundle:Administration:Facture/index.html.twig', array(
            'factures' => $factures,
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:Finals')->find($id);


        $affichage = $entity->getAffichage();
        $referencement = $entity->getReferencement();

        $panier =$entity->getContenu();
        $paiement =$entity->getPaiement();


        $result =$entity->getResultat();

        return $this->render('EcommerceBundle:Default:produits/layout/contactd.html.twig', array('entity'=>$entity, 'referencement' => $referencement,'contenu' => $panier,'affichage' => $affichage,'paiement'=>$paiement,'somme'=>$result));

    }

    public function factureAction($id)
    {

        $this->container->get('setNewFacture')->contactsec($id)->Output('E-contact.pdf');
        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');
        return $response;

    }


}