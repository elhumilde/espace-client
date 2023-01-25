<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Ecommerce\EcommerceBundle\Entity\Categories;

class PanierController extends Controller
{

    public function menuAction()
    {
        $session = $this->getRequest()->getSession();
        if (!$session->has('panier'))
            $articles = 0;
        else
            $articles = count($session->get('panier'));

        return $this->render('EcommerceBundle:Default:panier/modulesUsed/panier.html.twig', array('articles' => $articles));
    }



    public function ajouterAction($id)
    {
        $session = $this->getRequest()->getSession();

        if (!$session->has('panier')) $session->set('panier',array());
        $panier = $session->get('panier');  // je declare variable session du panier

        if (array_key_exists($id, $panier)) // Si le produit existe au panier
        {
            if ($this->getRequest()->query->get('qte') != null) $panier[$id] = $this->getRequest()->query->get('qte'); //le produit egale a la nouvelle qtz
            $this->get('session')->getFlashBag()->add('success','Quantité modifié avec succès');
        } else {
            if ($this->getRequest()->query->get('qte') != null)
                $panier[$id] = $this->getRequest()->query->get('qte');
            else
                $panier[$id] = 1;

            $this->get('session')->getFlashBag()->add('success','Article ajouté avec succès');
        }

        $session->set('panier',$panier);


        return $this->redirect($this->generateUrl('panier'));
    }

    public function supprimerAction($id)
    {
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier');

        if (array_key_exists($id, $panier))
        {
            unset($panier[$id]);
            $session->set('panier',$panier);
            $this->get('session')->getFlashBag()->add('success','Article supprimé avec succès');
        }

        return $this->redirect($this->generateUrl('panier'));
    }


    public function panierAction()
    {
        $session = $this->getRequest()->getSession();
        if (!$session->has('panier')) $session->set('panier', array());

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        return $this->render('EcommerceBundle:Default:panier/layout/panier.html.twig', array('produits' => $produits,
            'panier' => $session->get('panier')));
    }


    public function validerAction()
    {

        if ($this->get('request')->getMethod() == 'POST')
            $this->setLivraisonOnSession();

        $em = $this->getDoctrine()->getManager();
        $prepareCommande = $this->forward('EcommerceBundle:Commandes:prepareCommande');
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($prepareCommande->getContent());

        return $this->render('EcommerceBundle:Default:panier/layout/valider.html.twig', array('commande' => $commande));

    }

}
