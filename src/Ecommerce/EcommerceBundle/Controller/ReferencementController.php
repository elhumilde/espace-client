<?php

namespace Ecommerce\EcommerceBundle\Controller;


use Ecommerce\EcommerceBundle\Entity\Finals;
use Ecommerce\EcommerceBundle\Entity\Prestation;
use Ecommerce\EcommerceBundle\Form\ReferencementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Entity\MobileVille;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Ecommerce\EcommerceBundle\Entity\Mails;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class ReferencementController extends Controller
{
    /**
     * @return Response
     */


    public function indexAction(Request $request)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $request = $this->container->get('request');
        $session = $request->getSession();

        $raison =array(
            'rs'=>NULL,
            'cfirme'=>NULL,
            'civi'=>NULL,
            'sign'=>NULL,
            'profession'=>NULL,

        );
        $this->getRequest()->getSession()->clear();
        return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array('user' => $user,'raison' => $raison));

        // ici ce pour effacer toutes les sessions



    }

    public function moteurAction(/*Categories $categorie = null*/)
    {

        ini_set('memory_limit', '-1');
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

        if ($session->has('referencement'))
            $panier = $session->get('referencement');
        else
            $panier =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL,
                'rub1'=>NULL,
                'rub2'=>NULL,
                'rub3'=>NULL,
                'rub4'=>NULL,
                'rub5'=>NULL,
                'rub6'=>NULL,
                'rub7'=>NULL,
                'sel'=>NULL,
                'sel1'=>NULL,
                'villes1'=>NULL,
                'villes2'=>NULL,
                'villes3'=>NULL,
                'villes4'=>NULL,
                'villes5'=>NULL,
                'villes6'=>NULL,

            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>NULL,
                    'profession'=>NULL,
                    'prix'=>NULL,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'addprest1'=>NULL,
                    'addprest2'=>NULL,
                    'addprest3'=>NULL,
                    'addprest4'=>NULL,
                    'addprest5'=>NULL,
                    'addprest6'=>NULL,
                    'addprest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,
                    'package'=>NULL,
                    'package_value'=>0,

                );

        if ($session->has('marque'))
            $marque = $session->get('marque');

        else
            $marque =
                array(
                    'marq1'=>NULL,
                    'marq2'=>NULL,
                    'marq3'=>NULL,
                    'marq4'=>NULL,
                    'marq5'=>NULL,
                    'marq6'=>NULL,
                    'marq7'=>NULL,
                    'marq8'=>NULL,
                    'marq9'=>NULL,
                    'marq10'=>NULL,
                    'posi1'=>NULL,
                    'posi2'=>NULL,
                    'posi3'=>NULL,
                    'posi4'=>NULL,
                    'posi5'=>NULL,
                    'posi6'=>NULL,
                    'posi7'=>NULL,
                    'posi8'=>NULL,
                    'posi9'=>NULL,
                    'posi10'=>NULL,


                );
        $rubriques = $em->getRepository('EcommerceBundle:Rubrique')->findAll();

        $raison =$session->get('raison');

        $villes = $em->getRepository('EcommerceBundle:VilleD')->findBy(array(), array('libelle' => 'asc'));

        return $this->render('EcommerceBundle:Default:produits/layout/moteur.html.twig', array('rubriques' => $rubriques,'villes' => $villes,
                'referencement' => $panier,
                'raison' => $raison,
                'profession'=>$profession,
                'desrubref'=>$desrubref,
                'marque'=>$marque,
        ));
    }

    public function secAction(/*Categories $categorie = null*/)
    {
        $session = $this ->getRequest()->getSession();

        if ($session->has('affichage'))

            $affichage = $session->get('affichage');

        else
            $affichage =array(

                'villes'=>NULL,
                'regions'=>NULL,
                'cat1'=> NULL,
                'cat2r'=>  NULL,
                'cat2f'=>  NULL,
                'cat2ma'=>  NULL,
                'cat2me'=>  NULL,
                'cat2ag'=> NULL,
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
                'promo_name' =>NULL,
                'total1_name' => NULL,
                'habillage_name' => NULL,
                'banniere_name' => NULL,


            );



        if ($session->has('referencement'))
            $referencement = $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL,
                'r_count'=>NULL,
                'rubrique1'=>NULL,
                'villes1'=>NULL,
                'villes2'=>NULL,
                'villes3'=>NULL,
                'villes4'=>NULL,
                'villes5'=>NULL,
                'villes6'=>NULL,
            );



        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);


        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement =
                array(
                    'montantttc'=>NULL,
                    'accompte'=>NULL,
                    'reste'=> NULL,
                    'nbr'=>NULL,
                    'montant1'=>NULL,
                    'montant2'=>NULL,
                    'montant3'=>NULL,
                    'montant4'=>NULL,
                    'montant4'=>NULL,
                    'montant5'=>NULL,
                    'dateP1'=>NULL,
                    'dateP2'=>NULL,
                    'dateP3'=>NULL,
                    'dateP4'=>NULL,
                    'dateP5'=>NULL,
                );


        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,

                );

        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>NULL,
                    'profession'=>NULL,
                    'prix'=>NULL,

                );
        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,
                    'tva'=>NULL,

                );


        if ($session->has('nbr_rub'))
            $nbr_rub = $session->get('nbr_rub');
        else
            $nbr_rub =
                array(
                    'nbr_rub'=>NULL,
                );

        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );
        if ($session->has('visbilite_header'))
            $visbilite_header = $session->get('visbilite_header');
        else
            $visbilite_header =
                array(
                    'proposition'=>null,
                    'ordre'=>null,
                    'bon_commande'=>null,
                );
        if($session->has('raison'))
            $raison =$session->get('raison');
        else
            $raison =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );

        $ref =$session->get('ref');
        $con =$session->get('cont');
        $aff =$session->get('aff');
        /* $prof =$session->get('prof');*/

        /*$raison =$session->get('raison');*/

        /*var_dump($desrubref);
        die();*/
        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        return $this->render('EcommerceBundle:Default:produits/layout/contact.html.twig', array('raison'=>$raison, 'referencement' => $referencement,'contenu' => $panier,'affichage' => $affichage,'somme'=>$result,'paiement' =>$paiement,'desrubref'=>$desrubref,'profession'=>$profession,'code'=>$code,'nbr_rub'=>$nbr_rub,'marque'=>$marque,'visbilite_header'=>$visbilite_header));


    }






    public function secfayssalAction(/*Categories $categorie = null*/)
    {
        $session = $this ->getRequest()->getSession();

        if ($session->has('affichage'))

            $affichage = $session->get('affichage');

        else
            $affichage =array(

                'villes'=>NULL,
                'regions'=>NULL,
                'cat1'=> NULL,
                'cat2r'=>  NULL,
                'cat2f'=>  NULL,
                'cat2ma'=>  NULL,
                'cat2me'=>  NULL,
                'cat2ag'=> NULL,
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
                'promo_name' =>NULL,
                'total1_name' => NULL,
                'habillage_name' => NULL,
                'banniere_name' => NULL,


            );



        if ($session->has('referencement'))
            $referencement = $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL,
                'r_count'=>NULL,
                'rubrique1'=>NULL,
                'r_count'=>NULL,
            );



        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);


        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement =
                array(
                    'montantttc'=>NULL,
                    'accompte'=>NULL,
                    'reste'=> NULL,
                    'nbr'=>NULL,
                    'montant1'=>NULL,
                    'montant2'=>NULL,
                    'montant3'=>NULL,
                    'montant4'=>NULL,
                    'montant4'=>NULL,
                    'montant5'=>NULL,
                    'dateP1'=>NULL,
                    'dateP2'=>NULL,
                    'dateP3'=>NULL,
                    'dateP4'=>NULL,
                    'dateP5'=>NULL,
                );


        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,

                    'package'=>NULL,
                    'package_value'=>0,

                );

        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );
        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,
                    'tva'=>NULL,

                );


        if ($session->has('nbr_rub'))
            $nbr_rub = $session->get('nbr_rub');
        else
            $nbr_rub =
                array(
                    'nbr_rub'=>NULL,
                );

        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );
        if ($session->has('visbilite_header'))
            $visbilite_header = $session->get('visbilite_header');
        else
            $visbilite_header =
                array(
                    'proposition'=>null,
                    'ordre'=>null,
                    'bon_commande'=>null,
                );
        if($session->has('raison'))
            $raison =$session->get('raison');
        else
            $raison =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );

        $ref =$session->get('ref');
        $con =$session->get('cont');
        $aff =$session->get('aff');
        /* $prof =$session->get('prof');*/

        /*$raison =$session->get('raison');*/

        /*var_dump($desrubref);
        die();*/
        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        return $this->render('EcommerceBundle:Default:produits/layout/contactfayssal.html.twig', array('raison'=>$raison, 'referencement' => $referencement,'contenu' => $panier,'affichage' => $affichage,'somme'=>$result,'paiement' =>$paiement,'desrubref'=>$desrubref,'profession'=>$profession,'code'=>$code,'nbr_rub'=>$nbr_rub,'marque'=>$marque,'visbilite_header'=>$visbilite_header));


    }


    public function pdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this ->getRequest()->getSession();
        if($request->isMethod('Post')) {

            $mail = $request->request->get('email_ad');
            $join = $request->request->get('joindre');
            $text='Merci de trouver ci-joint notre proposition de visibilitÃ© sur telecontact.ma';

            $affichage =$session->get('affichage');


            if ( $session->has('referencement'))
                $referencement =  $session->get('referencement');
            else
                $referencement =array(
                    'rubrique'=>NULL,
                    'prest'=>NULL,
                    'prest_sup'=> NULL,
                    'marque'=>NULL,
                    'sum3'=>NULL ,
                    'mari'=> NULL,
                    'marque_pack'=> NULL,
                    'resulta'=> NULL,
                    'resulta2'=> NULL,
                    'rubd'=> NULL,
                    'rania'=> NULL,
                    'raniad'=> NULL,
                    'villes'=>NULL,
                    'regions'=>NULL,
                    'villes_sup'=>NULL,
                    'regions_sup'=>NULL);

            if ($session->has('contenu'))
                $contenu = $session->get('contenu');
            else
                $contenu = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);

            if ($session->has('profession'))
                $profession = $session->get('profession');

            else
                $profession =
                    array(
                        'villesp'=>null,
                        'profession'=>null,
                        'prix'=>null,
                        'parcours_diplomes'=>null,
                        'specialites'=>null,
                        'services'=>null,
                        'rubrique'=>null,
                        'certifications'=>null,
                        'first_day'=>null,
                        'second_day'=>null,
                        'hour1'=>null,
                        'hour2'=>null,
                        'hour3'=>null,
                        'hour4'=>null,
                        'langue'=>null,
                        'paiement'=>null,
                        'socieux'=>null,

                    );
            if ($session->has('code'))
                $code = $session->get('code');

            else
                $code =
                    array(
                        'nature_remise'=>NULL,
                        'montant_remise'=>NULL,
                        'offre'=>NULL,
                        'pourcentage'=>NULL,
                        'montant_r'=>NULL,
                        'montant_rem'=>NULL,

                    );
            if ($session->has('nbr_rub'))
                $nbr_rub = $session->get('nbr_rub');
            else
                $nbr_rub =
                    array(
                        'nbr_rub'=>NULL,
                    );



            if ($session->has('desrubref'))
                $desrubref = $session->get('desrubref');

            else
                $desrubref =
                    array(
                        'rub1'=>NULL,
                        'rub2'=>NULL,
                        'rub3'=>NULL,
                        'rub4'=>NULL,
                        'rub5'=>NULL,
                        'rub6'=>NULL,
                        'rub7'=>NULL,
                        'prest1'=>NULL,
                        'prest2'=>NULL,
                        'prest3'=>NULL,
                        'prest4'=>NULL,
                        'prest5'=>NULL,
                        'prest6'=>NULL,
                        'prest7'=>NULL,
                        'prestsupp'=>NULL,
                        'r_count'=>NULL,
                        'villes1'=>NULL,
                        'villes2'=>NULL,
                        'villes3'=>NULL,
                        'villes4'=>NULL,
                        'villes5'=>NULL,
                        'villes6'=>NULL,
                        'villes7'=>NULL,
                        'villes_panier1'=>NULL,
                        'villes_panier2'=>NULL,
                        'villes_panier3'=>NULL,
                        'villes_panier4'=>NULL,
                        'villes_panier5'=>NULL,
                        'villes_panier6'=>NULL,
                        'villes_panier7'=>NULL,

                        'rub_supp_1'=>0,
                        'rub_supp_2'=>0,
                        'rub_supp_3'=>0,
                        'rub_supp_4'=>0,
                        'rub_supp_5'=>0,
                        'rub_supp_6'=>0,
                        'rub_supp_7'=>0,

                        'localiter_supp'=>0,
                        'localiter_supp2'=>0,
                        'localiter_supp3'=>0,
                        'localiter_supp4'=>0,
                        'localiter_supp5'=>0,
                        'localiter_supp6'=>0,
                        'localiter_supp7'=>0,
                        'localiter_supp1'=>0,

                        'final'=>0,
                        'fayssal'=>0,
                    );

            if ($session->has('marque'))
                $marque = $session->get('marque');
            else
                $marque =
                    array(

                        'marq1'=>null,
                        'marq2'=>null,
                        'marq3'=>null,
                        'marq4'=>null,
                        'marq5'=>null,
                        'marq6'=>null,
                        'marq7'=>null,
                        'marq8'=>null,
                        'marq9'=>null,
                        'marq10'=>null,
                        'positionnement'=>null,
                        'positionnement1'=>null,
                        'positionnement2'=>null,
                        'positionnement3'=>null,
                        'positionnement4'=>null,
                        'positionnement5'=>null,
                        'positionnement6'=>null,
                        'positionnement7'=>null,
                        'positionnement8'=>null,
                        'positionnement9'=>null,
                    );

            if ($session->has('visbilite_header'))
                $visbilite_header = $session->get('visbilite_header');
            else
                $visbilite_header =
                    array(

                        'proposition'=>null,
                        'ordre'=>null,
                        'bon_commande'=>null,

                    );


            $ref =$session->get('ref');
            $con = $session->get('cont');
            $aff = $session->get('aff');
            /*$prof = $session->get('prof');*/
            /*$raison =$session->get('raison');*/
            if($session->has('raison'))
                $raison =$session->get('raison');
            else
                $raison =array(
                    'rs'=>NULL,
                    'cfirme'=>NULL,
                    'civi'=>NULL,
                    'sign'=>NULL,
                    'profession'=>NULL,

                );



            $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);



            $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactsec.html.twig', array('raison'=> $raison,'referencement'=> $referencement, 'affichage'=> $affichage,'contenu'=>$contenu,'somme'=>$result,'profession'=>$profession,'code'=>$code,'nbr_rub'=>$nbr_rub,'desrubref'=>$desrubref,'marque'=>$marque,'visbilite_header'=>$visbilite_header));
            $html2pdf = new \Html2Pdf_Html2Pdf('P','letter','fr');
            $html2pdf->pdf->SetAuthor('E-contact');
            $html2pdf->pdf->SetTitle('E-contact');
            $html2pdf->pdf->SetDisplayMode('default');
            $html2pdf->writeHTML($html);

            $to = $mail;
            $from      = "e-contact@telecontact.ma";
            $vb        ="f.anouar@edicom.ma";
            $subject   = "Mail EnvoyÃ© E-contact";
            $message   = "<p>.$text.</p>";
            $separator = md5(time());
            $eol       = PHP_EOL;
            $filename   = "e-contact.pdf";


            $filenamed   = "baniere-TLC.pdf";
            $filenamed1   = "Carte-visite.pdf";
            $filenamed2   = "Espace-Promo.pdf";
            $filenamed3   = "Habillage.pdf";
            $filenamed4   = "Marques.pdf";
            $filenamed5   = "Prestation.pdf";
            $filenamed6   = "Professionnels-du-jour.pdf";
            $filenamed7  = "Rubrique.pdf";
            $filenamed8  = "Video.pdf";
            $filenamed9  = "Vignette-Accueil-Video.pdf";
            $filenamed10  = "Vignette-localite.pdf";
            $filenamed11  = "Vignette-thÃ©matique.pdf";





            $pdfdoc     = $html2pdf->Output('e-contact.pdf', 'S');
            $attachment = chunk_split(base64_encode($pdfdoc));

            $pdfdocd = file_get_contents("http://www.telecontact.ma/trouver/pdf/baniere-TLC.pdf");
            $attachmentd = chunk_split(base64_encode($pdfdocd));



            $headers = "From: " . $from . $eol;
            $headers .= "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
            $headers .= "Cc: " . $vb . $eol;

            $body = "Content-Transfer-Encoding: 7bit" . $eol;
            $body .= "This is a MIME encoded message." . $eol; //had one more .$eol


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
            $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
            $body .= $message . $eol;


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;
            $body .= $attachment . $eol;


            if(in_array("Baniere", $joind)){
                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd . $eol;

            }


            if(in_array("Carte", $joind)){


                $pdfdocd1 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Carte-visite.pdf");
                $attachmentd1 = chunk_split(base64_encode($pdfdocd1));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed1 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd1 . $eol;

            }

            if(in_array("Espace", $joind)){


                $pdfdocd2 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Espace-Promo.pdf");
                $attachmentd2 = chunk_split(base64_encode($pdfdocd2));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed2 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd2 . $eol;

            }

            if(in_array("Habillage", $joind)){



                $pdfdocd3 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Habillage.pdf");
                $attachmentd3 = chunk_split(base64_encode($pdfdocd3));

                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed3 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd3 . $eol;

            }


            if(in_array("Marques", $joind)){



                $pdfdocd4 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Marques.pdf");
                $attachmentd4 = chunk_split(base64_encode($pdfdocd4));

                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed4 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd4 . $eol;

            }
            if(in_array("Prestation", $joind)){



                $pdfdocd5 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Prestation.pdf");
                $attachmentd5 = chunk_split(base64_encode($pdfdocd5));

                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed5 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd5 . $eol;

            }
            if(in_array("Professionnels", $joind)){



                $pdfdocd6 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Professionnels-du-jour.pdf");
                $attachmentd6 = chunk_split(base64_encode($pdfdocd6));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed6 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd6 . $eol;

            }

            if(in_array("Rubrique", $joind)){


                $pdfdocd7 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Rubrique.pdf");
                $attachmentd7 = chunk_split(base64_encode($pdfdocd7));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed7 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd7 . $eol;

            }
            if( in_array("Video", $joind)){


                $pdfdocd8 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Video.pdf");
                $attachmentd8 = chunk_split(base64_encode($pdfdocd8));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed8 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd8 . $eol;

            }

            if(in_array("Vignette_vid", $joind)){


                $pdfdocd9 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Vignette-Accueil-Video.pdf");
                $attachmentd9 = chunk_split(base64_encode($pdfdocd9));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed9 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd9 . $eol;

            }

            if(in_array("Vignette_lo", $joind)){



                $pdfdocd10 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Vignette-localite.pdf");
                $attachmentd10 = chunk_split(base64_encode($pdfdocd10));

                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed10 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd10 . $eol;

            }

            if(in_array("Vignette_th", $joind)) {


                $pdfdocd11 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Vignette_th.pdf");
                $attachmentd11 = chunk_split(base64_encode($pdfdocd11));


                $body .= "--" . $separator . $eol;
                $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed11 . "\"" . $eol;
                $body .= "Content-Transfer-Encoding: base64" . $eol;
                $body .= "Content-Disposition: attachment" . $eol . $eol;

                $body .= $attachmentd11 . $eol;

            }




            $body .= "--" . $separator . "--";



            if($mail){
                $la = mail($to, $subject, $body, $headers);
            }
            if ($la) {

                $this->get('session')->getFlashBag()->add('success','Mail envoyÃ© avec success');
            } else {

                $this->get('session')->getFlashBag()->add('error','mail n\'a pas Ã©tÃ© envoyÃ©');
            }

            return $this->redirect($this->generateUrl('facture_sec'));
        }

        return $this->render('EcommerceBundle:Default:referencement/modulesUsed/referencement.html.twig');
    }

    public function fnAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $date_tr=new \DateTime('now');

        $by_tr=$this->container->get('security.context')->getToken()->getUser();


        $session = $this ->getRequest()->getSession();
        $affichage = $session->get('affichage');

        if ($session->has('referencement'))
            $referencement = $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL,
                'sel'=>NULL,
                'sel1'=>NULL,

            );

        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array
            ('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);






        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>NULL,
                    'profession'=>NULL,
                    'prix'=>NULL,

                );


        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prestsupp'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'r_count'=>NULL,


                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );

        $ref =$session->get('ref');
        $con =$session->get('cont');
        $aff =$session->get('aff');
        /*$prof =$session->get('prof');*/
        $raison =$session->get('raison');

        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        $rs    = $raison['rs'];
        $cfirme= $raison['cfirme'];
        $civi  = $raison['civi'];
        $sign  = $raison['sign'];

        $entity= new Finals();
        $entity->setCfirme($cfirme);
        $entity->setRs($rs);
        $entity->setAffichage($affichage);
        $entity->setReferencement($referencement);
        $entity->setContenu($panier);
        $entity->setPaiement($paiement);
        $entity->setProfession($profession);
        $entity->setDesrubref($desrubref);
        $entity->setCode($code);
        $entity->setMarque($marque);
        $entity->setResultat($result);
        $entity->setDateCreation($date_tr);
        $entity->setUtilisateur($by_tr);
        $entity->setCivi($civi);
        $entity->setSign($sign);

        $em->persist($entity);

        $em->flush();





        return new Response(json_encode($result), 200);


    }

    public function mailpropositionAction(Request $request)
    {


        $mail = $request->request->get('email');
        $join = $request->request->get('join');



        $text='Bonjour <br><br>Je vous prie de trouver en pièce jointe notre proposition de parution dans telecontact.ma <br><br>
              Je reste bien évidemment à votre disposition pour tout complément d\'information.<br><br>
              En attendant votre retour, veuillez agréer nos sincères salutations <br><br>
              Bien cordialement';
        $by_email=$this->container->get('security.context')->getToken()->getUser()->getEmail();

        $session = $this ->getRequest()->getSession();

        $affichage =$session->get('affichage');


        if ( $session->has('referencement'))
            $referencement =  $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);


        if ($session->has('contenu'))
            $contenu = $session->get('contenu');
        else
            $contenu = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);

        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'new_ht'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,
                    'package'=>NULL,
                    'package_value'=>0,

                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );


        if ($session->has('visbilite_header'))
            $visbilite_header = $session->get('visbilite_header');
        else
            $visbilite_header =
                array(
                    'visbilite_header'=>null,

                );


        $ref =$session->get('ref');
        $con = $session->get('cont');
        $aff = $session->get('aff');
        /*$prof = $session->get('prof');*/
        /* $raison =$session->get('raison');*/
        if($session->has('raison'))
            $raison =$session->get('raison');
        else
            $raison =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );



        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);



        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactsec.html.twig', array('raison'=> $raison,'referencement'=> $referencement, 'affichage'=> $affichage,'contenu'=>$contenu,'somme'=>$result,'paiement'=>$paiement,'profession'=>$profession,'desrubref'=>$desrubref,'marque'=>$marque,'code'=>$code,'visbilite_header'=>$visbilite_header,'by_code'=>1));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','letter','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        $to = $mail;
        $from      = "e-contact@telecontact.ma";
        $vb        ="a.skalli@edicom.ma";
        $subject   = "Mail Envoyée E-contact";
        $message   = "<p>.$text.</p>";
        $separator = md5(time());
        $eol       = PHP_EOL;
        $visbilite_header     = $request->request->get('visbilite_header');


        $filename   = "Proposition.pdf";




        $filenamed   =   "bannière-classique.pdf";
        $filenamed1   =  "Carte-de-visite.pdf";
        $filenamed2   =  "Espace-promo.pdf";
        $filenamed3   =  "Habillage.pdf";
        $filenamed4   =  "Marques.pdf";
        $filenamed5   =  "Prestation.pdf";
        $filenamed6   =  "Professionnels-du-jour.pdf";
        $filenamed7  =   "Rubrique.pdf";
        $filenamed8  =   "Video.pdf";
        $filenamed9  =   "Vignette-classique.pdf";
        $filenamed10 =   "Vignette-localité.pdf";
        $filenamed11  =  "Vignette-thématique.pdf";
        $filenamed12  =  "Site-contact.pdf";
        $filenamed13  =  "PVI.pdf";
        $filenamed14  =  "Catalogue_ref.pdf";
        $filenamed15  =  "CONDITIONS-GENERALES_VENTE.pdf";
        $filenamed16  =  "Profession-libérale.pdf";
        $filenamed17  =  "Vignette-extensible.pdf";
        $filenamed18  =  "Audit-de-site.pdf";
        $filenamed19  =  "Bannière-extensible.pdf";
        $filenamed20  =  "Facebook-Ads.pdf";
        $filenamed21  =  "Film-Interview.pdf";
        $filenamed22  =  "Motion-Design.pdf";
        $filenamed23  =  "Vidéo-corporate.pdf";
        $filenamed24  =  "Vidéo-graphique.pdf";
        $filenamed25  =  "Audience-Telecontact.pdf";



        $pdfdoc     = $html2pdf->Output('Proposition.pdf', 'S');





        $attachment = chunk_split(base64_encode($pdfdoc));

        $pdfdocd = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/bannière-classique.pdf");
        $attachmentd = chunk_split(base64_encode($pdfdocd));



        $headers = "From: " . $by_email . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
        $headers .= "Cc: " . $vb . $eol;

        $body = "Content-Transfer-Encoding: 7bit" . $eol;
        $body .= "This is a MIME encoded message." . $eol; //had one more .$eol


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $message . $eol;


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;
        $body .= $attachment . $eol;



        if( in_array("Baniere", $join)){
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd . $eol;

        }


        if( in_array("Audience", $join)){
            $pdfdocd25 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Audience-Telecontact.pdf");
            $attachmentd25 = chunk_split(base64_encode($pdfdocd25));
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed25 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd25 . $eol;

        }

        if( in_array("Carte", $join) ){


            $pdfdocd1 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Carte-de-visite.pdf");
            $attachmentd1 = chunk_split(base64_encode($pdfdocd1));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed1 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd1 . $eol;

        }

        if( in_array("Espace", $join) ){


            $pdfdocd2 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Espace-promo.pdf");
            $attachmentd2 = chunk_split(base64_encode($pdfdocd2));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed2 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd2 . $eol;

        }

        if( in_array("Habillage", $join) ){



            $pdfdocd3 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Habillage.pdf");
            $attachmentd3 = chunk_split(base64_encode($pdfdocd3));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed3 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd3 . $eol;

        }


        if( in_array("Marques", $join) ){



            $pdfdocd4 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Marques.pdf");
            $attachmentd4 = chunk_split(base64_encode($pdfdocd4));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed4 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd4 . $eol;

        }
        if( in_array("Prestation", $join) ){



            $pdfdocd5 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Prestation.pdf");
            $attachmentd5 = chunk_split(base64_encode($pdfdocd5));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed5 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd5 . $eol;

        }
        if( in_array("Professionnels", $join)){



            $pdfdocd6 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Professionnels-du-jour.pdf");
            $attachmentd6 = chunk_split(base64_encode($pdfdocd6));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed6 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd6 . $eol;

        }

        if( in_array("Rubrique", $join) ){


            $pdfdocd7 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Rubrique.pdf");
            $attachmentd7 = chunk_split(base64_encode($pdfdocd7));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed7 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd7 . $eol;

        }


        if( in_array("Video", $join)){


            $pdfdocd8 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Video.pdf");
            $attachmentd8 = chunk_split(base64_encode($pdfdocd8));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed8 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd8 . $eol;

        }

        if( in_array("Vignette_vid", $join)){


            $pdfdocd9 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-classique.pdf");
            $attachmentd9 = chunk_split(base64_encode($pdfdocd9));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed9 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd9 . $eol;

        }

        if( in_array("Vignette_lo", $join)){



            $pdfdocd10 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-localité.pdf");
            $attachmentd10 = chunk_split(base64_encode($pdfdocd10));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed10 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd10 . $eol;

        }

        if( in_array("Vignette_th", $join) ){


            $pdfdocd11 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-thématique.pdf");
            $attachmentd11 = chunk_split(base64_encode($pdfdocd11));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed11 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd11 . $eol;

        }

        if( in_array("site_internet", $join) ){

            $pdfdocd12 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Site-contact.pdf");
            $attachmentd12 = chunk_split(base64_encode($pdfdocd12));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed12 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd12 . $eol;

        } if( in_array("pvi", $join) ){

        $pdfdocd13 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/PVI.pdf");
        $attachmentd13 = chunk_split(base64_encode($pdfdocd13));


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed13 . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;

        $body .= $attachmentd13 . $eol;

    }
        if( in_array("catalogue_ref", $join) ){

            $pdfdocd14 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Catalogue_ref.pdf");
            $attachmentd14 = chunk_split(base64_encode($pdfdocd14));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed14 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd14 . $eol;

        }
        /* if( in_array("reguelement", $join) ){

             $pdfdocd15 = file_get_contents("http://www.telecontact.ma/trouver/pdf/CONDITIONS-GENERALES_VENTE.pdf");
             $attachmentd15 = chunk_split(base64_encode($pdfdocd15));


             $body .= "--" . $separator . $eol;
             $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed15 . "\"" . $eol;
             $body .= "Content-Transfer-Encoding: base64" . $eol;
             $body .= "Content-Disposition: attachment" . $eol . $eol;

             $body .= $attachmentd15 . $eol;

         }*/
        if( in_array("profession", $join) ){

            $pdfdocd16 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Profession-libérale.pdf");
            $attachmentd16 = chunk_split(base64_encode($pdfdocd16));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed16 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd16 . $eol;

        }
        if( in_array("vignette_extensible", $join) ){

            $pdfdocd17 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-extensible.pdf");
            $attachmentd17 = chunk_split(base64_encode($pdfdocd17));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed17 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd17 . $eol;

        }
        if( in_array("audit", $join) ){

            $pdfdocd18 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Audit-de-site.pdf");
            $attachmentd18 = chunk_split(base64_encode($pdfdocd18));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed18 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd18 . $eol;

        }
        if( in_array("benniere_extensible", $join) ){

            $pdfdocd19 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Bannière-extensible.pdf");
            $attachmentd19 = chunk_split(base64_encode($pdfdocd19));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed19 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd19 . $eol;

        }
        if( in_array("Facebook-Ads", $join) ){

            $pdfdocd20 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Facebook-Ads.pdf");
            $attachmentd20 = chunk_split(base64_encode($pdfdocd20));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed20 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd20 . $eol;

        }
        if( in_array("film_interview", $join) ){

            $pdfdocd21 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Film-Interview.pdf");
            $attachmentd21 = chunk_split(base64_encode($pdfdocd21));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed21 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd21 . $eol;

        }
        if( in_array("Motion_Design", $join) ){

            $pdfdocd22 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Motion-Design.pdf");
            $attachmentd22 = chunk_split(base64_encode($pdfdocd22));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed22 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd22 . $eol;

        }
        if( in_array("Vidéo_corporate", $join) ){

            $pdfdocd23 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vidéo-corporate.pdf");
            $attachmentd23 = chunk_split(base64_encode($pdfdocd23));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed23 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd23 . $eol;

        }
        if( in_array("Vidéo_graphique", $join) ){

            $pdfdocd24 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vidéo-graphique.pdf");
            $attachmentd24 = chunk_split(base64_encode($pdfdocd24));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed24 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd24 . $eol;

        }



        $body .= "--" . $separator . "--";



        if($mail){
            $la = mail($to, utf8_decode($subject), utf8_decode($body), $headers);
            $la1 = mail($by_email, utf8_decode($subject), utf8_decode($body), $headers);
        }
        if ($la) {


        } else {


        }



        $em = $this->getDoctrine()->getManager();
        $date_tr=new \DateTime('now');
        $join = $request->request->get('join');
        $email = $request->request->get('email');
        $by_tr=$this->container->get('security.context')->getToken()->getUser();



        $session = $this ->getRequest()->getSession();
        $affichage = $session->get('affichage');

        if ($session->has('referencement'))
            $referencement = $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);

        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);
        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'new_ht'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,


                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );

        $ref =$session->get('ref');
        $con =$session->get('cont');
        $aff =$session->get('aff');
        /*$prof =$session->get('prof');*/
        $raison =$session->get('raison');
        $visbilite_header =$session->get('visbilite_header');

        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        $rs    = $raison['rs'];
        $cfirme= $raison['cfirme'];
        $civi  = $raison['civi'];
        $sign  = $raison['sign'];

        $proposotion= $visbilite_header['proposition'];
        $ordre  = $visbilite_header['ordre'];
        $bon_commande  = $visbilite_header['bon_commande'];

        $entity= new Mails();
        $entity->setCfirme($cfirme);
        $entity->setRs($rs);
        $entity->setAffichage($affichage);
        $entity->setReferencement($referencement);
        $entity->setContenu($panier);
        $entity->setPaiement($paiement);
        $entity->setProfession($profession);
        $entity->setProfession($profession);
        $entity->setDesrubref($desrubref);
        $entity->setCode($code);
        $entity->setMarque($marque);
        $entity->setResultat($result);
        $entity->setDateCreation($date_tr);
        $entity->setUtilisateur($by_tr);
        $entity->setCivi($civi);
        $entity->setSign($sign);
        $entity->setEmail($email);
        $entity->setJoin($join);
        $entity->setProposition($proposotion);
        $entity->setOrdre($ordre);
        $entity->setBonCommande($bon_commande);
        $entity->setProfession($profession);

        $em->persist($entity);
        $em->flush();





        return new Response(json_encode($result), 200);


    }
    public function maildevisAction(Request $request)
    {


        $mail = $request->request->get('email');
        $join = $request->request->get('join');
        $text='Bonjour <br><br>Je vous prie de trouver en pièce jointe le détail de vos insertions dans telecontact.ma <br><br>
 Je reste bien évidemment à votre disposition pour tout complément d\'information.<br><br>
 En attendant votre retour, veuillez agréer nos sincères salutations <br><br>
 Bien cordialement';
        $by_email=$this->container->get('security.context')->getToken()->getUser()->getEmail();

        $session = $this ->getRequest()->getSession();

        $affichage =$session->get('affichage');


        if ( $session->has('referencement'))
            $referencement =  $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);


        if ($session->has('contenu'))
            $contenu = $session->get('contenu');
        else
            $contenu = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);

        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'new_ht'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );

        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,
                    'package'=>NULL,
                    'package_value'=>0,
                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );


        if ($session->has('visbilite_header'))
            $visbilite_header = $session->get('visbilite_header');
        else
            $visbilite_header =
                array(
                    'visbilite_header'=>null,

                );


        $ref =$session->get('ref');
        $con = $session->get('cont');
        $aff = $session->get('aff');
        /*$prof = $session->get('prof');*/
        /*$raison =$session->get('raison');*/
        if($session->has('raison'))
            $raison =$session->get('raison');
        else
            $raison =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );



        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);



        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactsec.html.twig', array('raison'=> $raison,'referencement'=> $referencement, 'affichage'=> $affichage,'contenu'=>$contenu,'somme'=>$result,'paiement'=>$paiement,'profession'=>$profession,'desrubref'=>$desrubref,'marque'=>$marque,'code'=>$code,'visbilite_header'=>$visbilite_header,'by_code'=> 1));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','letter','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        $to = $mail;
        $from      = "e-contact@telecontact.ma";
        $vb        ="f.anouar@edicom.ma";
        $subject   = "Mail Envoyée E-contact";
        $message   = "<p>.$text.</p>";
        $separator = md5(time());
        $eol       = PHP_EOL;



        $filename   = "Ordre d'insertion.pdf";




        $filenamed   =   "bannière-classique.pdf";
        $filenamed1   =  "Carte-de-visite.pdf";
        $filenamed2   =  "Espace-promo.pdf";
        $filenamed3   =  "Habillage.pdf";
        $filenamed4   =  "Marques.pdf";
        $filenamed5   =  "Prestation.pdf";
        $filenamed6   =  "Professionnels-du-jour.pdf";
        $filenamed7  =   "Rubrique.pdf";
        $filenamed8  =   "Video.pdf";
        $filenamed9  =   "Vignette-classique.pdf";
        $filenamed10 =   "Vignette-localité.pdf";
        $filenamed11  =  "Vignette-thématique.pdf";
        $filenamed12  =  "Site-contact.pdf";
        $filenamed13  =  "PVI.pdf";
        $filenamed14  =  "Catalogue_ref.pdf";
        $filenamed15  =  "CONDITIONS-GENERALES_VENTE.pdf";
        $filenamed16  =  "Profession-libérale.pdf";
        $filenamed17  =  "Vignette-extensible.pdf";
        $filenamed18  =  "Audit-de-site.pdf";
        $filenamed19  =  "Bannière-extensible.pdf";
        $filenamed20  =  "Facebook-Ads.pdf";
        $filenamed21  =  "Film-Interview.pdf";
        $filenamed22  =  "Motion-Design.pdf";
        $filenamed23  =  "Vidéo-corporate.pdf";
        $filenamed24  =  "Vidéo-graphique.pdf";
        $filenamed25  =  "Audience-Telecontact.pdf";




        $pdfdoc     = $html2pdf->Output('Proposition.pdf', 'S');





        $attachment = chunk_split(base64_encode($pdfdoc));

        $pdfdocd = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/bannière-classique.pdf");
        $attachmentd = chunk_split(base64_encode($pdfdocd));



        $headers = "From: " . $by_email . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
        $headers .= "Cc: " . $vb . $eol;

        $body = "Content-Transfer-Encoding: 7bit" . $eol;
        $body .= "This is a MIME encoded message." . $eol; //had one more .$eol


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $message . $eol;


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;
        $body .= $attachment . $eol;



        if( in_array("Baniere", $join)){
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd . $eol;

        }



        if( in_array("Audience", $join)){
            $pdfdocd25 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Audience-Telecontact.pdf");
            $attachmentd25 = chunk_split(base64_encode($pdfdocd25));
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed25 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd25 . $eol;

        }



        if( in_array("Carte", $join) ){


            $pdfdocd1 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Carte-de-visite.pdf");
            $attachmentd1 = chunk_split(base64_encode($pdfdocd1));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed1 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd1 . $eol;

        }

        if( in_array("Espace", $join) ){


            $pdfdocd2 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Espace-promo.pdf");
            $attachmentd2 = chunk_split(base64_encode($pdfdocd2));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed2 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd2 . $eol;

        }

        if( in_array("Habillage", $join) ){



            $pdfdocd3 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Habillage.pdf");
            $attachmentd3 = chunk_split(base64_encode($pdfdocd3));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed3 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd3 . $eol;

        }


        if( in_array("Marques", $join) ){



            $pdfdocd4 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Marques.pdf");
            $attachmentd4 = chunk_split(base64_encode($pdfdocd4));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed4 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd4 . $eol;

        }
        if( in_array("Prestation", $join) ){



            $pdfdocd5 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Prestation.pdf");
            $attachmentd5 = chunk_split(base64_encode($pdfdocd5));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed5 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd5 . $eol;

        }
        if( in_array("Professionnels", $join)){



            $pdfdocd6 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Professionnels-du-jour.pdf");
            $attachmentd6 = chunk_split(base64_encode($pdfdocd6));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed6 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd6 . $eol;

        }

        if( in_array("Rubrique", $join) ){


            $pdfdocd7 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Rubrique.pdf");
            $attachmentd7 = chunk_split(base64_encode($pdfdocd7));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed7 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd7 . $eol;

        }


        if( in_array("Video", $join)){


            $pdfdocd8 = file_get_contents("http://www.telecontact.ma/trouver/pdf/Video.pdf");
            $attachmentd8 = chunk_split(base64_encode($pdfdocd8));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed8 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd8 . $eol;

        }

        if( in_array("Vignette_vid", $join)){


            $pdfdocd9 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-classique.pdf");
            $attachmentd9 = chunk_split(base64_encode($pdfdocd9));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed9 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd9 . $eol;

        }

        if( in_array("Vignette_lo", $join)){



            $pdfdocd10 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-localité.pdf");
            $attachmentd10 = chunk_split(base64_encode($pdfdocd10));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed10 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd10 . $eol;

        }

        if( in_array("Vignette_th", $join) ){


            $pdfdocd11 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-thématique.pdf");
            $attachmentd11 = chunk_split(base64_encode($pdfdocd11));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed11 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd11 . $eol;

        }

        if( in_array("site_internet", $join) ){

            $pdfdocd12 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Site-contact.pdf");
            $attachmentd12 = chunk_split(base64_encode($pdfdocd12));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed12 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd12 . $eol;

        } if( in_array("pvi", $join) ){

        $pdfdocd13 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/PVI.pdf");
        $attachmentd13 = chunk_split(base64_encode($pdfdocd13));


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed13 . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;

        $body .= $attachmentd13 . $eol;

    }
        if( in_array("catalogue_ref", $join) ){

            $pdfdocd14 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Catalogue_ref.pdf");
            $attachmentd14 = chunk_split(base64_encode($pdfdocd14));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed14 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd14 . $eol;

        }
        /* if( in_array("reguelement", $join) ){

             $pdfdocd15 = file_get_contents("http://www.telecontact.ma/trouver/pdf/CONDITIONS-GENERALES_VENTE.pdf");
             $attachmentd15 = chunk_split(base64_encode($pdfdocd15));


             $body .= "--" . $separator . $eol;
             $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed15 . "\"" . $eol;
             $body .= "Content-Transfer-Encoding: base64" . $eol;
             $body .= "Content-Disposition: attachment" . $eol . $eol;

             $body .= $attachmentd15 . $eol;

         }*/
        if( in_array("profession", $join) ){

            $pdfdocd16 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Profession-libérale.pdf");
            $attachmentd16 = chunk_split(base64_encode($pdfdocd16));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed16 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd16 . $eol;

        }
        if( in_array("vignette_extensible", $join) ){

            $pdfdocd17 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vignette-extensible.pdf");
            $attachmentd17 = chunk_split(base64_encode($pdfdocd17));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed17 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd17 . $eol;

        }
        if( in_array("audit", $join) ){

            $pdfdocd18 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Audit-de-site.pdf");
            $attachmentd18 = chunk_split(base64_encode($pdfdocd18));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed18 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd18 . $eol;

        }
        if( in_array("benniere_extensible", $join) ){

            $pdfdocd19 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Bannière-extensible.pdf");
            $attachmentd19 = chunk_split(base64_encode($pdfdocd19));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed19 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd19 . $eol;

        }
        if( in_array("Facebook-Ads", $join) ){

            $pdfdocd20 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Facebook-Ads.pdf");
            $attachmentd20 = chunk_split(base64_encode($pdfdocd20));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed20 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd20 . $eol;

        }
        if( in_array("film_interview", $join) ){

            $pdfdocd21 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Film-Interview.pdf");
            $attachmentd21 = chunk_split(base64_encode($pdfdocd21));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed21 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd21 . $eol;

        }
        if( in_array("Motion_Design", $join) ){

            $pdfdocd22 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Motion-Design.pdf");
            $attachmentd22 = chunk_split(base64_encode($pdfdocd22));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed22 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd22 . $eol;

        }
        if( in_array("Vidéo_corporate", $join) ){

            $pdfdocd23 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vidéo-corporate.pdf");
            $attachmentd23 = chunk_split(base64_encode($pdfdocd23));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed23 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd23 . $eol;

        }
        if( in_array("Vidéo_graphique", $join) ){

            $pdfdocd24 = file_get_contents("http://www.telecontact.ma/trouver/pdf_new/Vidéo-graphique.pdf");
            $attachmentd24 = chunk_split(base64_encode($pdfdocd24));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed24 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd24 . $eol;

        }






        $body .= "--" . $separator . "--";



        if($mail){
            $la = mail($to, utf8_decode($subject), utf8_decode($body), $headers);
            $la1 = mail($by_email, utf8_decode($subject), utf8_decode($body), $headers);
        }
        if ($la) {


        } else {


        }



        $em = $this->getDoctrine()->getManager();
        $date_tr=new \DateTime('now');
        $join = $request->request->get('join');
        $email = $request->request->get('email');
        $by_tr=$this->container->get('security.context')->getToken()->getUser();



        $session = $this ->getRequest()->getSession();
        $affichage = $session->get('affichage');

        if ($session->has('referencement'))
            $referencement = $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);

        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);
        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'new_ht'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );

        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,




                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );

        $ref =$session->get('ref');
        $con =$session->get('cont');
        $aff =$session->get('aff');
        /*$prof =$session->get('prof');*/
        $raison =$session->get('raison');
        $visbilite_header =$session->get('visbilite_header');



        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        $rs    = $raison['rs'];
        $cfirme= $raison['cfirme'];
        $civi  = $raison['civi'];
        $sign  = $raison['sign'];
        $proposotion= $visbilite_header['proposition'];
        $ordre  = $visbilite_header['ordre'];
        $bon_commande  = $visbilite_header['bon_commande'];



        $entity= new Mails();
        $entity->setCfirme($cfirme);
        $entity->setRs($rs);
        $entity->setAffichage($affichage);
        $entity->setReferencement($referencement);
        $entity->setContenu($panier);
        $entity->setPaiement($paiement);
        $entity->setProfession($profession);
        $entity->setProfession($profession);
        $entity->setDesrubref($desrubref);
        $entity->setCode($code);
        $entity->setMarque($marque);
        $entity->setResultat($result);
        $entity->setDateCreation($date_tr);
        $entity->setUtilisateur($by_tr);
        $entity->setCivi($civi);
        $entity->setSign($sign);
        $entity->setEmail($email);
        $entity->setJoin($join);
        $entity->setProposition($proposotion);
        $entity->setOrdre($ordre);
        $entity->setBonCommande($bon_commande);
        $entity->setProfession($profession);

        $em->persist($entity);

        $em->flush();





        return new Response(json_encode($result), 200);


    }
    public function mailboncommandeAction(Request $request)
    {
      
        $em = $this->getDoctrine()->getManager();
        $mail = $request->request->get('email');
        $join = $request->request->get('join');
        $text='Bonjour <br><br>Je vous prie de trouver en pièce jointe le Bon de commande pour vos parutions dans telecontact.ma <br><br>
 Je reste bien évidemment à votre disposition pour tout complément d\'information.<br><br>
 En attendant votre retour, veuillez agréer nos sincères salutations <br><br>
 Bien cordialement';
        $by_email=$this->container->get('security.context')->getToken()->getUser()->getEmail();
        $by_code=$this->container->get('security.context')->getToken()->getUser()->getCode();
        $session = $this ->getRequest()->getSession();

        $affichage =$session->get('affichage');

        if ( $session->has('referencement'))
            $referencement =  $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);


        if ($session->has('contenu'))
            $contenu = $session->get('contenu');
        else
            $contenu = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);

        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'new_ht'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );
        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,
                    'package'=>NULL,
                    'package_value'=>0,
                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );


        if ($session->has('visbilite_header'))
            $visbilite_header = $session->get('visbilite_header');
        else
            $visbilite_header =
                array(
                    'visbilite_header'=>null,

                );


        $ref =$session->get('ref');
        $con = $session->get('cont');
        $aff = $session->get('aff');
        /*$prof = $session->get('prof');*/
        /*$raison =$session->get('raison');*/
        if($session->has('raison'))
            $raison =$session->get('raison');
        else
            $raison =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );

            $lastQuestion = $em->getRepository('EcommerceBundle:Mails')->findOneBy(  array('bon_commande'=>'bon_commande'),  array('id' => 'DESC'));
            $lastId = $lastQuestion->getNumbc();
            $lastId = $lastId + 1 ;
       

        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);



        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactsec.html.twig', array('raison'=> $raison,'referencement'=> $referencement, 'affichage'=> $affichage,'contenu'=>$contenu,'somme'=>$result,'paiement'=>$paiement,'profession'=>$profession,'desrubref'=>$desrubref,'marque'=>$marque,'code'=>$code,'visbilite_header'=>$visbilite_header,'lastId'=>$lastId, 'by_code'=> $by_code));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','letter','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        $to = $mail;
        $from      = "e-contact@telecontact.ma";
        $vb        ="f.anouar@edicom.ma";
        $subject   = "Mail Envoyée E-contact";
        $message   = "<p>.$text.</p>";
        $separator = md5(time());
        $eol       = PHP_EOL;
        $visbilite_header     = $request->request->get('visbilite_header');


        $filename   = "Bon de commande.pdf";




        $filenamed   =   "bannière-classique.pdf";
        $filenamed1   =  "Carte-de-visite.pdf";
        $filenamed2   =  "Espace-promo.pdf";
        $filenamed3   =  "Habillage.pdf";
        $filenamed4   =  "Marques.pdf";
        $filenamed5   =  "Prestation.pdf";
        $filenamed6   =  "Professionnels-du-jour.pdf";
        $filenamed7  =   "Rubrique.pdf";
        $filenamed8  =   "Video.pdf";
        $filenamed9  =   "Vignette-classique.pdf";
        $filenamed10 =   "Vignette-localité.pdf";
        $filenamed11  =  "Vignette-thématique.pdf";
        $filenamed12  =  "Site-contact.pdf";
        $filenamed13  =  "PVI.pdf";
        $filenamed14  =  "Catalogue_ref.pdf";
        $filenamed15  =  "CONDITIONS-GENERALES_VENTE.pdf";
        $filenamed16  =  "Profession-libérale.pdf";
        $filenamed17  =  "Vignette-extensible.pdf";
        $filenamed18  =  "Audit-de-site.pdf";
        $filenamed19  =  "Bannière-extensible.pdf";
        $filenamed20  =  "Facebook-Ads.pdf";
        $filenamed21  =  "Film-Interview.pdf";
        $filenamed22  =  "Motion-Design.pdf";
        $filenamed23  =  "Vidéo-corporate.pdf";
        $filenamed24  =  "Vidéo-graphique.pdf";
        $filenamed25  =  "Audience-Telecontact.pdf";



        $pdfdoc     = $html2pdf->Output('Proposition.pdf', 'S');





        $attachment = chunk_split(base64_encode($pdfdoc));

        $pdfdocd = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/bannière-classique.pdf");
        $attachmentd = chunk_split(base64_encode($pdfdocd));



        $headers = "From: " . $by_email . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
        $headers .= "Cc: " . $vb . $eol;

        $body = "Content-Transfer-Encoding: 7bit" . $eol;
        $body .= "This is a MIME encoded message." . $eol; //had one more .$eol


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $message . $eol;


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;
        $body .= $attachment . $eol;



        if( in_array("Baniere", $join)){
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd . $eol;

        }
        if( in_array("Audience", $join)){
            $pdfdocd25 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Audience-Telecontact.pdf");
            $attachmentd25 = chunk_split(base64_encode($pdfdocd25));
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed25 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd25 . $eol;

        }

        if( in_array("Carte", $join) ){


            $pdfdocd1 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Carte-de-visite.pdf");
            $attachmentd1 = chunk_split(base64_encode($pdfdocd1));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed1 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd1 . $eol;

        }

        if( in_array("Espace", $join) ){


            $pdfdocd2 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Espace-promo.pdf");
            $attachmentd2 = chunk_split(base64_encode($pdfdocd2));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed2 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd2 . $eol;

        }

        if( in_array("Habillage", $join) ){



            $pdfdocd3 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Habillage.pdf");
            $attachmentd3 = chunk_split(base64_encode($pdfdocd3));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed3 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd3 . $eol;

        }


        if( in_array("Marques", $join) ){



            $pdfdocd4 = file_get_contents("https://www.telecontact.ma/trouver/pdf/Marques.pdf");
            $attachmentd4 = chunk_split(base64_encode($pdfdocd4));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed4 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd4 . $eol;

        }
        if( in_array("Prestation", $join) ){



            $pdfdocd5 = file_get_contents("https://www.telecontact.ma/trouver/pdf/Prestation.pdf");
            $attachmentd5 = chunk_split(base64_encode($pdfdocd5));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed5 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd5 . $eol;

        }
        if( in_array("Professionnels", $join)){



            $pdfdocd6 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Professionnels-du-jour.pdf");
            $attachmentd6 = chunk_split(base64_encode($pdfdocd6));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed6 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd6 . $eol;

        }

        if( in_array("Rubrique", $join) ){


            $pdfdocd7 = file_get_contents("https://www.telecontact.ma/trouver/pdf/Rubrique.pdf");
            $attachmentd7 = chunk_split(base64_encode($pdfdocd7));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed7 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd7 . $eol;

        }


        if( in_array("Video", $join)){


            $pdfdocd8 = file_get_contents("https://www.telecontact.ma/trouver/pdf/Video.pdf");
            $attachmentd8 = chunk_split(base64_encode($pdfdocd8));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed8 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd8 . $eol;

        }

        if( in_array("Vignette_vid", $join)){


            $pdfdocd9 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Vignette-classique.pdf");
            $attachmentd9 = chunk_split(base64_encode($pdfdocd9));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed9 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd9 . $eol;

        }

        if( in_array("Vignette_lo", $join)){



            $pdfdocd10 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Vignette-localité.pdf");
            $attachmentd10 = chunk_split(base64_encode($pdfdocd10));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed10 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd10 . $eol;

        }

        if( in_array("Vignette_th", $join) ){


            $pdfdocd11 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Vignette-thématique.pdf");
            $attachmentd11 = chunk_split(base64_encode($pdfdocd11));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed11 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd11 . $eol;

        }

        if( in_array("site_internet", $join) ){

            $pdfdocd12 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Site-contact.pdf");
            $attachmentd12 = chunk_split(base64_encode($pdfdocd12));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed12 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd12 . $eol;

        } if( in_array("pvi", $join) ){

        $pdfdocd13 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/PVI.pdf");
        $attachmentd13 = chunk_split(base64_encode($pdfdocd13));


        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed13 . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;

        $body .= $attachmentd13 . $eol;

    }
        if( in_array("catalogue_ref", $join) ){

            $pdfdocd14 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Catalogue_ref.pdf");
            $attachmentd14 = chunk_split(base64_encode($pdfdocd14));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed14 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd14 . $eol;

        }
         if( in_array("reguelement", $join) ){

             $pdfdocd15 = file_get_contents("https://www.telecontact.ma/trouver/pdf/CONDITIONS-GENERALES_VENTE.pdf");
             $attachmentd15 = chunk_split(base64_encode($pdfdocd15));


             $body .= "--" . $separator . $eol;
             $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed15 . "\"" . $eol;
             $body .= "Content-Transfer-Encoding: base64" . $eol;
             $body .= "Content-Disposition: attachment" . $eol . $eol;

             $body .= $attachmentd15 . $eol;

         }
        if( in_array("profession", $join) ){

            $pdfdocd16 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Profession-libérale.pdf");
            $attachmentd16 = chunk_split(base64_encode($pdfdocd16));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed16 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd16 . $eol;

        }
        if( in_array("vignette_extensible", $join) ){

            $pdfdocd17 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Vignette-extensible.pdf");
            $attachmentd17 = chunk_split(base64_encode($pdfdocd17));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed17 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd17 . $eol;

        }
        if( in_array("audit", $join) ){

            $pdfdocd18 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Audit-de-site.pdf");
            $attachmentd18 = chunk_split(base64_encode($pdfdocd18));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed18 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd18 . $eol;

        }
        if( in_array("benniere_extensible", $join) ){

            $pdfdocd19 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Bannière-extensible.pdf");
            $attachmentd19 = chunk_split(base64_encode($pdfdocd19));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed19 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd19 . $eol;

        }
        if( in_array("Facebook-Ads", $join) ){

            $pdfdocd20 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Facebook-Ads.pdf");
            $attachmentd20 = chunk_split(base64_encode($pdfdocd20));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed20 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd20 . $eol;

        }
        if( in_array("film_interview", $join) ){

            $pdfdocd21 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Film-Interview.pdf");
            $attachmentd21 = chunk_split(base64_encode($pdfdocd21));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed21 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd21 . $eol;

        }
        if( in_array("Motion_Design", $join) ){

            $pdfdocd22 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Motion-Design.pdf");
            $attachmentd22 = chunk_split(base64_encode($pdfdocd22));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed22 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd22 . $eol;

        }
        if( in_array("Vidéo_corporate", $join) ){

            $pdfdocd23 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Vidéo-corporate.pdf");
            $attachmentd23 = chunk_split(base64_encode($pdfdocd23));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed23 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd23 . $eol;

        }
        if( in_array("Vidéo_graphique", $join) ){

            $pdfdocd24 = file_get_contents("https://www.telecontact.ma/trouver/pdf_new/Vidéo-graphique.pdf");
            $attachmentd24 = chunk_split(base64_encode($pdfdocd24));


            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filenamed24 . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;

            $body .= $attachmentd24 . $eol;

        }



        $body .= "--" . $separator . "--";



        if($mail){
            $la = mail($to, utf8_decode($subject), utf8_decode($body), $headers);
            $la1 = mail($by_email, utf8_decode($subject), utf8_decode($body), $headers);
        }
        if ($la) {


        } else {


        }



        $em = $this->getDoctrine()->getManager();
        $date_tr=new \DateTime('now');
        $join = $request->request->get('join');
        $email = $request->request->get('email');
        $by_tr=$this->container->get('security.context')->getToken()->getUser();



        $session = $this ->getRequest()->getSession();
        $affichage = $session->get('affichage');

        if ($session->has('referencement'))
            $referencement = $session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);

        if ($session->has('contenu'))
            $panier = $session->get('contenu');
        else
            $panier = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);
        if ($session->has('paiement'))
            $paiement = $session->get('paiement');
        else
            $paiement = array(
                'montantttc'=>NULL,
                'new_ht'=>NULL,
                'accompte'=>NULL,
                'reste'=> NULL,
                'nbr'=>NULL,
                'montant1'=>NULL,
                'montant2'=>NULL,
                'montant3'=>NULL,
                'montant4'=>NULL,
                'montant5'=>NULL,
                'dateP1'=>NULL,
                'dateP2'=>NULL,
                'dateP3'=>NULL,
                'dateP4'=>NULL,
                'dateP5'=>NULL,
            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>null,
                    'profession'=>null,
                    'prix'=>null,
                    'parcours_diplomes'=>null,
                    'specialites'=>null,
                    'services'=>null,
                    'rubrique'=>null,
                    'certifications'=>null,
                    'first_day'=>null,
                    'second_day'=>null,
                    'hour1'=>null,
                    'hour2'=>null,
                    'hour3'=>null,
                    'hour4'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,
                    'villes1'=>NULL,
                    'villes2'=>NULL,
                    'villes3'=>NULL,
                    'villes4'=>NULL,
                    'villes5'=>NULL,
                    'villes6'=>NULL,
                    'villes7'=>NULL,
                    'villes_panier1'=>NULL,
                    'villes_panier2'=>NULL,
                    'villes_panier3'=>NULL,
                    'villes_panier4'=>NULL,
                    'villes_panier5'=>NULL,
                    'villes_panier6'=>NULL,
                    'villes_panier7'=>NULL,

                    'rub_supp_1'=>0,
                    'rub_supp_2'=>0,
                    'rub_supp_3'=>0,
                    'rub_supp_4'=>0,
                    'rub_supp_5'=>0,
                    'rub_supp_6'=>0,
                    'rub_supp_7'=>0,

                    'localiter_supp'=>0,
                    'localiter_supp2'=>0,
                    'localiter_supp3'=>0,
                    'localiter_supp4'=>0,
                    'localiter_supp5'=>0,
                    'localiter_supp6'=>0,
                    'localiter_supp7'=>0,
                    'localiter_supp1'=>0,

                    'final'=>0,
                    'fayssal'=>0,




                );

        if ($session->has('code'))
            $code = $session->get('code');

        else
            $code =
                array(
                    'nature_remise'=>NULL,
                    'montant_remise'=>NULL,
                    'offre'=>NULL,
                    'pourcentage'=>NULL,
                    'montant_r'=>NULL,
                    'montant_rem'=>NULL,

                );


        if ($session->has('marque'))
            $marque = $session->get('marque');
        else
            $marque =
                array(

                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
                    'marq10'=>null,
                    'positionnement'=>null,
                    'positionnement1'=>null,
                    'positionnement2'=>null,
                    'positionnement3'=>null,
                    'positionnement4'=>null,
                    'positionnement5'=>null,
                    'positionnement6'=>null,
                    'positionnement7'=>null,
                    'positionnement8'=>null,
                    'positionnement9'=>null,
                );

        $ref =$session->get('ref');
        $con =$session->get('cont');
        $aff =$session->get('aff');
        /*$prof =$session->get('prof');*/
        $raison =$session->get('raison');
        $visbilite_header =$session->get('visbilite_header');


        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        $rs    = $raison['rs'];
        $cfirme= $raison['cfirme'];
        $civi  = $raison['civi'];
        $sign  = $raison['sign'];
        $proposotion= $visbilite_header['proposition'];
        $ordre  = $visbilite_header['ordre'];
        $bon_commande  = $visbilite_header['bon_commande'];



        $entity= new Mails();
        $entity->setCfirme($cfirme);
        $entity->setRs($rs);
        $entity->setAffichage($affichage);
        $entity->setReferencement($referencement);
        $entity->setContenu($panier);
        $entity->setPaiement($paiement);
        $entity->setProfession($profession);
        $entity->setProfession($profession);
        $entity->setDesrubref($desrubref);
        $entity->setCode($code);
        $entity->setMarque($marque);
        $entity->setResultat($result);
        $entity->setDateCreation($date_tr);
        $entity->setUtilisateur($by_tr);
        $entity->setCivi($civi);
        $entity->setSign($sign);
        $entity->setEmail($email);
        $entity->setJoin($join);
        $entity->setProposition($proposotion);
        $entity->setOrdre($ordre);
        $entity->setBonCommande($bon_commande);
        $entity->setProfession($profession);
        $entity->setNumbc($lastId);        
        $em->persist($entity);

        $em->flush();





        return new Response(json_encode($result), 200);


    }

    public function referencementAction()
    {
        $session = $this->getRequest()->getSession();
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

        if ($session->has('referencement'))
            $panier = $session->get('referencement');
        else
            $panier =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'marque_pack'=> NULL,
                'resulta'=> NULL,
                'resulta2'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL,
                'rub1'=>NULL,
                'rub2'=>NULL,
                'rub3'=>NULL,
                'rub4'=>NULL,
                'rub5'=>NULL,
                'rub6'=>NULL,
                'rub7'=>NULL,
                'sel'=>NULL,
                'sel1'=>NULL,

            );
        if ($session->has('profession'))
            $profession = $session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>NULL,
                    'profession'=>NULL,
                    'prix'=>NULL,

                );

        if ($session->has('desrubref'))
            $desrubref = $session->get('desrubref');

        else
            $desrubref =
                array(
                    'rub1'=>NULL,
                    'rub2'=>NULL,
                    'rub3'=>NULL,
                    'rub4'=>NULL,
                    'rub5'=>NULL,
                    'rub6'=>NULL,
                    'rub7'=>NULL,
                    'prest1'=>NULL,
                    'prest2'=>NULL,
                    'prest3'=>NULL,
                    'prest4'=>NULL,
                    'prest5'=>NULL,
                    'prest6'=>NULL,
                    'prest7'=>NULL,
                    'addprest1'=>NULL,
                    'addprest2'=>NULL,
                    'addprest3'=>NULL,
                    'addprest4'=>NULL,
                    'addprest5'=>NULL,
                    'addprest6'=>NULL,
                    'addprest7'=>NULL,
                    'prestsupp'=>NULL,
                    'r_count'=>NULL,


                );

        if ($session->has('marque'))
            $marque = $session->get('marque');

        else
            $marque =
                array(
                    'marq1'=>NULL,
                    'marq2'=>NULL,
                    'marq3'=>NULL,
                    'marq4'=>NULL,
                    'marq5'=>NULL,
                    'marq6'=>NULL,
                    'marq7'=>NULL,
                    'marq8'=>NULL,
                    'marq9'=>NULL,
                    'marq10'=>NULL,
                    'posi1'=>NULL,
                    'posi2'=>NULL,
                    'posi3'=>NULL,
                    'posi4'=>NULL,
                    'posi5'=>NULL,
                    'posi6'=>NULL,
                    'posi7'=>NULL,
                    'posi8'=>NULL,
                    'posi9'=>NULL,
                    'posi10'=>NULL,


                );
        /*if ($session->has('rasion'))
            $rasion = $session->get('rasion');
        else
            $rasion =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );*/
        $raison =$session->get('raison');
        $villes     =     $em->getRepository('EcommerceBundle:Ville')->findBy(array(), array('libelle' => 'asc'));
        $rubriques  =     $em->getRepository('EcommerceBundle:Rubrique')->findAll();
      /*$prestation =     $em->getRepository('EcommerceBundle:Prestation')->getpresrt();*/
        $entities   =     $em->getRepository('EcommerceBundle:Region')->findBy(array(), array('libelle' => 'asc'));

        return $this->render('EcommerceBundle:Default:referencement/modulesUsed/referencement.html.twig',
            array(
                'rubriques' => $rubriques,
                /*'pres' => $prestation,*/
                'entities' => $entities,
                'villes' => $villes,
                'referencement' => $panier,
                'raison' => $raison,
                'profession'=>$profession,
                'desrubref'=>$desrubref,
                'marque'=>$marque,
                /* 'raison'=>$rasion,*/

            ));


    }

    public function referencementdevisAction()
    {

        $session = $this->getRequest()->getSession();

        if (!$session->has('referencement')) $session->set('referencement',array());
        $referencement = $session->get('referencement');

        $em = $this->getDoctrine()->getManager();


        $rubriques = $em->getRepository('EcommerceBundle:Rubrique')->findAll();
        $villes = $em->getRepository('EcommerceBundle:Ville')->findAll();
        $entities = $em->getRepository('EcommerceBundle:Region')->findAll();

        $session->set('referencement',$referencement);
        return $this->render('EcommerceBundle:Default:referencement/modulesUsed/referencementdevis.html.twig', array('rubriques' => $rubriques,
            'entities' => $entities,
            'villes' => $villes,

        ));

    }

    public function ajaxAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $prestations = $request->request->get('rubrique');

        $query = $em->createQuery('SELECT p FROM Ecommerce\EcommerceBundle\Entity\Prestation p WHERE p.rub=:rub');
        $query->setParameter('rub', $prestations);
        $result = $query->getArrayResult();

        return new Response(json_encode($result), 200);

    }

    public function ajaxsendAction(Request $request)
    {

        $villes = intval($request->request->get('villes'));
        $villes_sup = $request->request->get('radio1');

        $regions = $request->request->get('regions');
        $regions_sup = $request->request->get('radio');
        $rubrique = intval($request->request->get('num1'));
        $prest = intval($request->request->get('sum'));
        $prest_sup = intval($request->request->get('num2'));
        $marque = intval($request->request->get('num3'));



        $sum3 = intval($request->request->get('sum3'));
        $mari = intval($request->request->get('mari'));
        $marque_pack = intval($request->request->get('marque_pack'));
        $resulta = intval($request->request->get('resulta'));
        $resulta2 = intval($request->request->get('resulta2'));
        $rubd = intval($request->request->get('rubd'));
        $rania = intval($request->request->get('rania'));
        $raniad = intval($request->request->get('raniad'));


        $result =array('rubrique'=>$rubrique,'prest'=>$prest,'prest_sup'=> $prest_sup,'marque'=>$marque,'sum3'=>$sum3 ,'mari'=> $mari,'marque_pack'=> $marque_pack,'resulta'=> $resulta,'resulta2'=> $resulta2,'rubd'=> $rubd,'rania'=> $rania,'raniad'=> $raniad,'villes'=>$villes,'regions'=>$regions,'villes_sup'=>$villes_sup,'regions_sup'=>$regions_sup);




        $this->get('session')->set('referencement', $result);

        return new Response(json_encode($result), 200);


    }

    public function refcomAction(Request $request)
    {

        $session = $this->getRequest()->getSession();

        $panier = $session->get('referencement');
        print_r($panier);
        die('ici');


        $em = $this->getDoctrine()->getManager();
        $marque = $request->request->get('marque');
        $rubrique = $request->request->get('sel');
        $prest = $request->request->get('prest');
        $activite = $request->request->get('activite');
        $sup = $request->request->get('sup');
        $villes = $request->request->get('villes');
        $regions = $request->request->get('regions');
        $villes_sup = $request->request->get('chk2');
        $regions_sup = $request->request->get('chk1');

        $r_count = $request->request->get('r_count');
        $m_count = $request->request->get('m_count');






        $var=array();
        for ($i = 0; $i <= $r_count; $i++) {

            if ($i==0)
            {
                $var[0]['rubrique']= $request->request->get('sel');

                $prestastion='';
                if ($request->request->get('prest')){
                    foreach ($request->request->get('prest') as $names)
                    {
                        if(empty($prestastion)){
                            $prestastion .=$names ;
                        }
                        else{
                            $prestastion .=','.$names ;

                        }
                    }
                }

                $var[0]['prestation']=$prestastion;
            }
            else{
                $var[$i]['rubrique']= $request->request->get('sel'.$i);

                $presta='';
                if ($request->request->get('prest'.$i)){
                    foreach ($request->request->get('prest'.$i) as $namess)
                    {

                        if(empty($presta)){

                            $presta .=$namess ;

                        }
                        else{
                            $presta .=','.$namess ;

                        }
                    }
                }


                $var[$i]['prestation']=$presta;

            }
        }


        $mub= array();
        for ($j = 0; $j <= $m_count; $j++) {

            if ($j==0){
                $mub[0]['marque']= $request->request->get('marque');
            }
            else{
                $mub[$j]['marque']= $request->request->get('marque'.$j);

            }

        }


        $result =array('rubrique'=>$var,'r_count'=>$r_count,'m_count'=> $m_count,'marque'=>$mub,'activite'=>$activite ,'rub_sup'=> $sup,'villes'=>$villes,'regions'=>$regions,'villes_sup'=>$villes_sup,'regions_sup'=>$regions_sup);

        $this->get('session')->set('referencement', $result);

        return new Response(json_encode($result), 200);


    }

    public function contenuAction(Request $request)
    {

        $catalogue = $request->request->get('catalogue');
        $catalogue_ref = $request->request->get('catalogue_ref');
        $video = $request->request->get('video');
        $page = $request->request->get('page');
        $site_web = $request->request->get('site_web');

        $result =array('catalogue'=>$catalogue,'catalogue_ref'=>$catalogue_ref,'video'=> $video,'page'=>$page,'site_web'=>$site_web);

        $this->get('session')->set('contenu', $result);

        return new Response(json_encode($result), 200);


    }

    public function sommeAction(Request $request)
    {

        $referencement =$this->get('session')->get('ref');
        $contenu = $this->get('session')->get('cont');
        $affichage = $this->get('session')->get('aff');




        $results =array('referencement'=>$referencement,'contenu'=>$contenu,'affichage'=> $affichage);
        $result =intval($referencement);

        $this->get('session')->set('result', $result);

        return new Response(json_encode($result), 200);


    }

    public function sommeaffAction(Request $request)
    {

        $referencement =$this->get('session')->get('ref');
        $contenu = $this->get('session')->get('cont');
        $affichage = $this->get('session')->get('aff');




        $results =array('referencement'=>$referencement,'contenu'=>$contenu,'affichage'=> $affichage);
        $result =intval($affichage);

        $this->get('session')->set('result', $result);

        return new Response(json_encode($result), 200);


    }

    public function sommecontAction(Request $request)
    {


        $contenu = $this->get('session')->get('cont');

        $result =intval($contenu);

        $this->get('session')->set('result', $result);

        return new Response(json_encode($result), 200);


    }

    public function contAction(Request $request)
    {

        $contenu = $request->request->get('nbr');

        $result =array('cont'=> $contenu);

        $this->get('session')->set('cont', $contenu);

        return new Response(json_encode($result), 200);
    }

    public function refAction(Request $request)
    {

        $referencement = $request->request->get('nbr');
        $this->get('session')->set('ref', $referencement);

        $result =array('referencement'=>$referencement);

        return new Response(json_encode($result), 200);

    }

    public function affAction(Request $request)
    {


        $affichage = $request->request->get('nbr');

        $result =array('affichage'=> $affichage);

        $this->get('session')->set('aff', $affichage);

        return new Response(json_encode($result), 200);


    }

    public function profAction(Request $request)
    {


        $profession = $request->request->get('nbr');

        $result =array('profession'=> $profession);

        $this->get('session')->set('prof', $profession);

        return new Response(json_encode($result), 200);


    }

    public function factureAction(Request $request)
    {

        $this->container->get('setNewFacture')->contact()->Output('E-contact.pdf');
        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');
        return $response;

    }

    public function modalitePaiementAction(Request $request)
    {

        $this->container->get('setNewFacture')->modalitepaiement()->Output('E-contact.pdf');
        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');
        return $response;

    }


    public function affichageAction(Request $request)
    {

        $villes = $request->request->get('villes');

        $regions = $request->request->get('regions');

        $cat1 = $request->request->get('cat1');
        $cat2r = $request->request->get('cat2r');
        $cat2f = $request->request->get('cat2f');
        $cat2ma = $request->request->get('cat2ma');
        $cat2me = $request->request->get('cat2me');
        $cat2ag = $request->request->get('cat2ag');
        $cat2tan = $request->request->get('cat2tan');
        $cat3k = $request->request->get('cat3k');
        $cat3o = $request->request->get('cat3o');
        $cat3e = $request->request->get('cat3e');
        $cat3sa = $request->request->get('cat3sa');
        $cat3se = $request->request->get('cat3se');
        $cat3te = $request->request->get('cat3te');


        $thematique_name =intval( $request->request->get('thematique_name'));
        $localite_name = intval( $request->request->get('localite_name'));
        $pfjour_name = intval( $request->request->get('pfjour_name'));
        $promo_name = intval( $request->request->get('promo_name'));
        $total1_name = intval( $request->request->get('total1_name'));
        $habillage_name = intval( $request->request->get('habillage_name'));
        $banniere_name = intval( $request->request->get('banniere_name'));

        $cat4 = $request->request->get('cat4');

        $pro_du_jour = $request->request->get('pro_du_jour');
        $promo = $request->request->get('promo');

        $vignette_acc_video_nbr2 = $request->request->get('nbr2');
        $vign_ac = $request->request->get('vign_ac');
        $habil = $request->request->get('habil');


        $banniere_nombr2 = $request->request->get('nombre2');

        $bann_up_engin = $request->request->get('bann_up_engin');
        $bann_down_engin = $request->request->get('bann_down_engin');
        $bann_up_customer = $request->request->get('bann_up_customer');
        $bann_down_customer = $request->request->get('bann_down_customer');


        $result =array(

            'villes' =>    $villes,
            'regions'=>    $regions,
            'cat1'   =>    $cat1,
            'cat2r'  =>    $cat2r,
            'cat2f'  =>    $cat2f,
            'cat2ma' =>    $cat2ma,
            'cat2me' =>    $cat2me,
            'cat2ag' =>    $cat2ag,
            'cat2tan'=>    $cat2tan,
            'cat3k'  =>    $cat3k,
            'cat3o'  =>    $cat3o,
            'cat3e'  =>    $cat3e,
            'cat3sa' =>    $cat3sa,
            'cat3se' =>    $cat3se,
            'cat3te' =>    $cat3te,
            'cat4'   =>    $cat4,
            'pro_du_jour' => $pro_du_jour,
            'promo'  => $promo,
            'vignette_acc_video_nbr2'=>$vignette_acc_video_nbr2,
            'vign_ac' => $vign_ac,
            'habil'   => $habil,
            'banniere_nombr2' =>$banniere_nombr2,
            'bann_up_engin' =>$bann_up_engin,
            'bann_down_engin' =>$bann_down_engin,
            'bann_up_customer' =>$bann_up_customer,
            'bann_down_customer'=>$bann_down_customer,
            'thematique_name' => $thematique_name,
            'localite_name' => $localite_name,
            'pfjour_name' => $pfjour_name,
            'promo_name' => $promo_name,
            'total1_name' => $total1_name,
            'habillage_name' => $habillage_name,
            'banniere_name' => $banniere_name,

        );

        $this->get('session')->set('affichage', $result);

        return new Response(json_encode($result), 200);

    }

    public function professionAction(Request $request)
    {
        $logo     = $request->request->get('logo');
        $parcours_diplomes      = $request->request->get('parcours_diplomes');
        $specialites      = $request->request->get('specialites');
        $services      = $request->request->get('services');
        $rubrique      = $request->request->get('check');
        $certifications      = $request->request->get('certifications');
        $first_day      = $request->request->get('first_day');
        $samedi      = $request->request->get('samedi');
        $second_day      = $request->request->get('second_day');
        $h_samedi      = $request->request->get('h_samedi');
        $hour1     = $request->request->get('hour1');
        $hour2     = $request->request->get('hour2');
        $hour3     = $request->request->get('hour3');
        $hour4     = $request->request->get('hour4');
        $langue      = $request->request->get('langue');
        $paiement      = $request->request->get('paiement');
        $socieux      = $request->request->get('socieux');
        $villes     = $request->request->get('villesp');
        $prof      = $request->request->get('profession');
        $prix      = $request->request->get('prix');
        $count_specialites      = $request->request->get('count_specialites');
        $count_services      = $request->request->get('count_services');
        $profession=
            array(
                'logo'=>$logo,
                'parcours_diplomes'=>$parcours_diplomes,
                'specialites'=>$specialites,
                'services'=>$services,
                'rubrique'=>$rubrique,
                'certifications'=>$certifications,
                'first_day'=>$first_day,
                'samedi'=>$samedi,
                'second_day'=>$second_day,
                'h_samedi'=>$h_samedi,
                'hour1'=>$hour1,
                'hour2'=>$hour2,
                'hour3'=>$hour3,
                'hour4'=>$hour4,
                'langue'=>$langue,
                'paiement'=>$paiement,
                'socieux'=>$socieux,
                'villesp'=>$villes,
                'profession'=>$prof,
                'prix'=>$prix,
                'count_specialites'=>$count_specialites,
                'count_services'=>$count_services,
            );
        $this->get('session')->set('profession', $profession);

        return new Response(json_encode($profession), 200);

    }

    public function rsAction(Request $request)
    {
        $rs     = $request->request->get('rs');
        $cfirme = $request->request->get('cfirme');
        $civi   = $request->request->get('civi');
        $sign   = $request->request->get('sign');
        $profession   = $request->request->get('profession');

        $raison=
            array(
                'cfirme'=>$cfirme,
                'rs'=>$rs,
                'civi'=>$civi,
                'sign'=>$sign,
                'profession'=>$profession
            );
        $this->get('session')->set('raison', $raison);

        $result =array('raison' => $raison);
        return new Response(json_encode($result), 200);


    }

    public function desrubAction(Request $request)
    {
        $rub1 = $request->request->get('check');
        $prest1 = $request->request->get('prest');
        $addprest1 = $request->request->get('addprest');

        $rub2 = $request->request->get('check1');
        $prest2 = $request->request->get('prest1');
        $addprest2 = $request->request->get('addprest1');

        $rub3 = $request->request->get('check2');
        $prest3 = $request->request->get('prest2');
        $addprest3 = $request->request->get('addprest2');

        $rub4 = $request->request->get('check3');
        $prest4 = $request->request->get('prest3');
        $addprest4 = $request->request->get('addprest3');

        $rub5 = $request->request->get('check4');
        $prest5 = $request->request->get('prest4');
        $addprest5 = $request->request->get('addprest4');

        $rub6 = $request->request->get('check5');
        $prest6 = $request->request->get('prest5');
        $addprest6 = $request->request->get('addprest5');

        $rub7 = $request->request->get('check6');
        $prest7 = $request->request->get('prest6');
        $addprest7 = $request->request->get('addprest6');
        $prestsupp = $request->request->get('prestsupp');

        $desrubref =array('rub1'=>$rub1,'prest1'=>$prest1,'addprest1'=>$addprest1,'rub2'=>$rub2,'prest2'=>$prest2,'addprest2'=>$addprest2,'rub3'=>$rub3,'prest3'=>$prest3,'addprest3'=>$addprest3,'rub4'=>$rub4,'prest4'=>$prest4,'addprest4'=>$addprest4,'rub5'=>$rub5,'prest5'=>$prest5,'addprest5'=>$addprest5,'rub6'=>$rub6,'prest6'=>$prest6,'addprest6'=>$addprest6,'rub7'=>$rub7,'prest7'=>$prest7,'addprest7'=>$addprest7,'prestsupp'=>$prestsupp);

        $this->get('session')->set('desrubref', $desrubref);

        return new Response(json_encode($desrubref), 200);



    }



    public function desrubfayssalAction(Request $request)
    {
        $rub1 = $request->request->get('check');
       if ($rub1){
        $rub1=explode('|',$rub1);}

        $package = $request->request->get('package');
        $package_value = $request->request->get('villes_package');

        $prest1 = $request->request->get('prest');
        $addprest1 = $request->request->get('addprest');
        $villes1 = $request->request->get('villes');

        $villes1=explode('|',$villes1);


        $villes_panier1 = $request->request->get('villes_panier');

        $arr1=array();
        if ($villes_panier1){

            $i=0;
            foreach($villes_panier1 as $value) {
                $values=explode('|',$value);
                $arr1[$i]= $values[0];
                $i++ ;
            }
        }


        $rub2 = $request->request->get('check1');

        $rub2=explode('|',$rub2);

        $prest2 = $request->request->get('prest1');
        $addprest2 = $request->request->get('addprest1');
        $villes2 = $request->request->get('villes1');

        $villes2=explode('|',$villes2);
        $villes_panier2 = $request->request->get('villes_panier1');



        $arr2=array();

        if ($villes_panier2){



            $i=0;
            foreach($villes_panier2 as $value) {
                $values=explode('|',$value);
                $arr2[$i]= $values[0];
                $i++ ;
            }
        }


        $rub3 = $request->request->get('check2');
        $rub3=explode('|',$rub3);

        $prest3 = $request->request->get('prest2');
        $addprest3 = $request->request->get('addprest2');

        $villes3 = $request->request->get('villes2');

        $villes3=explode('|',$villes3);
        $villes_panier3 = $request->request->get('villes_panier2');




        $arr3=array();
        if ($villes_panier3){


            $i=0;
            foreach($villes_panier3 as $value) {
                $values=explode('|',$value);
                $arr3[$i]= $values[0];
                $i++ ;
            }
        }



        $rub4 = $request->request->get('check3');
        $rub4=explode('|',$rub4);
        $prest4 = $request->request->get('prest3');
        $addprest4 = $request->request->get('addprest3');
        $villes4 = $request->request->get('villes3');
        $villes4=explode('|',$villes4);

        $villes_panier4 = $request->request->get('villes_panier3');

        $arr4=array();
        if ($villes_panier4){

            $i=0;
            foreach($villes_panier4 as $value) {
                $values=explode('|',$value);
                $arr4[$i]= $values[0];
                $i++ ;
            }
        }



        $rub5 = $request->request->get('check4');
        $rub5=explode('|',$rub5);

        $prest5 = $request->request->get('prest4');
        $addprest5 = $request->request->get('addprest4');
        $villes5 = $request->request->get('villes4');
        $villes5=explode('|',$villes5);

        $villes_panier5 = $request->request->get('villes_panier4');

        $arr5=array();
        if ($villes_panier5){



            $i=0;
            foreach($villes_panier5 as $value) {
                $values=explode('|',$value);
                $arr5[$i]= $values[0];
                $i++ ;
            }
        }


        $rub6 = $request->request->get('check5');
        $rub6=explode('|',$rub6);

        $prest6 = $request->request->get('prest5');
        $addprest6 = $request->request->get('addprest5');

        $villes6 = $request->request->get('villes5');
        $villes6=explode('|',$villes6);
        $villes_panier6 = $request->request->get('villes_panier5');

        $arr6=array();
        if ($villes_panier6){


            $i=0;
            foreach($villes_panier6 as $value) {
                $values=explode('|',$value);
                $arr6[$i]= $values[0];
                $i++ ;
            }
        }



        $rub7 = $request->request->get('check6');
        $rub7=explode('|',$rub7);

        $prest7 = $request->request->get('prest6');
        $addprest7 = $request->request->get('addprest6');

        $villes7 = $request->request->get('villes6');
        $villes7=explode('|',$villes7);
        $villes_panier7 = $request->request->get('villes_panier6');

        $arr7=array();
        if ($villes_panier7){


            $i=0;
            foreach($villes_panier7 as $value) {
                $values=explode('|',$value);
                $arr7[$i]= $values[0];
                $i++ ;
            }
        }



        $prestsupp = $request->request->get('prestsupp');

        $final = $request->request->get('final');

        $fayssal = $request->request->get('fayssal');

        $rub_supp_1 = $request->request->get('rub_supp_1');
        $rub_supp_2 = $request->request->get('rub_supp_2');
        $rub_supp_3 = $request->request->get('rub_supp_3');
        $rub_supp_4 = $request->request->get('rub_supp_4');
        $rub_supp_5 = $request->request->get('rub_supp_5');
        $rub_supp_6 = $request->request->get('rub_supp_6');
        $rub_supp_7 = $request->request->get('rub_supp_7');

        $localiter_supp  = $request->request->get('localiter_supp');
        $localiter_supp1 = $request->request->get('localiter_supp1');
        $localiter_supp2 = $request->request->get('localiter_supp2');
        $localiter_supp3 = $request->request->get('localiter_supp3');
        $localiter_supp4 = $request->request->get('localiter_supp4');
        $localiter_supp5 = $request->request->get('localiter_supp5');
        $localiter_supp6 = $request->request->get('localiter_supp6');
        $localiter_supp7 = $request->request->get('localiter_supp7');




        $desrubref =array('package_value'=>$package_value,'package'=>$package,'final'=>$final,'fayssal'=>$fayssal,'rub_supp_1'=>$rub_supp_1,'rub_supp_2'=>$rub_supp_2,'rub_supp_3'=>$rub_supp_3,'rub_supp_4'=>$rub_supp_4,'rub_supp_5'=>$rub_supp_5,'rub_supp_6'=>$rub_supp_6,'rub_supp_7'=>$rub_supp_7,
            'localiter_supp'=>$localiter_supp,'localiter_supp1'=>$localiter_supp1,'localiter_supp2'=>$localiter_supp2,'localiter_supp3'=>$localiter_supp3,'localiter_supp4'=>$localiter_supp4,'localiter_supp5'=>$localiter_supp5,'localiter_supp6'=>$localiter_supp6,'localiter_supp7'=>$localiter_supp7,
            'villes_panier7'=>$arr7,'villes_panier6'=>$arr6,'villes_panier5'=>$arr5,'villes_panier4'=>$arr4,'villes_panier3'=>$arr3,'villes_panier2'=>$arr2,'villes_panier1'=>$arr1,'villes7'=>$villes7[0],'villes6'=>$villes6[0],'villes5'=>$villes5[0],'villes4'=>$villes4[0],'villes3'=>$villes3[0],'villes2'=>$villes2[0],'villes1'=>$villes1[0],'rub1'=>$rub1[0],'prest1'=>$prest1,'addprest1'=>$addprest1,'rub2'=>$rub2[0],'prest2'=>$prest2,'addprest2'=>$addprest2,'rub3'=>$rub3[0],'prest3'=>$prest3,'addprest3'=>$addprest3,'rub4'=>$rub4[0],'prest4'=>$prest4,'addprest4'=>$addprest4,'rub5'=>$rub5[0],'prest5'=>$prest5,'addprest5'=>$addprest5,'rub6'=>$rub6[0],'prest6'=>$prest6,'addprest6'=>$addprest6,'rub7'=>$rub7[0],'prest7'=>$prest7,'addprest7'=>$addprest7,'prestsupp'=>$prestsupp);




        $this->get('session')->set('desrubref', $desrubref);

        return new Response(json_encode($desrubref), 200);



    }


    public function marqueAction( Request $request)
    {
        $marq1 = $request->request->get('marq');
        $posi1 = $request->request->get('positionnement');

        $marq2 = $request->request->get('marq1');
        $posi2 = $request->request->get('positionnement1');

        $marq3 = $request->request->get('marq2');
        $posi3 = $request->request->get('positionnement2');

        $marq4 = $request->request->get('marq3');
        $posi4 = $request->request->get('positionnement3');

        $marq5 = $request->request->get('marq4');
        $posi5 = $request->request->get('positionnement4');

        $marq6 = $request->request->get('marq5');
        $posi6 = $request->request->get('positionnement5');

        $marq7 = $request->request->get('marq6');
        $posi7 = $request->request->get('positionnement6');

        $marq8 = $request->request->get('marq7');
        $posi8 = $request->request->get('positionnement7');

        $marq9 = $request->request->get('marq8');
        $posi9 = $request->request->get('positionnement8');

        $marq10 = $request->request->get('marq9');
        $posi10 = $request->request->get('positionnement9');

        $marque =array('marq1'=>$marq1,'posi1'=>$posi1,'marq2'=>$marq2,'posi2'=>$posi2,'marq3'=>$marq3,'posi3'=>$posi3,'marq4'=>$marq4,'posi4'=>$posi4,'marq5'=>$marq5,'posi5'=>$posi5,'marq6'=>$marq6,'posi6'=>$posi6,'marq7'=>$marq7,'posi7'=>$posi7,'marq8'=>$marq8,'posi8'=>$posi8,'marq9'=>$marq9,'posi9'=>$posi9,'marq10'=>$marq10,'posi10'=>$posi10);

        $this->get('session')->set('marque', $marque);

        return new Response(json_encode($marque), 200);
        /* for ($i = 0; $i <= 9; $i++) {

             if ($i==0)
             {
                 $var[0]['marque'] = $request->request->get('marq');

                 $positionnement = '';
                 if ($request->request->get('positionnement')) {
                     foreach ($request->request->get('positionnement') as $names) {
                         if (empty($positionnement)) {
                             $positionnement .= '' . $names;
                         } else {

                             $positionnement .= "\n" . '' . $names;
                         }
                     }
                 }
                 $var[0]['positionnement'] = $positionnement;

             }
             else{
                 $var[$i]['marque'] = $request->request->get('marq'.$i);

                 $positionnement = '';
                 if ($request->request->get('positionnement'.$i)) {
                     foreach ($request->request->get('positionnement'.$i) as $names) {
                         if (empty($positionnement)) {
                             $positionnement .= '' . $names;
                         } else {

                             $positionnement .= "\n" . '' . $names;
                         }
                     }
                 }
                 $var[$i]['positionnement'] = $positionnement;


             }
         }

         $marque=array('marque'=>$var);
         $this->get('session')->set('marque', $marque);

         return new Response(json_encode($marque), 200);*/

    }

    public function nbrrubAction(Request $request)
    {
        $nbr_rub = $request->request->get('nbr_rub');

        $nbr_rub=array('nbr_rub'=>$nbr_rub);
        $this->get('session')->set('nbr_rub', $nbr_rub);
        return new Response(json_encode($nbr_rub), 200);


    }

    public function paieAction(Request $request)
    {
        $montantttc     = $request->request->get('montantttc');
        $new_ht     = $request->request->get('new_ht');
        $accompte       = $request->request->get('accompte');
        $reste      = $request->request->get('reste');
        $nbr        = $request->request->get('nbr');
        $montant1   = $request->request->get('montant1');
        $montant2   = $request->request->get('montant2');
        $montant3   = $request->request->get('montant3');
        $dateP1     = $request->request->get('dateP1');
        $dateP2     = $request->request->get('dateP2');
        $dateP3     = $request->request->get('dateP3');

        $paiement=array('montantttc'=>$montantttc,'new_ht'=>$new_ht,'accompte'=>$accompte,'reste'=>$reste,'nbr'=>$nbr,'montant1'=>$montant1,'montant2'=>$montant2,'montant3'=>$montant3,'dateP1'=>$dateP1,'dateP2'=>$dateP2,'dateP3'=>$dateP3);
        $this->get('session')->set('paiement', $paiement);

        return new Response(json_encode($paiement), 200);
    }
    public function code_rpAction(Request $request)
    {
        $nature_remise     = $request->request->get('nature_remise');
        $montant_remise    = $request->request->get('montant_remise');
        $offre             = $request->request->get('offre');
        $pourcentage       = $request->request->get('pourcentage');
        $montant_r       = $request->request->get('montant_r');
        $montant_rem       = $request->request->get('montant_rem');
        $tva       = $request->request->get('tva');


        $code=array('nature_remise'=>$nature_remise,'montant_remise'=>$montant_remise,'offre'=>$offre,'pourcentage'=>$pourcentage,'montant_r'=>$montant_r,'montant_rem'=>$montant_rem,'tva'=>$tva);
        $this->get('session')->set('code', $code);

        return new Response(json_encode($code), 200);
    }

    public function visbiliteAction(Request $request)
    {
        $proposition     = $request->request->get('proposition');
        $ordre     = $request->request->get('ordre');
        $bon_commande     = $request->request->get('bon_commande');
        $visbilite_header=array('proposition'=>$proposition,'ordre'=>$ordre,'bon_commande'=>$bon_commande,);
        $this->get('session')->set('visbilite_header', $visbilite_header);

        return new Response(json_encode($visbilite_header), 200);
    }

    public function ajaxRefAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ville  =  $request->request->get('ville');
        $opt1  =  $request->request->get('opt1');
        $reg =  $request->request->get('region');
        $ctar =  $request->request->get('ctar');

        if($ctar=='MV'){
            $regions=$em->getRepository('EcommerceBundle:Ville')->findOneBy(array('codeville' => $ville));

            $region=$regions->getRegion()->getCoderegion();
            $var='rGion'.$region;
        }
        if($ctar=='ML'){
            $var='rGion'.$reg;
        }


        if($ctar=='RV'){
            $regions=$em->getRepository('EcommerceBundle:Ville')->findOneBy(array('codeville' => $ville));
            $region=$regions->getRegion()->getCoderegion();
            $var='rGion'.$region;
        }
        if($ctar=='RL'){
            $var='rGion'.$reg;
        }
        $query = $em->getRepository('EcommerceBundle:TarifInternet')->createQueryBuilder('t')->select('t.'.$var.' AS VAR')
            ->where('t.opt1 = :op')->setParameter('op',$opt1)
            ->andWhere('t.ctar = :ct')->setParameter('ct',$ctar)->getQuery()->getSingleResult();

        $response = new JsonResponse();
        return $response->setData(array('nom' => $query));


    }

    public function gettingAction(Request $request)
    {

        $var=   $this->get('session')->get('referencement');
        $contenu=   $this->get('session')->get('contenu');
        $affichage=   $this->get('session')->get('affichage');
        var_dump($var);
        echo '<br/>';
        var_dump($contenu);

        echo '<br/>';
        var_dump($affichage);

        die('here');
        $var=   $this->get('session')->get('ref');
        var_dump($var);

        $vasr=   $this->get('session')->get('aff');
        var_dump($vasr);

        $vassr=   $this->get('session')->get('cont');
        var_dump($vassr);
        die('now');

    }

    public function setCommandeAction()
    {
        $session = $this->getRequest()->getSession();
    }

}

