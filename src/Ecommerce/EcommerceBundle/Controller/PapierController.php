<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Ecommerce\EcommerceBundle\Entity\Categories;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PapierController extends Controller
{

    public function devisGestionDeContenuPapierAction(){
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session')->get('raison');

        $servername = "localhost";
        $username = "pyxicom";
        $password = "Yz9nVEXjZ2hqptZT";
        $dbname = "BD_EDICOM";

        // Create connection
        $conn = new \mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT code_ville FROM firmes where code_firme = 'MA".$session["cfirme"]."'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            $sqlVille = "SELECT ville FROM villes where code = ".$row["code_ville"];
            $ville = $conn->query($sqlVille);
          }
        } else {
          echo "0 results";
        }

        while($rowVille = $ville->fetch_assoc()) {
           
            $villeRs = $rowVille['ville'];
          }
        $conn->close();

       $rubriques = $em->getRepository('EcommerceBundle:Rubrique')->findAll();

       $query = "select * from u_yf_regions where number IS NOT NULL";
       $statement =$em->getConnection()->prepare($query);
       $statement->execute();
       $regions = $statement->fetchAll();

       $queryTarif = "SELECT DISTINCT CTAR ,LIBTAR ,PRIX1 FROM `tarif` WHERE NSTE=3. AND TEDI=1 AND NEDI=31 GROUP BY CTAR";
       $statementTarif =$em->getConnection()->prepare($queryTarif);
       $statementTarif->execute();
       $tarifs = $statementTarif->fetchAll();
       return $this->render('EcommerceBundle:Default:papier/budgetisationGestionDeContenuPapier.html.twig', array('rubriques' => $rubriques,'regions' => $regions,'tarifs' => $tarifs,'ville' => $villeRs, 'codeFirme' => $session["cfirme"]));
    }

    public function budgetPapierAction(Request $request){
        $ctar = $request->get('ctar');
        $libtar = $request->get('libtar');
        $rubrique = $request->get('rubriquePapier');
        $region = $request->get('region');
        $villeRs = $request->get('villeRs');
        $val = $request->get('val');
        $session = $this->get('session')->get('raison');
        $codeFirme = $session["cfirme"];
        $rs = $session["rs"];
        $civi = $session["civi"];
        $sign = $session["sign"];
        $em = $this->getDoctrine()->getManager();

        if($region){
    
            $queryTarif = "SELECT count(*) FROM `tarif` WHERE `CTAR` LIKE '".$ctar."' AND `LIBTAR` LIKE '".$libtar."' and NSTE=3. AND TEDI=1 AND NEDI=31";
            $statementTarif =$em->getConnection()->prepare($queryTarif);
            $statementTarif->execute();
            $result = $statementTarif->fetchAll();
            if ($result[0]["count(*)"] != 1) {
                $queryTarifReg = "SELECT *  FROM `tarif` WHERE `CTAR` LIKE '".$ctar."' AND `LIBTAR` LIKE '".$libtar."' and `DISTRIB` =".$region." and NSTE=3. AND TEDI=1 AND NEDI=31";
                $statementTarifReg =$em->getConnection()->prepare($queryTarifReg);
                $statementTarifReg->execute();
                $resTarifRegion = $statementTarifReg->fetchAll();
                if($resTarifRegion){
                    if($resTarifRegion[0]['PRIX1'] != 0){
                        $prix = $resTarifRegion[0]['PRIX1'];
                    }elseif ($resTarifRegion[0]['PRIX2'] != 0) {
                        $prix = $resTarifRegion[0]['PRIX2'];
                    }else{
                        $prix = $resTarifRegion[0]['PRIX3'];
                    }
                      $this->registerPapier($ctar,$libtar,$region,$villeRs,$codeFirme,$rs,$civi,$sign,$prix,$rubrique,$val);
                     return new JsonResponse(array('prix' => $prix,'id' => $val));
                }
               
            }else{
                $queryTarif = "SELECT *  FROM `tarif` WHERE `CTAR` LIKE '".$ctar."' AND `LIBTAR` LIKE '".$libtar."' and NSTE=3. AND TEDI=1 AND NEDI=31";
                $statementTarif =$em->getConnection()->prepare($queryTarif);
                $statementTarif->execute();
                $resTarif = $statementTarif->fetchAll();
                if($resTarif[0]['PRIX1'] != 0){
                    $prixreg = $resTarif[0]['PRIX1'];
                }elseif ($resTarif[0]['PRIX2'] != 0) {
                    $prixreg = $resTarif[0]['PRIX2'];
                }else{
                    $prixreg = $resTarif[0]['PRIX3'];
                }$prix=$prixreg;
                 $this->registerPapier($ctar,$libtar,$region,$villeRs,$codeFirme,$rs,$civi,$sign,$prix,$rubrique,$val);
                return new JsonResponse(array('prix' => $prix,'id' => $val));
            }
       
        }
        $prix = 0;
        return new JsonResponse(array('prix' => $prix,'id' => $val));
        
    }

    public function registerPapier($ctar,$libtar,$region,$villeRs,$codeFirme,$rs,$civi,$sign,$prix,$rubrique,$val){
        
        $query = "select * from u_yf_regions where number =".$region;
        $em = $this->getDoctrine()->getManager();
        $statementReg =$em->getConnection()->prepare($query);
        $statementReg->execute();
        $reg = $statementReg->fetchAll(); 
        $by_tr=$this->container->get('security.context')->getToken()->getUser();
        $line = array(
          'format' => $ctar.'-'.$libtar, 
          'region' => $reg, 
          'villeRs' => $villeRs, 
          'codeFirme' => $codeFirme,
          'rs' => $rs,
          'civi' => $civi,
          'sign' => $sign,
          'prix' => $prix,
          'rubrique' => $rubrique,
          'utilisateur' => $by_tr
        );
        $papier = array('papier'.$val => $line,);
        $session = $this ->getRequest()->getSession();
        if(!$session->has('papier')){
            
            $session->set('papier',array());
        }
        
        $session->set('papier', array_merge($session->get('papier'), $papier));
        
        
        
        

        /*$em = $this->getDoctrine()->getManager();
        $query = "select * from u_yf_regions where number =".$region;
        $statementReg =$em->getConnection()->prepare($query);
        $statementReg->execute();
        $reg = $statementReg->fetchAll();
        $statement = $em->getConnection()->prepare("INSERT INTO papier(`rs`,`codeFirme`,`ville`,`region`,`signataire`,`format`,`prixTotale`,`rubrique`,`civi`,`dateCreation`) VALUES(:rs , :codeFirme , :ville , :region , :signataire , :format , :prixTotale , :rubrique, :civi, :dateCreation)");
        $statement->execute(array(
                ":rs" => $rs,
                ":codeFirme" => $codeFirme,
                ":ville" => $villeRs,
                ":region" => $reg[0]["region"],
                ":signataire" => $sign,
                ":format" => $ctar,
                ":prixTotale" => $prix,
                ":rubrique" => $rubrique[0],
                ":civi" => $civi,
                ":dateCreation" => date('Y-m-d H:i:s') 
            ));*/
        return true;
    }

    public function showDevisAction($codeFirme){
        $session = $this ->getRequest()->getSession();
        $panniers = $session->get('papier');
        $somme = 0;
        foreach ($panniers as $key => $value) {
            $somme = $somme + $value['prix'];
        }

        /*$queryPapier = "SELECT * FROM `papier` WHERE  `codeFirme`=".$codeFirme;
        $em = $this->getDoctrine()->getManager();
        $statementPapier =$em->getConnection()->prepare($queryPapier);
        $statementPapier->execute();
        $result = $statementPapier->fetchAll();
        $somme = 0;
        foreach ($result as $key => $value) {
            $somme = $somme + $value['prixTotale']; 
        }*/
        return $this->render('EcommerceBundle:Default:papier/devisPapier.html.twig', array('panniers' => $panniers,'somme' => $somme));
    }

    public function telechargerPdfAction(){
        $em = $this->getDoctrine()->getManager();
        $session = $this ->getRequest()->getSession();
        $panniers = $session->get('papier');
       
        $somme = 0;
        foreach ($panniers as $key => $value) {
            $somme = $somme + $value['prix'];
          
        }


        
        $html = $this->container->get('templating')->render('EcommerceBundle:Default:papier/pdf.html.twig', array('panniers'=> $panniers,'somme' =>$somme));

        $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
        $html2pdf->pdf->SetAuthor('E-contact');
        $html2pdf->pdf->SetTitle('E-contact');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $html2pdf->Output('Proposition_papier.pdf');
        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');
        return $response;
    }

    public function sendEmailPdfAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $session = $this ->getRequest()->getSession();
        $panniers = $session->get('papier');
         $by_tr=$this->container->get('security.context')->getToken()->getUser();
        $somme = 0;
        foreach ($panniers as $key => $value) {
            $somme = $somme + $value['prix'];
           
        $statement = $em->getConnection()->prepare("INSERT INTO papier(`rs`,`codeFirme`,`ville`,`region`,`signataire`,`format`,`prixTotale`,`rubrique`,`civi`,`dateCreation`, `utilisateur`) VALUES(:rs , :codeFirme , :ville , :region , :signataire , :format , :prixTotale , :rubrique, :civi, :dateCreation , :utilisateur)");
        $statement->execute(array(
                ":rs" => $value['rs'],
                ":codeFirme" => $value['codeFirme'],
                ":ville" => $value['villeRs'],
                ":region" => $value["region"][0]["region"],
                ":signataire" => $value['sign'],
                ":format" => $value['format'],
                ":prixTotale" => $value['prix'],
                ":rubrique" => $value['rubrique'][0],
                ":civi" => $value['civi'],
                ":dateCreation" => date('Y-m-d H:i:s'),
                ":utilisateur" => $by_tr
            ));
        }
         if($request->isMethod('Post')) {
            $mail = $request->request->get('email');
            $join = $request->request->get('joindre');
            
            $text='Merci de trouver ci-joint notre proposition de visibilitÃ© sur l\'annuaire papier';
             $html = $this->container->get('templating')->render('EcommerceBundle:Default:papier/pdf.html.twig', array('panniers'=> $panniers,'somme' =>$somme));
             $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
            $html2pdf->pdf->SetAuthor('E-contact');
            $html2pdf->pdf->SetTitle('E-contact');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->writeHTML($html);

            $to = $mail;
            $from      = "e-contact@telecontact.ma";
            $vb        ="f.anouar@edicom.ma";
            $subject   = "Mail EnvoyÃ© E-contact";
            $message   = "<p>.$text.</p>";
            $separator = md5(time());
            $eol       = PHP_EOL;
            $filename   = "Proposition_papier.pdf";

             $pdfdoc     = $html2pdf->Output('Proposition_papier.pdf', 'S');
            $attachment = chunk_split(base64_encode($pdfdoc));

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
            $body .= "--" . $separator . "--";

           

            if($mail){
                $la = mail($to, $subject, $body, $headers);
            }
            if ($la) {

                return new JsonResponse(array('success','Mail envoyÃ© avec success'));
               // $this->get('session')->getFlashBag()->add('success','Mail envoyÃ© avec success');
            } else {
                return new JsonResponse(array('error','mail n\'a pas Ã©tÃ© envoyÃ©'));
                //$this->get('session')->getFlashBag()->add('error','mail n\'a pas Ã©tÃ© envoyÃ©');
            }

            return $this->redirect($this->generateUrl('showDevis'));
         }

    }

}
