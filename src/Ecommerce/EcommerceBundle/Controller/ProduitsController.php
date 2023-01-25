<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Ecommerce\EcommerceBundle\Entity\Categories;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProduitsController extends Controller
{

    public function vignetteExtensibleAction()
    {
        return $this->render('EcommerceBundle:Default:lien/vignet_extensible.html.twig');
    }
    public function banniereExtensibleAction()
    {
        return $this->render('EcommerceBundle:Default:lien/bannieres_extensibles.html.twig');
    }
    public function auditAction()
    {
        return $this->render('EcommerceBundle:Default:lien/audit.html.twig');
    }
    public function budgetgestiondecontenuAction(/*Categories $categorie = null*/)
    {
        $session = $this ->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();


            if ($session->has('affichage'))
                $panier = $session->get('affichage');

            else{

                $panier = array(

                    'villes'=>NULL,
                    'regions'=>NULL,
                    'cat1'=>  NULL,
                    'cat2r'=>  NULL,
                    'cat2f'=>  NULL,
                    'cat2ma'=>  NULL,
                    'cat2me'=>  NULL,
                    'cat2ag'=>  NULL,
                    'cat2tan'=>  NULL,                    
                    'cat3k'=>  NULL,
                    'cat3o'=>  NULL,
                    'cat3e'=>  NULL,
                    'cat3sa'=>  NULL,
                    'cat3se'=>  NULL,
                    'cat3te'=>  NULL,
                    'cat4'=>    NULL,
                    'pro_du_jour' => NULL,
                    'promo'  => NULL,
                    'vignette_acc_video_nbr2'=>NULL,
                    'vign_ac' => NULL,
                    'habil'   => NULL,
                    'banniere_nombr2' =>NULL,
                    'bann_up_engin' =>NULL,
                    'bann_down_engin' =>NULL,
                    'bann_up_customer' =>NULL,
                    'bann_down_customer'=>NULL,
                    'thematique_name' => NULL,
                    'localite_name' => NULL,
                    'pfjour_name' => NULL,
                    'promo_name' => NULL,
                    'total1_name' => NULL,
                    'habillage_name' => NULL,
                    'banniere_name' => NULL,



                );
                 $session->set('affichage',$panier);
                 }


        $raison =$session->get('raison');
        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);


        return $this->render('EcommerceBundle:Default:produits/layout/budgetisationgestiondecontenu.html.twig', array( 'contenu' => $panier,'raison'=>$raison));


    }

        public function budgetaffichageAction()
        {
            $session = $this ->getRequest()->getSession();
            $em = $this->getDoctrine()->getManager();
            $villes = $em->getRepository('EcommerceBundle:Ville')->findAll();

            if ($session->has('affichage'))
                $panier = $session->get('affichage');
            else
                $panier = array(

                    'villes'=>NULL,
                    'regions'=>NULL,
                    'cat1'=>  NULL,
                    'cat2r'=>  NULL,
                    'cat2f'=>  NULL,
                    'cat2ma'=>  NULL,
                    'cat2me'=>  NULL,
                    'cat2ag'=>  NULL,
                    'cat2tan'=>  NULL,                    
                    'cat3k'=>  NULL,
                    'cat3o'=>  NULL,
                    'cat3e'=>  NULL,
                    'cat3sa'=>  NULL,
                    'cat3se'=>  NULL,
                    'cat3te'=>  NULL,
                    'cat4'=>    NULL,
                    'pro_du_jour' => NULL,
                    'promo'  => NULL,
                    'vignette_acc_video_nbr2'=>NULL,
                    'vign_ac' => NULL,
                    'habil'   => NULL,
                    'banniere_nombr2' =>NULL,
                    'bann_up_engin' =>NULL,
                    'bann_down_engin' =>NULL,
                    'bann_up_customer' =>NULL,
                    'bann_down_customer'=>NULL,

                    'thematique_name' => NULL,
                    'localite_name' => NULL,
                    'pfjour_name' => NULL,
                    'promo_name' => NULL,
                    'total1_name' => NULL,
                    'habillage_name' => NULL,
                    'banniere_name' => NULL,



                );;
            $raison =$session->get('raison');

            return $this->render('EcommerceBundle:Default:produits/layout/budgetisationaffichage.html.twig',array('villes'=>$villes,'affichage'=>$panier,'raison'=>$raison));
        }

                                                 /*Simulation devis*/

    public function devisgestiondecontenuAction()

    {
        $session = $this ->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();


        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);
        return $this->render('EcommerceBundle:Default:produits/layout/devisgestiondecontenu.html.twig', array( 'contenu' => $panier));


    }
  
    /*{
        $session = $this ->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if ($session->has('panier'))
            $panier = $session->get('panier');
        else
            $panier = false;
        return $this->render('EcommerceBundle:Default:produits/layout/devisgestiondecontenu.html.twig', array( 'panier' => $panier));

    }*/

    public function devisaffichageAction()
    {
        $session = $this ->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('EcommerceBundle:Ville')->findAll();

        if ($session->has('affichage'))
            $panier = $session->get('affichage');
        else
            $panier = array(

                'villes'=>NULL,
                'regions'=>NULL,
                'cat1'=>  NULL,
                'cat2r'=>  NULL,
                'cat2f'=>  NULL,
                'cat2ma'=>  NULL,
                'cat2me'=>  NULL,
                'cat2ag'=>  NULL,
                'cat2tan'=>  NULL,                    
                'cat3k'=>  NULL,
                'cat3o'=>  NULL,
                'cat3e'=>  NULL,
                'cat3sa'=>  NULL,
                'cat3se'=>  NULL,
                'cat3te'=>  NULL,
                'cat4'=>    NULL,
                'pro_du_jour' => NULL,
                'promo'  => NULL,
                'vignette_acc_video_nbr2'=>NULL,
                'vign_ac' => NULL,
                'habil'   => NULL,
                'banniere_nombr2' =>NULL,
                'bann_up_engin' =>NULL,
                'bann_down_engin' =>NULL,
                'bann_up_customer' =>NULL,
                'bann_down_customer'=>NULL,

                'thematique_name' => NULL,
                'localite_name' => NULL,
                'pfjour_name' => NULL,
                'promo_name' => NULL,
                'total1_name' => NULL,
                'habillage_name' => NULL,
                'banniere_name' => NULL,



            );;

        return $this->render('EcommerceBundle:Default:produits/layout/devisaffichage.html.twig',array('villes'=>$villes,'affichage'=>$panier));
    }

    public function banniereVideoAction() {
        return $this->render("EcommerceBundle:Default/lien:banniere_video.html.twig");
    }

    public function professionAction()
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if ($session->has('affichage'))
            $panier = $session->get('affichage');

        else{

            $panier = array(

                'villes'=>NULL,
                'villes1'=>NULL,
                'villes2'=>NULL,
                'villes3'=>NULL,
                'villes4'=>NULL,
                'villes5'=>NULL,
                'villes6'=>NULL,
                'regions'=>NULL,
                'cat1'=>  NULL,
                'cat2r'=>  NULL,
                'cat2f'=>  NULL,
                'cat2ma'=>  NULL,
                'cat2me'=>  NULL,
                'cat2ag'=>  NULL,
                'cat2tan'=>  NULL,                    
                'cat3k'=>  NULL,
                'cat3o'=>  NULL,
                'cat3e'=>  NULL,
                'cat3sa'=>  NULL,
                'cat3se'=>  NULL,
                'cat3te'=>  NULL,
                'cat4'=>    NULL,
                'pro_du_jour' => NULL,
                'promo'  => NULL,
                'vignette_acc_video_nbr2'=>NULL,
                'vign_ac' => NULL,
                'habil'   => NULL,
                'banniere_nombr2' =>NULL,
                'bann_up_engin' =>NULL,
                'bann_down_engin' =>NULL,
                'bann_up_customer' =>NULL,
                'bann_down_customer'=>NULL,
                'thematique_name' => NULL,
                'localite_name' => NULL,
                'pfjour_name' => NULL,
                'promo_name' => NULL,
                'total1_name' => NULL,
                'habillage_name' => NULL,
                'banniere_name' => NULL,



            );
            $session->set('affichage',$panier);
        }

        if ($session->has('profession'))
            $profession = $session->get('profession');
        else
            $profession =array(

                'logo'=>null,
                'parcours_diplomes'=>null,
                'specialites'=>null,
                'services'=>null,
                'rubrique'=>null,
                'certifications'=>null,
                'first_day'=>null,
                'samedi'=>null,
                'second_day'=>null,
                'h_samedi'=>null,
                'hour1'=>null,
                'hour2'=>null,
                'hour3'=>null,
                'hour4'=>null,
                'langue'=>null,
                'paiement'=>null,
                'socieux'=>null,
                'villesp'=>null,
                'profession'=>null,
                'prix'=>null,
                'count_specialites'=>null,
                'count_services'=>null,

            );
        $raison =$session->get('raison');
        $villes = $em->getRepository('EcommerceBundle:Ville')->findBy(array(), array('libelle' => 'asc'));
        $Rubrique_Profession_Liberale = $em->getRepository('EcommerceBundle:RubriqueProfessionLiberale')->findBy(array(), array('libelle' => 'asc'));
        $dentistes = $em->getRepository('EcommerceBundle:Rubrique')->createQueryBuilder('u')->select('u')
            /*  ->groupBy('u.groupe')*/
            ->where('u.libelle like  :libelle')
            ->setParameter('libelle', '%dentiste%')
            ->getQuery()->getResult();

        $medecins = $em->getRepository('EcommerceBundle:Rubrique')->createQueryBuilder('u')->select('u')
            /*  ->groupBy('u.groupe')*/
            ->where('u.libelle like  :libelle')
            ->setParameter('libelle', '%medecin%')
            ->getQuery()->getResult();

        $avocats = $em->getRepository('EcommerceBundle:Rubrique')->createQueryBuilder('u')->select('u')
            /*  ->groupBy('u.groupe')*/
            ->where('u.libelle like  :libelle')
            ->setParameter('libelle', '%avocat%')
            ->getQuery()->getResult();

        return $this->render('EcommerceBundle:Default:professionliberale/professionliberale.html.twig',
            array(
                'villes' => $villes,
                'dentistes' => $dentistes,
                'medecins' => $medecins,
                'avocats' => $avocats,
                'profession' => $profession,
                'raison' => $raison,
                'Rubrique_Profession_Liberale' => $Rubrique_Profession_Liberale
            ));


    }

    public function peelOffAction() {
        return $this->render("EcommerceBundle:Default/lien:peel_off.html.twig");
    }

    public function cube3dAction() {
        return $this->render("EcommerceBundle:Default/lien:cude_3d.html.twig");
    }
        
    /*{
        $session = $this ->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('EcommerceBundle:Ville')->findAll();

        if ($session->has('affichage'))
            $panier = $session->get('affichage');
        else
            $panier = false;
        return $this->render('EcommerceBundle:Default:produits/layout/devisaffichage.html.twig', array('villes' => $villes,'affichage'=>$panier));


    }*/


    public function prestationAction()
    {
        return $this->render('EcommerceBundle:Default:lien/prestation.html.twig');
    }
    public function marqueAction()
    {
        return $this->render('EcommerceBundle:Default:lien/marque.html.twig');
    }
    public function rubriqueAction()
    {
        return $this->render('EcommerceBundle:Default:lien/rubrique.html.twig');
    }
    public function videoAction()
    {
        return $this->render('EcommerceBundle:Default:lien/video.html.twig');
    }
    public function cartevisiteAction()
    {
        return $this->render('EcommerceBundle:Default:lien/cartevisite.html.twig');
    }
    public function catalogueAction()
    {
        return $this->render('EcommerceBundle:Default:lien/cataloguereference.html.twig');
    }
    public function pviAction()
    {
        return $this->render('EcommerceBundle:Default:lien/pvi.html.twig');
    }
    public function banniereAction()
    {
        return $this->render('EcommerceBundle:Default:lien/banniere.html.twig');
    }
    public function vignetteTAction()
    {
        return $this->render('EcommerceBundle:Default:lien/vignettethematique.html.twig');
    }
    public function vignetteLAction()
    {
        return $this->render('EcommerceBundle:Default:lien/vignettelocalte.html.twig');
    }
    public function vignetteavAction()
    {
        return $this->render('EcommerceBundle:Default:lien/vignetteaccueilvideo.html.twig');
    }
    public function professionnelsAction()
    {
        return $this->render('EcommerceBundle:Default:lien/professionnels.html.twig');
    }
    public function espacepromoAction()
    {
        return $this->render('EcommerceBundle:Default:lien/espacepromo.html.twig');
    }
    public function habillageAction()
    {
        return $this->render('EcommerceBundle:Default:lien/habillage.html.twig');
    }
    public function professionLAction()
    {
        return $this->render('EcommerceBundle:Default:lien/profession.html.twig');
    }

    public function catalogueConnecteAction() {
        return $this->render("EcommerceBundle:Default:lien/catalogue_connecte.html.twig");
    }

    public function galeriePhotosEtReferencesAction() {
        return $this->render("EcommerceBundle:Default:lien/galerie_photos_et_references.html.twig");
    }

    public function siteAction()
    {
        return $this->render('EcommerceBundle:Default:lien/site_internet.html.twig');
    }
    public function cataloguerefAction()
    {
        return $this->render('EcommerceBundle:Default:lien/catalogue_ref.html.twig');
    }

    /* ajax*/
    public function ajaxVAAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $opt1  =  $request->request->get('opt1');
        $ville =  $request->request->get('region');
        $regions=$em->getRepository('EcommerceBundle:Ville')->findOneBy(array('codeville' => $ville));

        $region=$regions->getRegion()->getCoderegion();
        $ctar =  $request->request->get('ctar');
