<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Ecommerce\EcommerceBundle\Entity\Produits;

class CommandesController extends Controller
{


    public function prepareCommandeAction()
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if (!$session->has('commande'))
            $commande = new Commandes();
        else
            $commande = $em->getRepository('EcommerceBundle:Commandes')->find($session->get('commande'));

      $commande->setDate(new \DateTime());
      $commande->setUtilisateur($this->container->get('security.context')->getToken()->getUser());
      $commande->setReference(0);


      if(! $session->has('commande'))
      {
          $em->persist($commande);
          $session->set('commande',$commande);

          $em->flush();

          return new Response($commande->getId());
      }
    }




}
