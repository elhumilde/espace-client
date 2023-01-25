<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Ecommerce\EcommerceBundle\Entity\Categories;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function acceuilAction(/*Categories $categorie = null*/)
    {
        $session = $this ->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if ($session->has('panier'))
            $panier = $session->get('panier');
        else
            $panier = false;
        return $this->render('EcommerceBundle:Administration:acceuil.html.twig', array( 'panier' => $panier));


    }
}
