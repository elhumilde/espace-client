<?php
/**
 * Created by PhpStorm.
 * User: l.fahimi
 * Date: 31/01/2018
 * Time: 09:25
 */

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Ecommerce\EcommerceBundle\Entity\Client;
class ClientController extends Controller
{
    public function clientsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $findclient= $em->getRepository('EcommerceBundle:Client')->findAll();
        $client = $this->get('knp_paginator')->paginate($findclient,$this->get('request')->query->get('page', 1),3);

        return $this->render('EcommerceBundle:Default:clients/clients.html.twig', array('clients' => $client));
    }

    public function presentationAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('EcommerceBundle:Client')->find($id);

        if (!$client) throw $this->createNotFoundException('La page n\'existe pas.');



        return $this->render('EcommerceBundle:Default:clients/presentation.html.twig', array('client' => $client));
    }


}