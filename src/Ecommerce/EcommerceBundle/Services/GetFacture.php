<?php
namespace Ecommerce\EcommerceBundle\Services;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GetFacture
{
    public function __construct(ContainerInterface $container, Session $session, EntityManager $em)
    {
        $this->container = $container;
        $this->session = $session;
        $this->em = $em;
    }




    public function contact()
    {



     $by_code=$this->container->get('security.context')->getToken()->getUser()->getCode();

     $lastQuestion = $this->em->getRepository('EcommerceBundle:Mails')->findOneBy(  array('bon_commande'=>'bon_commande'),  array('id' => 'DESC'));
     $lastId = $lastQuestion->getNumbc();
     $lastId = $lastId + 1 ;

        /*$affichage = $this->session->get('affichage');*/

        if ($this->session->has('affichage'))

            $affichage = $this->session->get('affichage');

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
                'cat3k'=>  NULL,
                'cat3o'=>  NULL,
                'cat3e'=>  NULL,
                'cat3sa'=>  NULL,
                'cat3se'=>  NULL,
                'cat3te'=>  NULL,
                'cat4'=>    NULL,
                'pro_du_jour' => NULL,
                'promo'  => NULL,
                'vignette_acc_video_nbr'=>NULL,
                'vign_ac' => NULL,
                'habil'   => NULL,
                'banniere_nombr' =>NULL,
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



        if ( $this->session->has('referencement'))
            $referencement =  $this->session->get('referencement');
        else
            $referencement =array(
                'rubrique'=>NULL,
                'prest'=>NULL,
                'prest_sup'=> NULL,
                'marque'=>NULL,
                'sum3'=>NULL ,
                'mari'=> NULL,
                'resulta'=> NULL,
                'rubd'=> NULL,
                'rania'=> NULL,
                'raniad'=> NULL,
                'villes'=>NULL,
                'regions'=>NULL,
                'villes_sup'=>NULL,
                'regions_sup'=>NULL);


        if ($this->session->has('contenu'))
            $contenu = $this->session->get('contenu');
        else
            $contenu = array('catalogue'=>NULL,'catalogue_ref'=>NULL,'video'=> NULL,'page'=>NULL,'site_web'=>NULL);

        if ($this->session->has('paiement'))
            $paiement = $this->session->get('paiement');
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

        if ($this->session->has('desrubref'))
            $desrubref = $this->session->get('desrubref');

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

                );

        if ($this->session->has('profession'))
            $profession = $this->session->get('profession');

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
                    'first_hour'=>null,
                    'second_hour'=>null,
                    'langue'=>null,
                    'paiement'=>null,
                    'socieux'=>null,

                );

        if ($this->session->has('code'))
            $code = $this->session->get('code');

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
        if ($this->session->has('nbr_rub'))
            $nbr_rub = $this->session->get('nbr_rub');
        else
            $nbr_rub =
                array(
                    'nbr_rub'=>NULL,
                );

        if ($this->session->has('marque'))
            $marque = $this->session->get('marque');
        else
            $marque =
                array(
                    'marq'=>null,
                    'marq1'=>null,
                    'marq2'=>null,
                    'marq3'=>null,
                    'marq4'=>null,
                    'marq5'=>null,
                    'marq6'=>null,
                    'marq7'=>null,
                    'marq8'=>null,
                    'marq9'=>null,
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

        if ($this->session->has('visbilite_header'))
            $visbilite_header = $this->session->get('visbilite_header');
        else
            $visbilite_header =
                array(
                    'proposition'=>null,
                    'ordre'=>null,
                    'bon_commande'=>null,

                );


        $ref =$this->session->get('ref');
        $con = $this->session->get('cont');
        $aff = $this->session->get('aff');
        /*$prof = $this->session->get('prof');*/
        /*$raison =$this->session->get('raison');*/
        if($this->session->has('raison'))
            $raison =$this->session->get('raison');
        else
            $raison =array(
                'rs'=>NULL,
                'cfirme'=>NULL,
                'civi'=>NULL,
                'sign'=>NULL,
                'profession'=>NULL,

            );

        $result =(intval($aff)+intval($con)+intval($ref)/*+intval($prof)*/);

        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactsec.html.twig', array('raison'=> $raison,'referencement'=> $referencement, 'affichage'=> $affichage,'contenu'=>$contenu,'somme'=>$result,'paiement' =>$paiement,'profession' =>$profession,'desrubref'=>$desrubref,'code'=>$code,'nbr_rub'=>$nbr_rub,'marque'=>$marque,'visbilite_header'=>$visbilite_header,'lastId'=>$lastId, 'by_code'=> $by_code));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        return $html2pdf;
    }


    public function modalitepaiement()
    {
        if ($this->session->has('paiement'))
            $paiement = $this->session->get('paiement');
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

        $ref =$this->session->get('ref');
        $con = $this->session->get('cont');
        $aff = $this->session->get('aff');
        $raison =$this->session->get('raison');
        $result =(intval($aff)+intval($con)+intval($ref));

        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/paiement.html.twig', array('raison'=> $raison,'somme'=>$result,'paiement' =>$paiement));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        return $html2pdf;
    }


    public function contactsec($id)
    {


        $entity = $this->em->getRepository('EcommerceBundle:Finals')->find($id);


        $affichage = $entity->getAffichage();
        $referencement = $entity->getReferencement();
        $panier =$entity->getContenu();
        $result =$entity->getResultat();
        $paiement=$entity->getPaiement();
        $profession=$entity->getProfession();
        $desrubref=$entity->getDesrubref();
        $code=$entity->getCode();
        $marque=$entity->getMarque();

        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactre.html.twig', array('entity'=>$entity, 'referencement' => $referencement,'contenu' => $panier,'affichage' => $affichage,'somme'=>$result,'paiement'=>$paiement,'profession'=>$profession,'desrubref'=>$desrubref,'code'=>$code,'marque'=>$marque));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','letter','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        return $html2pdf;



    }


}