<?php
namespace Ecommerce\EcommerceBundle\Services;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GetEmail
{
    public function __construct(ContainerInterface $container, Session $session, EntityManager $em)
    {
        $this->container = $container;
        $this->session = $session;
        $this->em = $em;
    }




    public function contact()
    {


        $affichage = $this->session->get('affichage');


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

        if ($this->session->has('profession'))
            $profession = $this->session->get('profession');

        else
            $profession =
                array(
                    'villesp'=>NULL,
                    'profession'=>NULL,
                    'prix'=>NULL,

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
        $raison =$this->session->get('raison');


        $result =(intval($aff)+intval($con)+intval($ref));



        $html = $this->container->get('templating')->render('EcommerceBundle:Default:produits/layout/contactsec.html.twig', array('raison'=> $raison,'referencement'=> $referencement, 'affichage'=> $affichage,'contenu'=>$contenu,'paiement' =>$paiement,'profession' =>$profession,'desrubref'=>$desrubref,'code'=>$code,'nbr_rub'=>$nbr_rub,'marque'=>$marque,'somme'=>$result,'visbilite_header'=>$visbilite_header));
        $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);

        return $html2pdf;
    }

    public function contactsec($id)
    {


        $entity = $this->em->getRepository('EcommerceBundle:Mails')->find($id);


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