/*        $query = $em->createQuery('SELECT t FROM  Ecommerce\EcommerceBundle\Entity\TarifInternet t WHERE t.opt1='.$opt1.' and t.ctar=.'.$ctar);
        $query->setParameter('region',$ville);
        */
         $var='rGion'.$region;
        $query = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.'.$var.' AS VAR','t.libelle AS L','t.libOpt1 AS LO1','t.libOpt2 AS LO2')
        ->where('t.opt1 = :op')->setParameter('op',$opt1)
        ->andWhere('t.ctar = :ct')->setParameter('ct',$ctar)->getQuery()->getSingleResult();
        //$var1='rGion'.$region1;
       // $query1 = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.'.$var1.' AS VAR')
           // ->where('t.opt1 = :op')->setParameter('op',$zone1)
            //->andWhere('t.ctar = :ct')->setParameter('ct',$ctar1)->getQuery()->getSingleResult();



//        return new Response(json_encode($query),200);


      $response = new JsonResponse();
        //$response1 = new JsonResponse();
     return $response->setData(array('nom' => $query));
        //return $response1->setData(array('localite' => $query1));
        /*   $result = $em->getRepository('EcommerceBundle:Prestation')->getPrestation($prestations);*/

        /*  $em = $this->getDoctrine()->getManager();
          $prestations   =  $request->request->get('rubrique');
       /*   $rubriques = $em->getRepository('EcommerceBundle:Prestation')->findBy(array("rubrique_id"=>$prestations));*/
        /*  $response = new JsonResponse();
                  $prest= $this->getDoctrine()->getRepository('EcommerceBundle:Prestation')->findBy(array('rub' => $prestations ));
          */     /*   $var=array();
            foreach ($prest as $rub){
                 $var['id'][]=$rub->getId();
                 $var['presta'][]=$rub->getPrestation();
        }*/
        /*  $prest = json_encode($prest);
    /*  $prest= json_encode($rubriques);*/
        /*    return $response->setData(array('nom' => $prest));*/
        /*$parametersAsArray = [];
        if($content = $request ->getContent()){
            $parametersAsArray = json_decode($content,true);
        }*/
    }

    public function ajaxTAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ctar =  $request->request->get('ctar');
        $opt1 =  $request->request->get('opt1');

       $query = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.rGion1 AS T','t.libelle AS L','t.libOpt1 AS LO1')
       ->where('t.opt1 = :op')->setParameter('op',  $opt1)
       ->andWhere('t.ctar = :ct')->setParameter('ct',$ctar)->getQuery()->getSingleResult();

        $response = new JsonResponse();
        return $response->setData(array('nom' => $query));

    }
    /*
    public function ajaxPJAction(Request $request)
   {
       $em = $this->getDoctrine()->getManager();
       $ctar =  $request->request->get('ctar');
       $opt1 =  $request->request->get('opt1');

       $query = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.rGion1 AS PJ')
           ->where('t.opt1 = :op')->setParameter('op',  $opt1)
           ->andWhere('t.ctar = :ct')->setParameter('ct',$ctar)->getQuery()->getSingleResult();
       $response = new JsonResponse();
       return $response->setData(array('nom' => $query));

   }
   /*
   pufunction ajaxEPAction(Request $request)
   {
       $em = $this->getDoctrine()->getManager();
       $ctar =  $request->request->get('ctar');
       $opt1 =  $request->request->get('opt1');

       $query = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.rGion1 AS EP')
           ->where('t.opt1 = :op')->setParameter('op',  $opt1)
           ->andWhere('t.ctar = :ct')->setParameter('ct',$ctar)->getQuery()->getSingleResult();

       $response = new JsonResponse();
       return $response->setData(array('nom' => $query));

   }

   public function ajaxHBAction(Request $request)
   {
       $em = $this->getDoctrine()->getManager();
       $ctar =  $request->request->get('ctar');
       $opt1 =  $request->request->get('opt1');

       $query = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.rGion1 AS HB')
           ->where('t.opt1 = :op')->setParameter('op',  $opt1)
           ->andWhere('t.ctar = :ct')->setParameter('ct',$ctar)->getQuery()->getSingleResult();

       $response = new JsonResponse();
       return $response->setData(array('nom' => $query));

   }*/





}
