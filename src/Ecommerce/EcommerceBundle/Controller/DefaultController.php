<?php

namespace Ecommerce\EcommerceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Widop\GoogleAnalytics\Query;
use Widop\GoogleAnalytics\Client;
use Widop\HttpAdapter\CurlHttpAdapter;
use Widop\GoogleAnalytics\Service;
use Widop\HttpAdapter\GuzzleHttpAdapter;
use Liuggio\ExcelBundle\LiuggioExcelBundle;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Ecommerce\EcommerceBundle\Entity\Statistics;
use Ecommerce\EcommerceBundle\Entity\StatisticsGron;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\Session\Session;
use PDO;
/*use Knp\Component\Pager\PaginatorInterface;*/
/**
 * rs controller.
 *
 */
class DefaultController extends Controller
{

    /**
     * Lists all rs entities.
     *
     */
    public function indexAction()
    {    

         return $this->render('EcommerceBundle:Default:statistic/index.html.twig',array('nom' => '', 'prenom' => '', 'email' =>'', 'code_firme' => '', 'sexe' => '','message' =>''));  
    }

   


    public function previsualiserAction(Request $request){
            $code_firme = $request->request->get('code_firme');
            $code_firme =  str_replace('MA', '', $code_firme);
            $code_firme = str_replace(' ', '', $code_firme);
            $this->get('session')->set('codeFirmeImage', $code_firme);

            $email = $request->request->get('email');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $sexe = $request->request->get('sex');

            $resultVignetteRubrique  = $this->requetVignettes($code_firme,'vignettes','rubrique');
            $resultVignetteRegion    = $this->requetVignettes($code_firme,'vignettes','region');
            $resultApparitionRs      = $this->requetStatistic($code_firme,'apparition_rs');
            $resultApparitionRs1     = $this->requetStatistic($code_firme,'fiche_apparition');
            $resultRaisonSocialeClick= $this->requetStatistic($code_firme,'Raison_sociale_click'); 
            $resultAfficherLeNumero  = $this->requetStatistic($code_firme,'Afficher_Le_Numero');
            $resultSiteWeb           = $this->requetStatistic($code_firme,'Site_Web');
            $resultNombreMailsReçus  = $this->requetNombreMail($code_firme); 
            $resultDecouvrirCatalogue= $this->requetStatistic($code_firme,'Decouvrir_Catalogue');
            $resultPVIClick          = $this->requetStatistic($code_firme,'PVI_Click');
            $resultPageAffichercord  = $this->requetStatistic($code_firme,'Page_Afficher_cord');

            $resultpack_presence     = $this->requetStatistic($code_firme,'pack_presence');
            $resultPage_Simple       = $this->requetStatistic($code_firme,'Page_Simple');
            $resultVIDEO_Click       = $this->requetStatistic($code_firme,'VIDEO_Click');

            try{
              $ch = curl_init();
              if (FALSE === $ch){
                throw new Exception('failed to initialize');
              }
              curl_setopt($ch, CURLOPT_URL,"https://www.telecontact.ma/telecontact-analytics-emailing/".$code_firme);
              
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
              curl_setopt($ch, CURLOPT_POSTREDIR, 3);                                                                  
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                 
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow http 3xx redirects
              $Curl_result = curl_exec($ch); // execute
              
            }catch(Exception $e) {
              trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
            }

            
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $dirigeant = $connection->executeQuery("SELECT `code_firme` ,`fonction` , `nom` ,sex, personne.`prenom` FROM ( SELECT `id` , `code_personne` , `code_firme` , `code_fonction` FROM `lien_dirigeant` WHERE `code_firme` LIKE 'MA".$code_firme."' AND `code_fonction` NOT LIKE '0%' UNION SELECT * FROM `lien_dirigeant_sec` WHERE `code_firme` LIKE 'MA".$code_firme."' AND `code_fonction` NOT LIKE '0%' ORDER BY `code_fonction` ASC LIMIT 1 ) AS B, `personne` , fonction WHERE `personne`.code_personne = B.code_personne AND fonction.code = B.`code_fonction`");
            $resultdirigeant = $dirigeant->fetchAll();

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $sql_get_record = $connection->executeQuery("SELECT record_session, page_vue, users, record_date, update_date FROM telecontact_record WHERE id=1");
            $data_get_record = $sql_get_record->fetchAll();
            $resultPageViews= $this->requetPageVue('pageviews');
            $rank = $this->AlexaRank("telecontact.ma", "Morocco", "country");

            //l'envoi du mail
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'FROM:' . htmlspecialchars('edicom.telecontact@gmail.com') . "\r\n";
            $headers .= 'Bcc: edicom.telecontact@gmail.com' . "\r\n";
            $to = $email;
            
                setlocale(LC_TIME, "fr_FR");
                $today = date("Y-m-d");
                $thisMonth = date("F", strtotime("first day of previous month"));
                $lastMonth = date("F", strtotime("first day of 2 months ago"));
                $thisMonth = $this->dateToFrench($thisMonth , 'F');
                $lastMonth = $this->dateToFrench($lastMonth , 'F');


                $infoDetaillees['sommeLastMonth']   = $resultRaisonSocialeClick['sommeLastMonth'] + $resultApparitionRs1['sommeLastMonth'] + $resultPageAffichercord['sommeLastMonth'] + $resultAfficherLeNumero['sommeLastMonth'];
                $infoDetaillees['sommeThisMonth']   = $resultRaisonSocialeClick['sommeThisMonth'] + $resultApparitionRs1['sommeThisMonth'] + $resultPageAffichercord['sommeThisMonth'] + $resultAfficherLeNumero['sommeThisMonth'];
                if($sexe == "M"){
                    $sex="Monsieur";
                }else if($sexe == "F"){
                    $sex="Madame";
                }
               
                $subject = 'Bilan du mois';
                $message = '<html>';
                $message .= '<head>';
                $message .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $message .= '<title>Bilan du mois</title>';
                $message .= '</head>';
                $message .= '';
                $message .= '<body style="font-family: arial, sans-serif; font-size: 12px;">';
                $message .= '<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE" style="background-color: #EEEEEE; font-size:12px; font-family: arial, sans-serif;">';
                $message .= '<tr>';
                $message .= '<td align="center" valign="middle"><br />';
                $message .= '<table width="600" align="center" cellspacing="0">';
                $message .= '<tr bgcolor="#ffdd00">';
                $message .= '<tr bgcolor="#ffdd00">';
                $message .= '<td align="center" valign="middle" bgcolor="#ffdd00" style="background-color: #292828; border-bottom: 1px solid #EEEEEE;" height="80" colspan="2"><p style="font-weight: bold;text-align: center;margin-left: 10px;text-transform: uppercase;color: #ffdd00;font-size: 20px;line-height: 1px;">BILAN DU MOIS '.$thisMonth.'</p><p style="font-weight: bold;text-align: center;margin-left: 10px;color: white;font-size: 18PX;">Que s\'est-il passé ce mois sur votre profil entreprise ? </p></td>';
                $message .= '</tr>';
                $message .= '</tr>';
                $message .= '</table>';
                $message .= '<table width="600" align="center" cellspacing="0">';
                $message .= '<tr bgcolor="#ffdd00">';
                $message .= '<tr bgcolor="#ffdd00">';
                $message .= '<td align="left" valign="middle" bgcolor="#eeeeee" style="background-color: white; border-bottom: 1px solid white;" height="80" colspan="2">'; 
                $message .= '<table width="600" align="left" cellspacing="0">';
                $message .= '<tr bgcolor="#eeeeee">';
                $message .= '<td align="left" valign="middle" bgcolor="#eeeeee" style="background-color: #eeeeee; border-bottom: 1px solid white;" height="80" colspan="2">';
                $message .= '<table width="500" align="left" cellspacing="0">';
                $message .= '<tr bgcolor="#eeeeee">';
                $message .= '<td><div style="margin-top: 16px;margin-left: 21px;margin-right: 21px;text-align: left;font-size: 15px;color: #4a4848;">Bonjour '.$sex.' <strong>'.$nom .' '. $prenom.'</strong><p style="margin-bottom: 30px;margin-top:11px;">Nous vous invitons à découvrir ci-dessous le récapitulatif de consultation de votre profil entreprise sur  <a href="https://www.telecontact.ma">telecontact.ma</a></p></div>';        
                $message .= $Curl_result;
                $message.= '<div style="width: 595px;height: 325px;"><img src="https://www.telecontact.ma/trouver/media/images/articles/'.$code_firme.'_'.date('Y-m-d').'.png" style="margin-left: 48px;height: 285PX;width: 500px;" /></div> ';
                $message .= '</td>';    
                $message .= '</tr>';
                $message .= '</table>';
                $message .= '</td>';
                $message .= '</tr>';
                $message .= '</table>';

                $message .= ' <table width="600" align="left" cellspacing="0"><tbody><tr style="margin-top: 12px;"><ul style="margin-top: 18px;font-weight: bold;text-transform: uppercase;margin-left: -6px;">recherches liées à votre profil</ul></tr></tbody>';
                
                


                $message .= '<tr><table>';
                $message .= '<th>';
                $message .= '<table style=" width: 12px;margin-left: 31px;">';
                $message .= '<tr><th style="padding: 7px;text-align: left;background-color: gray;color: white;font-size: 13px;">Référencement</th><th style="padding: 7px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$lastMonth.'</th><th style="padding: 10px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$thisMonth.'</th></tr>';
                $message .= '<tr style="font-weight: normal;font-size: 14PX;">';

                if( $infoDetaillees['sommeLastMonth'] != 0 || $infoDetaillees['sommeThisMonth'] != 0 ){
                    $message .= '<td style="padding: 4px;text-align: left;font-weight: bold;font-size: 12px;">Infos détaillées<span style="color: red;">*</span></td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$infoDetaillees['sommeLastMonth'].' </td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$infoDetaillees['sommeThisMonth'].'</td></tr>';
                }
                if( $resultApparitionRs['sommeLastMonth'] != 0 || $resultApparitionRs['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;"> Votre profil sur la liste de résultats<span style="color: red;">**</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultApparitionRs['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultApparitionRs['sommeThisMonth'].'</td></tr>';
                }
                $message .= '</table>';
                $message .= '</th>';



                $message .= '<th>';
                $message .= '<table style=" width: 12px;">';
                $message .= '<tr><th style="padding: 7px;text-align: left;background-color: gray;color: white;font-size: 13px;    padding-right: 62px;">Affichage</th><th style="padding: 7px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$lastMonth.'</th><th style="padding: 10px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$thisMonth.'</th></tr>';
                $message .= '<tr style="font-weight: normal;font-size: 14PX;">';

                if( $resultVignetteRubrique['sommeLastMonth'] != 0 || $resultVignetteRubrique['sommeThisMonth'] != 0 ){
                    $message .= '<td style="padding: 4px;text-align: left;font-weight: bold;font-size: 12px;"> Vignette thématique<span style="color: red;">**</span></td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRubrique['sommeLastMonth'].' </td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRubrique['sommeThisMonth'].'</td></tr>';
                }
                if( $resultVignetteRegion['sommeLastMonth'] != 0 || $resultVignetteRegion['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;"> Vignette localité<span style="color: red;">**</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRegion['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRegion['sommeThisMonth'].'</td></tr>';
                }
                $message .= '</table>';
                $message .= '</th></table>';


                $message .= '<table style=" width: 12px;margin-left: 31px;">';
                $message .= '<tr><th style="padding: 7px;text-align: left;background-color: gray;color: white;font-size: 13px;    padding-right: 50px;">Contenu</th><th style="padding: 7px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$lastMonth.'</th><th style="padding: 10px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$thisMonth.'</th></tr>';
                $message .= '<tr style="font-weight: normal;font-size: 14PX;">';

                if( $resultSiteWeb['sommeLastMonth'] != 0 || $resultSiteWeb['sommeThisMonth'] != 0 ){
                    $message .= '<td style="padding: 4px;text-align: left;font-weight: bold;font-size: 12px;">Lien vers site<span style="color: red;">*</span></td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultSiteWeb['sommeLastMonth'].' </td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultSiteWeb['sommeThisMonth'].'</td></tr>';
                }
                if( $resultDecouvrirCatalogue['sommeLastMonth'] != 0 || $resultDecouvrirCatalogue['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Catalogue<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultDecouvrirCatalogue['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultDecouvrirCatalogue['sommeThisMonth'].'</td></tr>';
                }
                if( $resultVIDEO_Click['sommeLastMonth'] != 0 || $resultVIDEO_Click['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Vidéo<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVIDEO_Click['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVIDEO_Click['sommeThisMonth'].'</td></tr>';
                }
                if( $resultPVIClick['sommeLastMonth'] != 0 || $resultPVIClick['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">PVI<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPVIClick['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPVIClick['sommeThisMonth'].'</td></tr>';
                }
                if( $resultPage_Simple['sommeLastMonth'] != 0 || $resultPage_Simple['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Page<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPage_Simple['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPage_Simple['sommeThisMonth'].'</td></tr>';
                }
                if( $resultpack_presence['sommeLastMonth'] != 0 || $resultpack_presence['sommeThisMonth'] != 0 ){
                $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Plus d\'info<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultpack_presence['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultpack_presence['sommeThisMonth'].'</td></tr>';
                }
                $message .= '</table>';


                $message .= '<table width="600" align="center" cellspacing="0">';
                $message .= '<tr bgcolor="#ffffff">';
                $message .= '<td align="left" valign="middle" bgcolor="#ffffff" style="background-color: #ffffff; border-bottom: 1px solid white;" height="80" colspan="2">
                    <p style="margin-bottom: 12px;margin-left: 25px;margin-top: 12px;font-size: 12px;"><span style="color: red;">*</span> Nombre de clics<br><span style="color: red;">**</span> Nombre de parution</p>
                    <p style="margin-bottom: 12px;margin-left: 25px;margin-top: 12px;font-size: 12px;"><span style="color: red;">NB :</span> Plus de statistiques sur votre profil sont disponibles sur votre compte   <a href="https://www.telecontact.ma/se-connecter">telecontact.ma</a></p><td>';
                $message .= '</tr>';
                $message .= '</table>';
                 $message .= '<table width="600" align="left" cellspacing="0">';
                $message .= '<tr bgcolor="#eeeeee">';
                $message .= '<td style="padding-bottom: 26px;"><div style="margin-top: 16px;margin-left: 21px;margin-right: 21px;text-align: left;font-size: 15px;color: #4a4848;"><p style="margin-bottom: 30px;margin-top:11px;text-align: center;font-size: 15px;">Nous restons à votre disposition pour toute étude d\'amélioration de votre visibilité.</p></div>'; 
                $message .= '<a type="button" style="background-color:#ffdd00;border:1px solid #ffdd00;font-size:15px;padding:10px 15px 15px;font-weight: bold;margin-left: 221px;margin-top: -15px;margin-bottom: 14px;" href="https://www.telecontact.ma/retour-emailing">Contactez-nous</a>';       
                $message .= '</td>';    
                $message .= '</tr>';
                $message .= '</table>';
                $message .= '<table width="600" align="center" cellspacing="0" bgcolor="#000000">';
                $message .= '<td bgcolor="#000000">';
                $message.= '<table><tr><th style="color:white;padding-right: 79px;">Telecontact.ma</th><th style="color:white;padding-right: 17px;">'.number_format($data_get_record[0]['record_session'], 0, ',', ' ').' <br><span style="font-size: 13px;    font-weight: normal !important;">Visites/Jour</span></th><th style="color:white;padding-right: 17px;">'.number_format($resultPageViews['pageviews'][0][0], 0, ',', ' ').'<br><span style="font-size: 13px;font-weight: normal !important;">Pages vues / Mois</span></th><th style="color:white;">'.number_format($rank).'<br><span style="font-size: 13px;font-weight: normal !important;">Classement Alexa</span></th></tr></table>';
                $message .= '</td>';
                $message .= '</table>';
                $message .= '</tr>';
                $message .= '</table>';
                $message .= '</td>';
                $message .= '</tr>';
                $message .= '</tr>';
                $message .= '</table>';
                $message .= '</tr>';
                $message .= '</table>';
                $message .= '</body>';
                return $this->render('EcommerceBundle:Default:statistic/index.html.twig', array('nom' => $nom, 'prenom' => $prenom, 'email' =>$email, 'code_firme' => $code_firme, 'sexe' => $sexe, 'message' => $message));
    }

  
    public function getResultGoogleAnalyticsAction(Request $request)
    {
            $code_firme = $request->request->get('code_firme');
            $code_firme =  str_replace('MA', '', $code_firme);
            $code_firme = str_replace(' ', '', $code_firme);
            $this->get('session')->set('codeFirmeImage', $code_firme);

            $email = $request->request->get('email');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $sexe = $request->request->get('sex');

            $resultVignetteRubrique  = $this->requetVignettes($code_firme,'vignettes','rubrique');
            $resultVignetteRegion    = $this->requetVignettes($code_firme,'vignettes','region');
            $resultApparitionRs      = $this->requetStatistic($code_firme,'apparition_rs');
            $resultApparitionRs1     = $this->requetStatistic($code_firme,'fiche_apparition');
            $resultRaisonSocialeClick= $this->requetStatistic($code_firme,'Raison_sociale_click'); 
            $resultAfficherLeNumero  = $this->requetStatistic($code_firme,'Afficher_Le_Numero');
            $resultSiteWeb           = $this->requetStatistic($code_firme,'Site_Web');
            $resultNombreMailsReçus  = $this->requetNombreMail($code_firme); 
            $resultDecouvrirCatalogue= $this->requetStatistic($code_firme,'Decouvrir_Catalogue');
            $resultPVIClick          = $this->requetStatistic($code_firme,'PVI_Click');
            $resultPageAffichercord  = $this->requetStatistic($code_firme,'Page_Afficher_cord');

            $resultpack_presence     = $this->requetStatistic($code_firme,'pack_presence');
            $resultPage_Simple       = $this->requetStatistic($code_firme,'Page_Simple');
            $resultVIDEO_Click       = $this->requetStatistic($code_firme,'VIDEO_Click');

            try{
              $ch = curl_init();
              if (FALSE === $ch){
                throw new Exception('failed to initialize');
              }
              curl_setopt($ch, CURLOPT_URL,"https://www.telecontact.ma/telecontact-analytics-emailing/".$code_firme);
              
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
              curl_setopt($ch, CURLOPT_POSTREDIR, 3);                                                                  
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                 
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow http 3xx redirects
              $Curl_result = curl_exec($ch); // execute
              
            }catch(Exception $e) {
              trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
            }

            
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $dirigeant = $connection->executeQuery("SELECT `code_firme` ,`fonction` , `nom` ,sex, personne.`prenom` FROM ( SELECT `id` , `code_personne` , `code_firme` , `code_fonction` FROM `lien_dirigeant` WHERE `code_firme` LIKE 'MA".$code_firme."' AND `code_fonction` NOT LIKE '0%' UNION SELECT * FROM `lien_dirigeant_sec` WHERE `code_firme` LIKE 'MA".$code_firme."' AND `code_fonction` NOT LIKE '0%' ORDER BY `code_fonction` ASC LIMIT 1 ) AS B, `personne` , fonction WHERE `personne`.code_personne = B.code_personne AND fonction.code = B.`code_fonction`");
            $resultdirigeant = $dirigeant->fetchAll();

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $sql_get_record = $connection->executeQuery("SELECT record_session, page_vue, users, record_date, update_date FROM telecontact_record WHERE id=1");
            $data_get_record = $sql_get_record->fetchAll();
            $resultPageViews= $this->requetPageVue('pageviews');
            $rank = $this->AlexaRank("telecontact.ma", "Morocco", "country");

            //l'envoi du mail
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'FROM:' . htmlspecialchars('edicom.telecontact@gmail.com') . "\r\n";
            $headers .= 'Bcc: edicom.telecontact@gmail.com' . "\r\n";
            $to = $email;
           
             $this->envoi_email($headers,$to,$resultApparitionRs,$resultApparitionRs1,$resultRaisonSocialeClick,$resultAfficherLeNumero,$resultSiteWeb,$resultNombreMailsReçus,$resultDecouvrirCatalogue,$resultPVIClick,$resultPageAffichercord,$resultVignetteRubrique,$resultVignetteRegion,$Curl_result,$resultdirigeant,$code_firme,$data_get_record,$resultPageViews,$rank,$resultpack_presence,$resultPage_Simple,$resultVIDEO_Click,$nom,$prenom,$sexe,$request); 
            
    }

    public function AlexaRank($domain, $country, $mode) {
        $url = "https://www.alexa.com/minisiteinfo/".$domain;
        $string = file_get_contents($url);
        if ($mode == "country") {
            $temp_s = substr($string, strpos($string, $country." Flag") + 9 + strlen($country));
            return(substr($temp_s, 0, strpos($temp_s, "</a></div>")));
        }
        else if ($mode == "global") {
            $temp_s = substr($string, strpos($string, "Global") + 38);
            return(substr($temp_s, 0, strpos($temp_s, "</a></div>")));
        }
        else {
            return('something wrong.');
        }
    }

    public function requetPageVue($category){
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';
            //apparition_rs
            $query = new Query($profileId);

            $query->setStartDate( new \DateTime('first day of last month'));
            $query->setEndDate( new \DateTime('last day of last month'));
           
            $query->setMetrics(array('ga:pageviews'));

             
            $query->setStartIndex(1);
            $query->setPrettyPrint(false);
            $query->setCallback(null);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $result = $response->getRows();
             return array('pageviews' => $result);
    }

    public function requetNombreMail($code_firme){
        $today = date("Y-m-d");
        $lastDayOfLastMonth = date("Y-m-d", strtotime("last day of -2 month",strtotime($today)));
        $firstDayOfLastMonth = date("Y-m-d",strtotime("first day of -2 month",strtotime($today)));
        $lastDayOfThisMonth = date("Y-m-d", strtotime("last day of -1 month",strtotime($today)));
        $firstDayOfThisMonth = date("Y-m-d", strtotime("first day of -1 month",strtotime($today)));

        $connectionFactory = $this->get('doctrine.dbal.connection_factory');
        $hostname='localhost';
        $dbname='telecontact_BackOffice_Site';
        $username='pyxicom';
        $password='Yz9nVEXjZ2hqptZT';
        $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
        $nombreMailthisMonth = $connection->executeQuery("SELECT Count(*) as somme FROM `Demande_devis_Details` WHERE `Cfirme` LIKE ".$code_firme." AND  (Date_Envoi BETWEEN '".$firstDayOfThisMonth."' AND '".$lastDayOfThisMonth."')");
        $resultNombreMailthisMonth = $nombreMailthisMonth->fetchAll();


     
        $nombreMailLastMonth = $connection->executeQuery("SELECT Count(*) as somme FROM `Demande_devis_Details` WHERE `Cfirme` LIKE ".$code_firme." AND  (Date_Envoi BETWEEN '".$firstDayOfLastMonth."' AND '".$lastDayOfLastMonth."')");
        $resultNombreMailLastMonth = $nombreMailLastMonth->fetchAll();

    
        
        return array('sommeLastMonth' => $resultNombreMailLastMonth[0]['somme'], 'sommeThisMonth' => $resultNombreMailthisMonth[0]['somme'], 'tauxEvolution' => 0);   
    }

    public function requetVignettes($code_firme,$category,$action){
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';
            //apparition_rs
            $query = new Query($profileId);

            $query->setStartDate( new \DateTime('first day of -2 month'));
            $query->setEndDate( new \DateTime('last day of -1 month'));
           
            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:eventLabel','ga:date'));

            $query->setFilters(array('ga:eventLabel=@'.$code_firme.';ga:eventCategory=='.$category.';ga:eventAction=='.$action));
             
            $query->setStartIndex(1);
            $query->setPrettyPrint(false);
            $query->setCallback(null);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $result = $response->getRows();
            
            $somme1=0;
            $somme2=0;
            if(!empty($result)){
                $months_in = substr($result[0][1], 4 ,-2);
                $days_in = substr($result[0][1], 6); 
                $months_sec = $months_in+1;
                foreach ($result as $key => $value) {
                    //var_dump($value[1]); 
                    $months_this=substr($value[1], 4 ,-2);
                    $days_this = substr($value[1], 6);
                    if($months_in == $months_this){
                        $somme1=$somme1+$value[2];
                    }else{
                        if($months_sec == $months_this){
                          if($days_in >= $days_this){
                            $somme1 = $somme1 + $value[2];
                          }else{
                            $somme2=$somme2+$value[2];
                          }
                        }else{
                            $somme2=$somme2+$value[2];
                        }
                    }
                }
                $sommeLastMonth = $somme1;
                $sommeThisMonth = $somme2;
                if($somme2 >0){
                     $tauxEvolution = (($somme1 - $somme2) / $somme2)*100 ;
                 
                }else{
                     $tauxEvolution = 0;
                }
                

                    return array('sommeLastMonth' => $sommeLastMonth, 'sommeThisMonth' => $sommeThisMonth, 'tauxEvolution' => $tauxEvolution);
            }else{
                return array('sommeLastMonth' => 0, 'sommeThisMonth' => 0, 'tauxEvolution' => 0);
            }
    }    

    public function requetStatistic($code_firme,$category){
         $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';
            //apparition_rs
            $query = new Query($profileId);

            $query->setStartDate( new \DateTime('first day of -2 month'));
            $query->setEndDate( new \DateTime('last day of -1 month'));
           
            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:eventLabel','ga:date'));

            $query->setFilters(array('ga:eventLabel=@'.$code_firme.';ga:eventCategory=='.$category));
             
            $query->setStartIndex(1);
            $query->setPrettyPrint(false);
            $query->setCallback(null);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $result = $response->getRows();
            
            $somme1=0;
            $somme2=0;
            if(!empty($result)){
                $months_in = substr($result[0][1], 4 ,-2);
                $days_in = substr($result[0][1], 6); 
                $months_sec = $months_in+1;
                foreach ($result as $key => $value) {
                    //var_dump($value[1]); 
                    $months_this=substr($value[1], 4 ,-2);
                    $days_this = substr($value[1], 6);
                    if($months_in == $months_this){
                        $somme1=$somme1+$value[2];
                    }else{
                        if($months_sec == $months_this){
                          if($days_in >= $days_this){
                            $somme1 = $somme1 + $value[2];
                          }else{
                            $somme2=$somme2+$value[2];
                          }
                        }else{
                            $somme2=$somme2+$value[2];
                        }
                    }
                }
                $sommeLastMonth = $somme1;
                $sommeThisMonth = $somme2;
                if($somme2 >0){
                     $tauxEvolution = (($somme1 - $somme2) / $somme2)*100 ;
                 
                }else{
                     $tauxEvolution = 0;
                }
                

                    return array('sommeLastMonth' => $sommeThisMonth, 'sommeThisMonth' => $sommeLastMonth, 'tauxEvolution' => $tauxEvolution);
            }else{
                return array('sommeLastMonth' => 0, 'sommeThisMonth' => 0, 'tauxEvolution' => 0);
            }
    }

    public function envoi_email($headers,$to,$resultApparitionRs,$resultApparitionRs1,$resultRaisonSocialeClick,$resultAfficherLeNumero,$resultSiteWeb,$resultNombreMailsReçus,$resultDecouvrirCatalogue,$resultPVIClick,$resultPageAffichercord,$resultVignetteRubrique,$resultVignetteRegion,$Curl_result,$resultdirigeant,$code_firme,$data_get_record,$resultPageViews,$rank,$resultpack_presence,$resultPage_Simple,$resultVIDEO_Click,$nom,$prenom,$sexe,$request)
    {

        setlocale(LC_TIME, "fr_FR");
        $today = date("Y-m-d");
        $thisMonth = date("F", strtotime("last day of -1 month",strtotime($today)));
        $lastMonth = date("F", strtotime("last day of -2 month",strtotime($today)));
        $thisMonth = $this->dateToFrench($thisMonth , 'F');
        $lastMonth = $this->dateToFrench($lastMonth , 'F');


        $infoDetaillees['sommeLastMonth']   = $resultRaisonSocialeClick['sommeLastMonth'] + $resultApparitionRs1['sommeLastMonth'] + $resultPageAffichercord['sommeLastMonth'] + $resultAfficherLeNumero['sommeLastMonth'];
        $infoDetaillees['sommeThisMonth']   = $resultRaisonSocialeClick['sommeThisMonth'] + $resultApparitionRs1['sommeThisMonth'] + $resultPageAffichercord['sommeThisMonth'] + $resultAfficherLeNumero['sommeThisMonth'];
        if($sexe == "M"){
            $sex="Monsieur";
        }else if($sexe == "F"){
            $sex="Madame";
        }
       
        $subject = 'Bilan du mois';
        $message = '<html>';
        $message .= '<head>';
        $message .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $message .= '<title>Bilan du mois</title>';
        $message .= '</head>';
        $message .= '';
        $message .= '<body style="font-family: arial, sans-serif; font-size: 12px;">';
        $message .= '<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE" style="background-color: #EEEEEE; font-size:12px; font-family: arial, sans-serif;">';
        $message .= '<tr>';
        $message .= '<td align="center" valign="middle"><br />';
        $message .= '<table width="600" align="center" cellspacing="0">';
        $message .= '<tr bgcolor="#ffdd00">';
        $message .= '<tr bgcolor="#ffdd00">';
        $message .= '<td align="center" valign="middle" bgcolor="#ffdd00" style="background-color: #292828; border-bottom: 1px solid #EEEEEE;" height="80" colspan="2"><p style="font-weight: bold;text-align: center;margin-left: 10px;text-transform: uppercase;color: #ffdd00;font-size: 20px;line-height: 1px;">BILAN DU MOIS '.$thisMonth.'</p><p style="font-weight: bold;text-align: center;margin-left: 10px;color: white;font-size: 18PX;">Que s\'est-il passé ce mois sur votre profil entreprise ? </p></td>';
        $message .= '</tr>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<table width="600" align="center" cellspacing="0">';
        $message .= '<tr bgcolor="#ffdd00">';
        $message .= '<tr bgcolor="#ffdd00">';
        $message .= '<td align="left" valign="middle" bgcolor="#eeeeee" style="background-color: white; border-bottom: 1px solid white;" height="80" colspan="2">'; 
        $message .= '<table width="600" align="left" cellspacing="0">';
        $message .= '<tr bgcolor="#eeeeee">';
        $message .= '<td align="left" valign="middle" bgcolor="#eeeeee" style="background-color: #eeeeee; border-bottom: 1px solid white;" height="80" colspan="2">';
        $message .= '<table width="500" align="left" cellspacing="0">';
        $message .= '<tr bgcolor="#eeeeee">';
        $message .= '<td><div style="margin-top: 16px;margin-left: 21px;margin-right: 21px;text-align: left;font-size: 15px;color: #4a4848;">Bonjour '.$sex.' <strong>'.$nom .' '. $prenom.'</strong><p style="margin-bottom: 30px;margin-top:11px;">Nous vous invitons à découvrir ci-dessous le récapitulatif de consultation de votre profil entreprise sur  <a href="https://www.telecontact.ma">telecontact.ma</a></p></div>';        
        $message .= $Curl_result;
        $message.= '<div style="width: 595px;height: 325px;"><img src="https://www.telecontact.ma/trouver/media/images/articles/'.$code_firme.'_'.date('Y-m-d').'.png" style="margin-left: 48px;height: 285PX;width: 500px;" /></div> ';
        $message .= '</td>';    
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $message .= ' <table width="600" align="left" cellspacing="0"><tbody><tr style="margin-top: 12px;"><ul style="margin-top: 18px;font-weight: bold;text-transform: uppercase;margin-left: -6px;">recherches liées à votre profil</ul></tr></tbody>';
        
        


        $message .= '<tr><table>';
        $message .= '<th>';
        $message .= '<table style=" width: 12px;margin-left: 31px;">';
        $message .= '<tr><th style="padding: 7px;text-align: left;background-color: gray;color: white;font-size: 13px;">Référencement</th><th style="padding: 7px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$lastMonth.'</th><th style="padding: 10px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$thisMonth.'</th></tr>';
        $message .= '<tr style="font-weight: normal;font-size: 14PX;">';

        if( $infoDetaillees['sommeLastMonth'] != 0 || $infoDetaillees['sommeThisMonth'] != 0 ){
            $message .= '<td style="padding: 4px;text-align: left;font-weight: bold;font-size: 12px;">Infos détaillées<span style="color: red;">*</span></td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$infoDetaillees['sommeLastMonth'].' </td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$infoDetaillees['sommeThisMonth'].'</td></tr>';
        }
        if( $resultApparitionRs['sommeLastMonth'] != 0 || $resultApparitionRs['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;"> Votre profil sur la liste de résultats<span style="color: red;">**</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultApparitionRs['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultApparitionRs['sommeThisMonth'].'</td></tr>';
        }
        $message .= '</table>';
        $message .= '</th>';



        $message .= '<th>';
        $message .= '<table style=" width: 12px;">';
        $message .= '<tr><th style="padding: 7px;text-align: left;background-color: gray;color: white;font-size: 13px;    padding-right: 62px;">Affichage</th><th style="padding: 7px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$lastMonth.'</th><th style="padding: 10px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$thisMonth.'</th></tr>';
        $message .= '<tr style="font-weight: normal;font-size: 14PX;">';

        if( $resultVignetteRubrique['sommeLastMonth'] != 0 || $resultVignetteRubrique['sommeThisMonth'] != 0 ){
            $message .= '<td style="padding: 4px;text-align: left;font-weight: bold;font-size: 12px;"> Vignette thématique<span style="color: red;">**</span></td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRubrique['sommeLastMonth'].' </td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRubrique['sommeThisMonth'].'</td></tr>';
        }
        if( $resultVignetteRegion['sommeLastMonth'] != 0 || $resultVignetteRegion['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;"> Vignette localité<span style="color: red;">**</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRegion['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVignetteRegion['sommeThisMonth'].'</td></tr>';
        }
        $message .= '</table>';
        $message .= '</th></table>';


        $message .= '<table style=" width: 12px;margin-left: 31px;">';
        $message .= '<tr><th style="padding: 7px;text-align: left;background-color: gray;color: white;font-size: 13px;    padding-right: 50px;">Contenu</th><th style="padding: 7px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$lastMonth.'</th><th style="padding: 10px;text-align: left;background-color: #ffdd00;font-size: 12px;">'.$thisMonth.'</th></tr>';
        $message .= '<tr style="font-weight: normal;font-size: 14PX;">';

        if( $resultSiteWeb['sommeLastMonth'] != 0 || $resultSiteWeb['sommeThisMonth'] != 0 ){
            $message .= '<td style="padding: 4px;text-align: left;font-weight: bold;font-size: 12px;">Lien vers site<span style="color: red;">*</span></td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultSiteWeb['sommeLastMonth'].' </td><td style="padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultSiteWeb['sommeThisMonth'].'</td></tr>';
        }
        if( $resultDecouvrirCatalogue['sommeLastMonth'] != 0 || $resultDecouvrirCatalogue['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Catalogue<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultDecouvrirCatalogue['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultDecouvrirCatalogue['sommeThisMonth'].'</td></tr>';
        }
        if( $resultVIDEO_Click['sommeLastMonth'] != 0 || $resultVIDEO_Click['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Vidéo<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVIDEO_Click['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultVIDEO_Click['sommeThisMonth'].'</td></tr>';
        }
        if( $resultPVIClick['sommeLastMonth'] != 0 || $resultPVIClick['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">PVI<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPVIClick['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPVIClick['sommeThisMonth'].'</td></tr>';
        }
        if( $resultPage_Simple['sommeLastMonth'] != 0 || $resultPage_Simple['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Page<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPage_Simple['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultPage_Simple['sommeThisMonth'].'</td></tr>';
        }
        if( $resultpack_presence['sommeLastMonth'] != 0 || $resultpack_presence['sommeThisMonth'] != 0 ){
        $message .= '<tr style="font-weight: normal;font-size: 14PX;"><td style=" padding: 4px;text-align: left;    font-weight: bold;font-size: 12px;">Plus d\'info<span style="color: red;">*</span></td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultpack_presence['sommeLastMonth'].' </td><td style=" padding: 7px;text-align: left;background-color: #80808033;font-size: 12px;">'.$resultpack_presence['sommeThisMonth'].'</td></tr>';
        }
        $message .= '</table>';


        $message .= '<table width="600" align="center" cellspacing="0">';
        $message .= '<tr bgcolor="#ffffff">';
        $message .= '<td align="left" valign="middle" bgcolor="#ffffff" style="background-color: #ffffff; border-bottom: 1px solid white;" height="80" colspan="2">
            <p style="margin-bottom: 12px;margin-left: 25px;margin-top: 12px;font-size: 12px;"><span style="color: red;">*</span> Nombre de clics<br><span style="color: red;">**</span> Nombre de parution</p>
            <p style="margin-bottom: 12px;margin-left: 25px;margin-top: 12px;font-size: 12px;"><span style="color: red;">NB :</span> Plus de statistiques sur votre profil sont disponibles sur votre compte   <a href="https://www.telecontact.ma/se-connecter">telecontact.ma</a></p><td>';
        $message .= '</tr>';
        $message .= '</table>';
         $message .= '<table width="600" align="left" cellspacing="0">';
        $message .= '<tr bgcolor="#eeeeee">';
        $message .= '<td style="padding-bottom: 26px;"><div style="margin-top: 16px;margin-left: 21px;margin-right: 21px;text-align: left;font-size: 15px;color: #4a4848;"><p style="margin-bottom: 30px;margin-top:11px;text-align: center;font-size: 15px;">Nous restons à votre disposition pour toute étude d\'amélioration de votre visibilité.</p></div>'; 
        $message .= '<a type="button" style="background-color:#ffdd00;border:1px solid #ffdd00;font-size:15px;padding:10px 15px 15px;font-weight: bold;margin-left: 221px;margin-top: -15px;margin-bottom: 14px;" href="https://www.telecontact.ma/retour-emailing">Contactez-nous</a>';       
        $message .= '</td>';    
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<table width="600" align="center" cellspacing="0" bgcolor="#000000">';
        $message .= '<td bgcolor="#000000">';
        $message.= '<table><tr><th style="color:white;padding-right: 79px;">Telecontact.ma</th><th style="color:white;padding-right: 17px;">'.number_format($data_get_record[0]['record_session'], 0, ',', ' ').' <br><span style="font-size: 13px;    font-weight: normal !important;">Visites/Jour</span></th><th style="color:white;padding-right: 17px;">'.number_format($resultPageViews['pageviews'][0][0], 0, ',', ' ').'<br><span style="font-size: 13px;font-weight: normal !important;">Pages vues / Mois</span></th><th style="color:white;">'.number_format($rank).'<br><span style="font-size: 13px;font-weight: normal !important;">Classement Alexa</span></th></tr></table>';
        $message .= '</td>';
        $message .= '</table>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</body>';
        
        $var= mail($to, $subject, $message, $headers);
        
        if($var){
        echo '<a href="http://www.e-contact.telecontact.ma/user/statistique/sendEmail"  style="width : 119px !important;">
                           < Retour ( chemins Envoi Statistique )
                        </a>';

        echo $message ;
        $user = $this->getUser();
        $statistics = new Statistics();
        $statistics->setCodeFirme($code_firme);
        $statistics->setNom($nom);
        $statistics->setTime(new \DateTime('now'));
        $statistics->setPrenom($prenom);
        $statistics->setSexe($sex);
        $statistics->setEmail($to);
        $statistics->setCodeCommercial($user->getUsername());
        $statistics->setIdCommercial($user->getId());
        $statistics->setStatus(0);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')->select('u.nom')
                /*  ->groupBy('u.groupe')*/
                ->where('u.id =  :group')
                ->setParameter('group', $user->getId())
                ->getQuery()->getResult();
        $statistics->setNomCommercial($query[0]['nom']);
        $statistics->setMsg($message);
        $em->persist($statistics);
        $em->flush();

        /*$msg = ""; 
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($list_client);
        $stmt->execute();*/
        }
        else{
        echo 'message non envoyé :(';
        }
        
        
        die('');
    }

    // Convert a date or timestamp into French.
    public static function dateToFrench($date, $format) 
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
    }

    public function putImageAction (Request $request){
        $code_firme=$request->request->get('code_firme');
        $image_checking=$request->request->get('registration');
        if($image_checking){
            $rs = $this->base64ToImage($image_checking,$code_firme);
            echo 'success :)';
        }
        else{
        	$code_firme = $this->get('session')->get('codeFirmeImage');
        	$file = '/var/www/prod/telecontactV2/telecontact/trouver/media/images/actualites/imageVide.png';
			$newfile = '/var/www/prod/telecontactV2/telecontact/trouver/media/images/articles/'.$code_firme.'_'.date('Y-m-d').'.png';
			 
			if (!copy($file, $newfile)) {
			    echo "failed to copy $file...\n";
			}else{
			    echo "copied $file into $newfile\n";
			}
            echo':(';
        }
            die();  
    }

    public function base64ToImage($image_checking,$code_firme) {

       $url= '/var/www/prod/telecontactV2/telecontact/trouver/media/images/articles/'.$code_firme.'_'.date('Y-m-d').'.png';

       $file = __DIR__.'/images/'.$code_firme.'_'.date('Y-m-d').'.png';
       echo $url;
       if (is_writable($url)) {
            echo 'Le fichier est accessible en écriture.';
        } else {
            echo 'Le fichier n\'est pas accessible en écriture !';
        }
        $handle = fopen($url, 'wb');
        $data = explode(',', $image_checking);

        fwrite($handle, base64_decode($data[1]));
        fclose($handle);
    }

    public function showAction(Request $request) {
        ini_set('memory_limit', '-1');

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('EcommerceBundle:Statistics')->createQueryBuilder('t')->select('t')
            ->orderBy('t.time', 'desc')
            ->getQuery()->getResult();
        return $this->render('EcommerceBundle:Administration:statistics/index.html.twig', array(
            'statistics' => $query,
        ));
     }

    public function msgWebAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery('SELECT p
            FROM EcommerceBundle:Statistics p
            WHERE p.id = :id')->setParameter('id', $id);

        $products = $query->getResult();
        foreach ($products as $key => $value) {
            $message = $value->getMsg();
            echo '<div class="icon-retour retour"><a style="color: #2eb2ff; " href="http://preprod.telecontact.ma/trouver/e-contact/web/app_dev.php/user/email/showStatistic">< Retour  ( chemins des onglets parcourus )</a></div>';
             echo $message;die();
        
        }    
    }

    public function statisticAction() {
        $this->get('session')->getFlashBag()->clear();
        $em = $this->getDoctrine()->getManager();
        $by_tr=$this->container->get('security.context')->getToken()->getUser()->getUsername();
        //var_dump($by_tr);die('ici');
        if($by_tr != null)
        {
            $query = $em->getRepository('EcommerceBundle:Statistics')->createQueryBuilder('t')->select('t')
                ->where('t.code_commercial = :user')->orderBy('t.time', 'DESC')
                ->setParameter('user',$by_tr)
                ->getQuery()->getResult();
            return $this->render('EcommerceBundle:Administration:statistics/statisticById.html.twig', array(
                'statisticbyid' => $query,
            ));
        }
        else
        {
            return $this->render('EcommerceBundle:Administration:statistics/statisticById.html.twig');
        }
    }

    public function statisticBygroupAction(){
        $em = $this->getDoctrine()->getManager();
        $by_tr=$this->container->get('security.context')->getToken()->getUser()->getId();
        if($by_tr == 4) {
            $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                    ->where('t.groupe like  :group')
                    ->setParameter('group', '%televente%')
                    ->getQuery()->getResult();
            $query_f = $em->getRepository('EcommerceBundle:Statistics')->createQueryBuilder('t')->select('t')
                    ->where('t.id_commercial in (:user)')
                    ->setParameter('user',$query)
                    ->orderBy('t.time', 'desc')
                    ->getQuery()->getResult();
        return $this->render('EcommerceBundle:Administration:Group/statisticByGroupCommercial.html.twig', array(
                    'statisticbygroup' => $query_f,
                )); 
        }elseif($by_tr == 9) {
                $groupe = 'Alami';
        }elseif($by_tr == 77) {
                $groupe = 'televente';
        }elseif($by_tr == 52) {
                $groupe = 'Kilaouy';
        }elseif($by_tr == 25) {
                $groupe = 'Chraibi';
        }elseif($by_tr == 32) {
                $groupe = 'Benzahra';         
        }
    
        $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                    ->where('t.groupe like  :group')
                    ->setParameter('group', '%'.$groupe.'%')
                    ->getQuery()->getResult();
        $query_f = $em->getRepository('EcommerceBundle:Statistics')->createQueryBuilder('t')->select('t')
                    ->where('t.id_commercial in (:user)')
                    ->setParameter('user',$query)
                    ->orderBy('t.time', 'desc')
                    ->getQuery()->getResult();
        return $this->render('EcommerceBundle:Administration:Group/statisticByGroupCommercial.html.twig', array(
                    'statisticbygroup' => $query_f,
                ));
    }

    public function retourStatisticsAction(){
        $connectionFactory = $this->get('doctrine.dbal.connection_factory');
        $hostname='localhost';
        $dbname='telecontact_BackOffice_Site';
        $username='pyxicom';
        $password='Yz9nVEXjZ2hqptZT';
        $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
        $retour = $connection->executeQuery("SELECT * FROM  _contactannonce_retour");
        $data_get_retour = $retour->fetchAll();
         return $this->render('EcommerceBundle:Administration:statistics/indexretour.html.twig', array(
                    'dataretour' => $data_get_retour,
                ));
    }

    public function showretourStatisticsAction($id){
        $connectionFactory = $this->get('doctrine.dbal.connection_factory');
        $hostname='localhost';
        $dbname='telecontact_BackOffice_Site';
        $username='pyxicom';
        $password='Yz9nVEXjZ2hqptZT';
        $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
        $retour = $connection->executeQuery("SELECT * FROM  _contactannonce_retour where _oid =".$id);
        $data_get_retour = $retour->fetchAll();
         return $this->render('EcommerceBundle:Administration:statistics/showretour.html.twig', array(
                    'entity' => $data_get_retour,
                ));
    }

    public function sendCopieEmailAction(Request $request){
        $email=$request->request->get('email');
        $nom=$request->request->get('nom');
        $prenom=$request->request->get('prenom');
        $sexe=$request->request->get('sex');
        $id=$request->request->get('idEntity');
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'FROM:' . htmlspecialchars('edicom.telecontact@gmail.com') . "\r\n";
        $headers .= 'Bcc: edicom.telecontact@gmail.com' . "\r\n";
        $to = $email;
        $subject = 'Bilan du mois';
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery('SELECT p
            FROM EcommerceBundle:Statistics p
            WHERE p.id = :id')->setParameter('id', $id);

        $products = $query->getResult();
        foreach ($products as $key => $value) {
            $message = $value->getMsg();
            $code_firme = $value->getCodeFirme();
            $var= mail($to, $subject, $message, $headers);
        }
        if($sexe == "M"){
            $sex="Monsieur";
        }else if($sexe == "F"){
            $sex="Madame";
        }

        $user = $this->getUser();
        $statistics = new Statistics();
        $statistics->setCodeFirme($code_firme);
        $statistics->setNom($nom);
        $statistics->setTime(new \DateTime('now'));
        $statistics->setPrenom($prenom);
        $statistics->setSexe($sex);
        $statistics->setEmail($to);
        $statistics->setCodeCommercial($user->getUsername());
        $statistics->setIdCommercial($user->getId());
        $statistics->setStatus(1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')->select('u.nom')
                /*  ->groupBy('u.groupe')*/
                ->where('u.id =  :group')
                ->setParameter('group', $user->getId())
                ->getQuery()->getResult();
        $statistics->setNomCommercial($query[0]['nom']);
        $statistics->setMsg($message);
        $em->persist($statistics);
        $em->flush();

        $em = $this->getDoctrine()->getManager();
        $by_tr=$this->container->get('security.context')->getToken()->getUser()->getUsername();
        //var_dump($by_tr);die('ici');
        if($by_tr != null)
        {
            $query = $em->getRepository('EcommerceBundle:Statistics')->createQueryBuilder('t')->select('t')
                ->where('t.code_commercial = :user')->orderBy('t.time', 'DESC')
                ->setParameter('user',$by_tr)
                ->getQuery()->getResult();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Mail envoyé !'
            );
            return $this->render('EcommerceBundle:Administration:statistics/statisticById.html.twig', array(
                'statisticbyid' => $query,
            ));
        }
        else
        {
            return $this->render('EcommerceBundle:Administration:statistics/statisticById.html.twig');
        }
    }

    public function downloadEmailAction($id){
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'FROM:' . htmlspecialchars('edicom.telecontact@gmail.com') . "\r\n";
        $headers .= 'Bcc: edicom.telecontact@gmail.com' . "\r\n";
        $subject = 'Bilan du mois';
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery('SELECT p
            FROM EcommerceBundle:Statistics p
            WHERE p.id = :id')->setParameter('id', $id);

        $products = $query->getResult();
        foreach ($products as $key => $value) {
            $message = $value->getMsg();
            
        }
        return $this->render('EcommerceBundle:Administration:statistics/downloadEmail.html.twig', array(
                'message' => $message,
            ));
    }


    public function testAction(Request $request){

        

        return $this->render('EcommerceBundle:Administration:statistics/test.html.twig', array(
                'resultItineraire' => $resultItineraire
            ));
     }



     public function baseAction(Request $request){






     return $this->render('layout/base_statistics.html.twig',array('data_vignette' => $data_vignette));
     }


     public function telecontact_dataAction(Request $request, $code_firme){
        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


            $session = $request->getSession();
            $session->set('cf', $code_firme);


            /* send raison sociale */
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname1='localhost';
                $dbname1='espace_clients';
                $username1='pyxicom';
                $password1='Yz9nVEXjZ2hqptZT';
                $connection1 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname1;dbname=$dbname1",$username1,$password1)));


            $raison_sociale = $connection1->fetchAll("
                    SELECT raison_sociale FROM `utilisateurs` 
                    WHERE code_firme = '$code_firme'
                    ");

            if(empty($raison_sociale)){
                $MAcode_firme = 'MA'.$code_firme;
                $raison_sociale = $connection1->fetchAll("
                    SELECT f.`rs_comp` as raison_sociale
                    FROM  BD_EDICOM.`firmes` f
                    WHERE f.`code_firme` = '$MAcode_firme'
                    ");
            }

            $rs = utf8_encode($raison_sociale[0]['raison_sociale']);
            $cf = array('code_firme' => $code_firme); 
            $rs = array('raison_sociale' => $rs);

            $session=$request->getSession();
            $data_user = $session->get('utilisateur');
            $data_user[0] = array_merge($data_user[0], $cf, $rs);
            $this->get('session')->set('data_user' , $data_user);

            

            $hostname='46.182.7.30';
            $dbname='erpprod';
            $username='edicom';
            $password='dnSQ8Tg5HRYNwpli';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $vignette = $connection->executeQuery("SELECT acc.code_firme,acc.raison_sociale,s.date_ordre,code_produit
                    FROM erpprod.u_yf_ssingleorders s
                    INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                    INNER join erpprod.u_yf_ssingleorders_inventory i on i.id=s.ssingleordersid
                    INNER join erpprod.vtiger_service se on se.serviceid=i.name
                    WHERE s.support='telecontact' AND s.type_ordre='Internet' AND code_produit in('VA','VB','V0','V1') AND acc.code_firme=$code_firme
                    group by s.num_ordre");
                
            $resultvignette = $vignette->fetchAll();

           /* var_dump($resultvignette);die('test');*/



            $session=$request->getSession();

            if(!empty($resultvignette)){

                $rs =  $resultvignette[0]['raison_sociale'];
                $rs =  utf8_encode($rs);

                $data_vignette =array(
                'code_firme'=>$resultvignette[0]['code_firme'],
                'raison_sociale'=>$rs,
                );

                
                $session->set('data_vignette', $data_vignette);
                $data_vignette = $session->get('data_vignette');

            }else{
                $data_vignette = 0;
            }
 

            return $this->render('EcommerceBundle:Administration:statistics/telecontact_data.html.twig',array('data_user' => $data_user, 'data_vignette' => $data_vignette ));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function audienceAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $code_firme = $request->get('code_firme');
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');

            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }
            


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $dbname2='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $sql_get_record = $connection->executeQuery("SELECT record_session, page_vue, users, record_date, update_date FROM telecontact_record WHERE id=1");
            $data_get_record = $sql_get_record->fetchAll();
            $resultPageViews= $this->requetPageVue('pageviews');





            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname2",$username,$password)));
            $dirigeant = $connection->executeQuery("SELECT `code_firme` ,`rs_comp` FROM `firmes` WHERE `code_firme` LIKE 'MA".$code_firme."' ");
            $resultdirigeant = $dirigeant->fetchAll();

            $rs = utf8_encode($resultdirigeant[0]["rs_comp"]);
            $raison_sociale=array('rs' => $rs);
            $session->set('raison_sociale', $raison_sociale);
            /*$session_rs= $session->get('raison_sociale');
            $raison_social_globale = $session_rs['rs'];
            var_dump($session_rs);die('ee');*/





            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            /*---Sessions-------------------------------------------------------*/
            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));
            $query->setMetrics(array('ga:sessions'));
            $query->setDimensions(array('ga:date'));

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $end_date = $query['end-date'];
            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));
            $end_date = date('Y-m-d', strtotime( $end_date . " -1 days"));
            $result = $response->getRows();
            $totalForAllResults = $response->getTotalsForAllResults();
            $c_result=count($result);
            $totalForAllResults= $totalForAllResults["ga:sessions"];


            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($result);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));/*d F y*/
                 array_push($dates, $date);
            }

            
            /*var_dump($result);
            die('ahora');*/


            /*---Device-------------------------------------------------------*/


            $query_device = new Query($profileId);
            $query_device->setStartDate( new \DateTime($start_period));
            $query_device->setEndDate( new \DateTime($end_period));
            $query_device->setMetrics(array('ga:sessions'));
            $query_device->setDimensions(array('ga:deviceCategory'));
            $query_device->setStartIndex(1);
            $query_device->setMaxResults (36);

            $service_device = new Service($client);
            $response_device = $service_device->query($query_device);

            $query_device = $response_device->getQuery();
            $result_device = $response_device->getRows();
            $total = $result_device[0][1] + $result_device[1][1] + $result_device[2][1];


            $pourcentage_desktop = ($result_device[0][1]/$total) * 100;
            $pourcentage_mobile = ($result_device[1][1]/$total) * 100;
            $pourcentage_tablet = ($result_device[2][1]/$total) * 100;


            /*var_dump($result_device);die();*/


             /*---Users total -------------------------------------------------------*/


            $query_users = new Query($profileId);
            $query_users->setStartDate( new \DateTime($start_period));
            $query_users->setEndDate( new \DateTime($end_period));
            $query_users->setMetrics(array('ga:users'));
            $query_users->setStartIndex(1);

            $service_users = new Service($client);
            $response_users = $service_users->query($query_users);

            $query_users = $response_users->getQuery();
            $result_users = $response_users->getRows();
            $result_users = $result_users[0][0];
            

            /*var_dump($result_users);die('me duele la espalda total');*/

            /*---Users new -------------------------------------------------------*/


            $query_new_users = new Query($profileId);
            $query_new_users->setStartDate( new \DateTime($start_period));
            $query_new_users->setEndDate( new \DateTime($end_period));
            $query_new_users->setMetrics(array('ga:newUsers'));
            $query_new_users->setStartIndex(1);

            $service_new_users = new Service($client);
            $response_new_users = $service_new_users->query($query_new_users);

            $query_new_users = $response_new_users->getQuery();
            $result_new_users = $response_new_users->getRows();
            $new_users = $response_new_users->getTotalsForAllResults();
           
            $old_users = $result_users - $new_users["ga:newUsers"];
            $new_users = $new_users["ga:newUsers"];
            $pourcentage_new = ($new_users/$result_users) * 100;
            $pourcentage_returned = ($old_users/$result_users) * 100;

            

             /*---Pages-------------------------------------------------------*/


            $query_pages = new Query($profileId);
            $query_pages->setStartDate( new \DateTime($start_period));
            $query_pages->setEndDate( new \DateTime($end_period));
            $query_pages->setMetrics(array('ga:pageviews','ga:avgSessionDuration'));
            $query_pages->setStartIndex(1);

            $service_pages = new Service($client);
            $response_pages = $service_pages->query($query_pages);

            $query_pages = $response_pages->getQuery();
            $total_pages_duration = $response_pages->getTotalsForAllResults();

            $total_pages = $total_pages_duration['ga:pageviews'];
            $total_duration = $total_pages_duration['ga:avgSessionDuration'];
            $seconds = round($total_duration);
            $avg_session_duration = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);



            /* dates */

            $end_date2 = $query['end-date'];
            $start_date2 = $query['start-date'];      
            $begin_period = $this->dateToFrench($start_date2 , 'l d F Y');
            $end_period = $this->dateToFrench($end_date2 , 'l d F Y');


            $response = new JsonResponse();
            return $response->setData(array('data_get_record' => $data_get_record,'resultPageViews'=>$resultPageViews,'sessions' => $result, 'start_date' => $start_date, 'end_date' => $end_date, 'result_device' => $result_device,'pourcentage_desktop'=>$pourcentage_desktop,'pourcentage_mobile'=>$pourcentage_mobile,'pourcentage_tablet'=>$pourcentage_tablet,'totalForAllResults' => $totalForAllResults, 'result_users' => $result_users,'old_users'=>$old_users, 'new_users'=>$new_users, 'pourcentage_new' => $pourcentage_new, 'pourcentage_returned' => $pourcentage_returned, 'total_pages' => $total_pages, 'avg_session_duration' => $avg_session_duration, 'dates' => $dates, 'begin_period' => $begin_period, 'end_period' => $end_period, 'rs' => $rs));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function affichageAction($code_firme, Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /* firme info */


            $session=$request->getSession();
            $data_vignette = $session->get('data_vignette');
            

            /* end firme info */


            return $this->render('EcommerceBundle:Administration:statistics/affichage2.html.twig',array('data_vignette' => $data_vignette));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function affichage2Action($code_firme, Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /* firme info */


            $session=$request->getSession();
            $data_vignette = $session->get('data_vignette');
            

            /* end firme info */


            return $this->render('EcommerceBundle:Administration:statistics/affichage.html.twig',array('data_vignette' => $data_vignette));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function clickVisiteChartAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');



            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            /*var_dump($start_period);
            var_dump($end_period);
            var_dump($code_firme);*/

            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==apparition_rs"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $click_visite_chart = $response->getRows();

            $end_date = $query['end-date'];
            $start_date = $query['start-date'];      
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($click_visite_chart);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }


            $response = new JsonResponse();
            return $response->setData(array('click_visite_chart' => $click_visite_chart, 'dates' => $dates));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }



     public function clickVisiteAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if(!empty($sp)){
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents','ga:uniqueEvents','ga:avgEventValue'));
            $query->setDimensions(array('ga:eventCategory'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==apparition_rs,ga:eventCategory==fiche_apparition"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();

            $click_visite = $response->getRows(); 




            if (!empty($click_visite)) {
                $apparition_rs = $click_visite[0];
                $fiche_apparition = $click_visite[1];
            }else{
                $apparition_rs = 0;
                $fiche_apparition = 0;

            }

             /*var_dump($apparition_rs);die('ff');*/

            /*var_dump($apparition_rs);die();*/

            $end_date = $query['end-date'];
            $start_date = $query['start-date'];      
            $begin_period = $this->dateToFrench($start_date , 'l d F Y');
            $end_period = $this->dateToFrench($end_date , 'l d F Y');

            



            $response = new JsonResponse();
            return $response->setData(array('apparition_rs' => $apparition_rs,'fiche_apparition' => $fiche_apparition, 'begin_period' => $begin_period, 'end_period' => $end_period));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function topKeywordSearchAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');

            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));
            $query->setMetrics(array('ga:searchResultViews'));
            $query->setDimensions(array('ga:searchKeyword'));
            $query->setSorts(array('ga:searchResultViews'));
            $query->setFilters(array('ga:searchStartPage=@'.$code_firme));
            $query->setStartIndex(1);
            $query->setMaxResults(5);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $top_keyword_search = $response->getRows();


            $response = new JsonResponse();
            return $response->setData(array('top_keyword_search' => $top_keyword_search));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     function convert_accents($string){

        return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y'), $string);
    }

    function convert_liens($str,$ville){

    $lien='/liens/';
    $str=str_replace("   "," ",$str);
    $str=str_replace("  "," ",$str);  
    $str=str_replace(" ","-",$str);
    $str=str_replace(":","-",$str);    
    $str=str_replace(")","",$str);
    $str=str_replace("(","",$str);
    $str=str_replace("(","",$str);
    $str=str_replace(".","",$str);  
    $str=str_replace("'","-",$str);
    $str=str_replace(",","",$str);
    $str=str_replace("’","-",$str);
    $str=str_replace("´","-",$str);
        if($ville=='DAR BOUAZZA'){
            $ville='dar-bouazza';
        }
        $str=$lien.$str.'-/'.$ville;
        return $str ;
    } 



     public function rubriqueAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');

            $total = 0;
            $ville_name ='';

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='46.182.7.30';
            $dbname='erpprod';
            $username='edicom';
            $password='dnSQ8Tg5HRYNwpli';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

            $hostname2='localhost';
            $dbname2='BD_EDICOM';
            $username2='pyxicom';
            $password2='Yz9nVEXjZ2hqptZT';
            $connection2 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname2",$username2,$password2)));

            $rubriques = $connection->fetchAll("SELECT r.`code_rubrique`, r.`rubrique`, v.`ville` FROM `u_yf_ssingleorders` s
                                                INNER join `u_yf_ssingleorders_inventory` i on i.`id`=s.`ssingleordersid`
                                                INNER join `vtiger_account` acc on acc.`accountid`=s.`firme`
                                                INNER join `u_yf_rubriques` r on r.`rubriquesid` = i.`ref`
                                                INNER join `u_yf_villes` v on v.`villesid` = i.`value28`
                                                where acc.`code_firme` = '".$code_firme."'
                                                and s.edition = (select MAX(s.edition) from u_yf_ssingleorders s
                                                                 INNER join `vtiger_account` acc on acc.`accountid`=s.`firme`
                                                                 where s.support='Telecontact' and s.type_ordre='Internet' AND acc.`code_firme` = '".$code_firme."'
                                                               )
                                                group by r.`code_rubrique`
                                              ");


            if(empty($rubriques)){
                $rubriques= $connection2->fetchAll("SELECT r.`Code_Rubrique` as code_rubrique, r.`Lib_Rubrique` as rubrique, v.`ville` 
                                                    FROM `rubriques` r
                                                    INNER JOIN `lien_rubrique_internet` l on l.`code_rubrique`=r.`Code_Rubrique`
                                                    INNER JOIN `firmes` f on f.`code_firme` = l.`code_firme`
                                                    INNER JOIN `villes` v on v.`code` = f.code_ville
                                                    WHERE f.`code_firme` = CONCAT('MA','".$code_firme."') and l.`editable`='+' 
                                                   ");

                
            }


            $list_rubrique= array();
            for($i=0; $i<count($rubriques); $i++){



                $rub = str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y'), utf8_encode($rubriques[$i]["rubrique"]));

                

                $ville = $rubriques[$i]['ville'];
                $lien='/liens/';

                $str=str_replace("   "," ",$rub);
                $str=str_replace(": ","-",$str);
                $str=str_replace("  "," ",$str);  
                $str=str_replace(" ","-",$str);
                $str=str_replace(":","-",$str);
                $str=str_replace(";","",$str);    
                $str=str_replace(")","",$str);
                $str=str_replace("(","",$str);
                $str=str_replace("(","",$str);
                $str=str_replace(".","",$str);  
                $str=str_replace("'","-",$str);
                $str=str_replace(", ","",$str);
                $str=str_replace(",","",$str);
                $str=str_replace("’","-",$str);
                $rubrique_v=str_replace("´","-",$str);
                    if($ville=='DAR BOUAZZA'){
                        $ville='dar-bouazza';
                    }
                $rubrique=$lien.$rubrique_v.'/'.$ville;


                $ville = utf8_encode($ville);
                $ville_name = "Nombre de parution de la rubrique à $ville";
               

                $sp = $request->get('start_period');
                $selectedDaterange = $session->get('selectedDaterange');
                if (!empty($sp)) {
                    $selectedDaterange = array($start_period,$end_period);
                    $session->set('selectedDaterange', $selectedDaterange);
                    $selectedDaterange = $session->get('selectedDaterange');
                    $start_period = $selectedDaterange[0];
                    $end_period = $selectedDaterange[1];
                    $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                    $session->set('selectedrange', $selecterange);
                }elseif($selectedDaterange != ''){
                    $start_period = $selectedDaterange[0];
                    $end_period = $selectedDaterange[1];
                    $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                    $session->set('selectedrange', $selecterange);
                }else{
                    $start_period = '-1months';
                    $end_period = '-1days';
                    $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                    $session->set('selectedrange', $selecterange);
                }

                
                $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
                $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
                $httpAdapter = new CurlHttpAdapter();

                $client = new Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();

                $profileId = 'ga:21206490';


                
                $query = new Query($profileId);
                $query->setStartDate( new \DateTime($start_period));
                $query->setEndDate( new \DateTime($end_period));
                $query->setMetrics(array('ga:pageviews'));
                $query->setDimensions(array('ga:pagePath'));
                $query->setFilters(array('ga:pagePath=@'.$rubrique));
               /* $query->setSorts(array('ga:searchResultViews'));
                $query->setFilters(array('ga:searchStartPage=@'.$code_firme));
                $query->setStartIndex(1);
                $query->setMaxResults(10);*/



                $service = new Service($client);
                $response = $service->query($query);

                $query = $response->getQuery();

                

                $rubrique_ville = $response->getRows();

                $rubrique_ville= $response->getTotalsForAllResults();



                
                                
                $array1 = array(utf8_encode($rubriques[$i]["rubrique"]));
                $array2 = array($rubrique_ville["ga:pageviews"]);
                $result = array_merge($array1, $array2);


                

                
                $list_rubrique = array_merge($list_rubrique, $result);
                if(empty($list_rubrique)){
                    $list_rubrique = 0;
                }


            }

            $response = new JsonResponse();
            return $response->setData(array('list_rubrique' => $list_rubrique, 'ville_name' => $ville_name));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function rubriqueMarocAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            $total =0;


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='46.182.7.30';
            $dbname='erpprod';
            $username='edicom';
            $password='dnSQ8Tg5HRYNwpli';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

            $hostname2='localhost';
            $dbname2='BD_EDICOM';
            $username2='pyxicom';
            $password2='Yz9nVEXjZ2hqptZT';
            $connection2 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname2",$username2,$password2)));

            $rubriques = $connection->fetchAll("SELECT r.`code_rubrique`, r.`rubrique` FROM `u_yf_ssingleorders` s
                                                INNER join `u_yf_ssingleorders_inventory` i on i.`id`=s.`ssingleordersid`
                                                INNER join `vtiger_account` acc on acc.`accountid`=s.`firme`
                                                INNER join `u_yf_rubriques` r on r.`rubriquesid` = i.`ref`
                                                where acc.`code_firme` = '".$code_firme."' and s.edition = (select MAX(s.edition) from u_yf_ssingleorders s
                                                                 INNER join `vtiger_account` acc on acc.`accountid`=s.`firme`
                                                                 where s.support='Telecontact' and s.type_ordre='Internet' AND acc.`code_firme` = '".$code_firme."')
                                                group by r.`code_rubrique`");

            if(empty($rubriques)){
                $rubriques= $connection2->fetchAll("SELECT r.`Code_Rubrique` as code_rubrique, r.`Lib_Rubrique` as rubrique
                                                    FROM `rubriques` r
                                                    INNER JOIN `lien_rubrique_internet` l on l.`code_rubrique`=r.`Code_Rubrique`
                                                    INNER JOIN `firmes` f on f.`code_firme` = l.`code_firme`
                                                    WHERE f.`code_firme` = CONCAT('MA', '".$code_firme."') and l.`editable`='+' 
                                                   ");
            }



            /*$rubriques = "marketing-direct";
            $ville = "casablanca";*/

        $count_rub = count($rubriques);
        $list_rubrique_maroc= array();

        for($i=0; $i<count($rubriques); $i++){



                $rub = str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y'), utf8_encode($rubriques[$i]["rubrique"]));

                

               
            $lien='/liens/';
            $str=str_replace("   "," ",$rub);
            $str=str_replace(": ","-",$str);
            $str=str_replace("  "," ",$str);  
            $str=str_replace(" ","-",$str);
            $str=str_replace(":","-",$str);
            $str=str_replace(";","",$str);    
            $str=str_replace(")","",$str);
            $str=str_replace("(","",$str);
            $str=str_replace("(","",$str);
            $str=str_replace(".","",$str);  
            $str=str_replace("'","-",$str);
            $str=str_replace(",","",$str);
            $str=str_replace("’","-",$str);
            $rubrique_m=str_replace("´","-",$str);
            $rubrique=$lien.$rubrique_m.'/';


            

            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));
            $query->setMetrics(array('ga:pageviews'));
            $query->setDimensions(array('ga:pagePath'));
            $query->setFilters(array('ga:pagePath=@'.$rubrique));
           /* $query->setSorts(array('ga:searchResultViews'));
            $query->setFilters(array('ga:searchStartPage=@'.$code_firme));
            $query->setStartIndex(1);
            $query->setMaxResults(5);*/

            


            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();

                

            $rubrique_maroc = $response->getRows();

            $rubrique_maroc= $response->getTotalsForAllResults();
                
            $array1 = array(utf8_encode($rubriques[$i]["rubrique"]));
            $array2 = array($rubrique_maroc["ga:pageviews"]);
            $result = array_merge($array1, $array2);
                

                
            $list_rubrique_maroc = array_merge($list_rubrique_maroc, $result);
            if(empty($list_rubrique_maroc)){
                    $list_rubrique_maroc = 0;
            }

        }

             


            $response = new JsonResponse();
            return $response->setData(array('list_rubrique_maroc' => $list_rubrique_maroc));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function clickAction($code_firme, Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /* firme info */

            /*$cf = array('code_firme' => $code_firme); 

            $session=$request->getSession();
            $data_user = $session->get('utilisateur');
            $data_user[0] = array_merge($data_user[0], $cf);*/
            
            $session=$request->getSession();
            $data_vignette = $session->get('data_vignette');

            /* end firme info */


            return $this->render('EcommerceBundle:Administration:statistics/click2.html.twig', array('data_vignette' => $data_vignette/*, 'data_user' => $data_user*/));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function click2Action($code_firme, Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /* firme info */

            /*$cf = array('code_firme' => $code_firme); 

            $session=$request->getSession();
            $data_user = $session->get('utilisateur');
            $data_user[0] = array_merge($data_user[0], $cf);*/
            
            $session=$request->getSession();
            $data_vignette = $session->get('data_vignette');

            /* end firme info */


            return $this->render('EcommerceBundle:Administration:statistics/click.html.twig', array('data_vignette' => $data_vignette/*, 'data_user' => $data_user*/));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }




     public function clickTableAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents','ga:uniqueEvents','ga:avgEventValue'));
            $query->setDimensions(array('ga:eventCategory'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==Raison_sociale_click,ga:eventCategory==Logo_click,ga:eventCategory==Site_Web,ga:eventCategory==PVI_Click,ga:eventCategory==Afficher_Le_Numero,ga:eventCategory==Afficher_ICE_click,ga:eventCategory==Afficher_RC_click,ga:eventCategory==itineraire,ga:eventCategory==map_click,ga:eventCategory==Page_Annonceur_Rubriques,ga:eventCategory==Page_Annonceur_mots-cles"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $click_table = $response->getRows();

            $total_clicks = '';
            for($i=0; $i<=count($click_table)-1; $i++){
                $total_clicks = $total_clicks+$click_table[$i][1];
            }

        
            
            $end_date = $query['end-date'];
            $start_date = $query['start-date'];      
            $begin_period = $this->dateToFrench($start_date , 'l d F Y');
            $end_period = $this->dateToFrench($end_date , 'l d F Y');

           

            $response = new JsonResponse();
            return $response->setData(array('click_table' => $click_table, 'begin_period' => $begin_period, 'end_period' => $end_period, 'total_clicks' => $total_clicks));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function clickDefaultChartAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }


            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==Raison_sociale_click,ga:eventCategory==Logo_click,ga:eventCategory==PVI_Click,ga:eventCategory==Site_Web,ga:eventCategory==map_click,ga:eventCategory==itineraire,ga:eventCategory==Afficher_RC_click,ga:eventCategory==Afficher_Le_Numero,ga:eventCategory==Afficher_ICE_click,ga:eventCategory==Page_Annonceur_Rubriques,ga:eventCategory==Page_Annonceur_mots-cles"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $click_def_chart = $response->getRows();


            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($click_def_chart);$i++){
                 $date = date('d-F-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }

            /*var_dump($click_def_chart);die('ahora');*/


            $response = new JsonResponse();
            return $response->setData(array('click_def_chart' => $click_def_chart, 'dates' => $dates));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function clickFicheChartAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==Raison_sociale_click,ga:eventCategory==Logo_click"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $click_fiche_chart = $response->getRows();


            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($click_fiche_chart);$i++){
                 $date = date('d-F-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }


            $response = new JsonResponse();
            return $response->setData(array('click_fiche_chart' => $click_fiche_chart, 'dates' => $dates));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function clickPviChartAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==PVI_Click"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $click_table_chart = $response->getRows();

            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($click_table_chart);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }



            $response = new JsonResponse();
            return $response->setData(array('click_table_chart' => $click_table_chart, 'dates' => $dates ));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function clickSiteChartAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }

            
            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';


            
            $query = new Query($profileId);
            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));

            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));

            $query->setFilters(array("ga:eventLabel=@".$code_firme.";ga:eventCategory==Site_Web"));  
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);

            $query = $response->getQuery();
            $click_table_chart = $response->getRows();

            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($click_table_chart);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }

            /*var_dump($click_table_chart);die();*/

            $response = new JsonResponse();
            return $response->setData(array('click_table_chart' => $click_table_chart, 'dates' => $dates ));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function vignetteAction($code_firme, Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /* firme info */
            
            $session=$request->getSession();
            $data_vignette = $session->get('data_vignette');

            /* end firme info */

            return $this->render('EcommerceBundle:Administration:statistics/vignette.html.twig', array('data_vignette' => $data_vignette));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function vignetteRubriqueAction(Request $request){


        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }


            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';
            
            $query = new Query($profileId);

            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));
           
            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));
            $query->setFilters(array('ga:eventLabel=@'.$code_firme.';ga:eventCategory==vignettes;ga:eventAction==rubrique'));
             
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);


            $query = $response->getQuery();
            $vignette_rubrique = $response->getRows();

            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($vignette_rubrique);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }

            

            $response = new JsonResponse();
            return $response->setData(array('vignette_rubrique' => $vignette_rubrique, 'dates' => $dates));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
     }


     public function vignetteRegionAction(Request $request){


        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }
        

            $clientId = '83342803379-2unaellkrn158ob6cr4gkslgbke1qqtv@developer.gserviceaccount.com';
            $privateKeyFile =__DIR__.'/../../../../app/Resources/client_secrets.p12';
            $httpAdapter = new CurlHttpAdapter();

            $client = new Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();

            $profileId = 'ga:21206490';
            
            $query = new Query($profileId);

            $query->setStartDate( new \DateTime($start_period));
            $query->setEndDate( new \DateTime($end_period));
           
            $query->setMetrics(array('ga:totalEvents'));
            $query->setDimensions(array('ga:date'));
            $query->setFilters(array('ga:eventLabel=@'.$code_firme.';ga:eventCategory==vignettes;ga:eventAction==region'));
             
            $query->setStartIndex(1);

            $service = new Service($client);
            $response = $service->query($query);


            $query = $response->getQuery();
            $vignette_region = $response->getRows();

            $start_date = $query['start-date'];            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($vignette_region);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }

            

            $response = new JsonResponse();
            return $response->setData(array('vignette_region' => $vignette_region, 'dates' => $dates));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
     }


     public function devisAction($code_firme, Request $request){/*, PaginatorInterface $paginator*/

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $devis = $connection->executeQuery("SELECT * FROM Demande_devis_Details where  Cfirme =".$code_firme." ORDER BY Date_Envoi DESC");

            $resultdevis = $devis->fetchAll();

            $resultdevis = array_map(function($c){
                $c['Nom_Expediteur'] = utf8_encode($c['Nom_Expediteur']);
                $c['Objet'] = utf8_encode($c['Objet']);
                $c['Message'] = utf8_encode($c['Message']);
                return $c;
            }, $resultdevis);

            return $this->render('EcommerceBundle:Administration:statistics/devis.html.twig', array(/*'data_vignette' => $data_vignette,*/'resultdevis' => $resultdevis));


        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function devisTelecontactAction($code_firme, Request $request){/*, PaginatorInterface $paginator*/

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $devis = $connection->executeQuery("SELECT * FROM demande_devis where  code_firme =".$code_firme." ORDER BY date_creation DESC");

            $resultdevis = $devis->fetchAll();

            $resultdevis = array_map(function($c){
                $c['nom_complet'] = utf8_encode($c['nom_complet']);
                $c['societe'] = utf8_encode($c['societe']);
                $c['bession'] = utf8_encode($c['bession']);
                return $c;
            }, $resultdevis);

            return $this->render('EcommerceBundle:Administration:statistics/devisTelecontact.html.twig', array(/*'data_vignette' => $data_vignette,*/'resultdevis' => $resultdevis));


        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function consulterDevisAction($code_firme, Request $request){/*, PaginatorInterface $paginator*/

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $devis = $connection->executeQuery("SELECT * FROM demande_devis where code_firme ='".$code_firme."' ORDER BY date_creation DESC");

            $resultdevis = $devis->fetchAll();

            $resultdevis = array_map(function($c){
                $c['societe'] = utf8_encode($c['societe']);
                $c['bession'] = utf8_encode($c['bession']);
                return $c;
            }, $resultdevis);

            return $this->render('EcommerceBundle:Administration:statistics/consulterDevis.html.twig', array(/*'data_vignette' => $data_vignette,*/'resultdevis' => $resultdevis));


        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     


     public function LoadStatistiquePdfAction(){
        
        
        $message = "working!!!!!!";

        return $this->render('EcommerceBundle:Administration:statistics/loadStatistiquePdf.html.twig', array(
                'message' => $message,
            ));
     }



     public function uploadPdfAudienceAction(Request $request){

        $image = $request->get('base64data');

        
        $page = $request->get('page');

        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = '';
        /*file_put_contents('/var/www/prod/telecontactV2/telecontact/trouver/espace_stats/pdf_images/audience_TLC'.$page.'.png', $image_base64);*/
        file_put_contents('/var/www/prod/espace-client/web/public_statistics/espace_stats/pdf_images/audience_TLC'.$page.'.png', $image_base64);

        die();

     }

     public function uploadPdfImagesAction(Request $request){

        $image = $request->get('base64data');

        $code_firme = $request->get('code_firme');
        $id_user = $request->get('id_user');
        $page = $request->get('page');

        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = '';

        if($page == 1){
            /*file_put_contents('/var/www/prod/telecontactV2/telecontact/trouver/espace_stats/pdf_images/audiance_globale_'.$page.'.png', $image_base64);*/
            file_put_contents('/var/www/prod/espace-client/web/public_statistics/espace_stats/pdf_images/audiance_globale_'.$page.'.png', $image_base64);
        }else{
            /*file_put_contents('/var/www/prod/telecontactV2/telecontact/trouver/espace_stats/pdf_images/'.$id_user.'__'.$code_firme.'__'.date('Y-m-d').'__'.$page.'.png', $image_base64);*/
            file_put_contents('/var/www/prod/espace-client/web/public_statistics/espace_stats/pdf_images/'.$id_user.'__'.$code_firme.'__'.date('Y-m-d').'__'.$page.'.png', $image_base64);
        }
        

        die();
     }

     public function pdfAction(Request $request, $code_firme, $id_user, $start, $end){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){



            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname1='localhost';
                $dbname1='espace_clients';
                $username1='pyxicom';
                $password1='Yz9nVEXjZ2hqptZT';
                $connection1 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname1;dbname=$dbname1",$username1,$password1)));

                $MAcode_firme = 'MA'.$code_firme;
                $raison_sociale = $connection1->fetchAll("
                    SELECT f.`rs_comp` as raison_sociale
                    FROM  BD_EDICOM.`firmes` f
                    WHERE f.`code_firme` = '$MAcode_firme'
                    ");
                $raison_sociale = utf8_encode($raison_sociale[0]['raison_sociale']);

                $start = date("d-m-Y", strtotime($start));
                $end   = date("d-m-Y", strtotime($end));


                $check_query = $connection1->fetchAll("SELECT * FROM `historique_pdf` where `nom_fichier` = '".$id_user."__".$code_firme."__".date('Y-m-d').".pdf' AND `start_date` = '".$start."' AND `end_date` = '".$end."' ");


                if(empty($check_query[0])){
                    $select_query = $connection1->executeQuery("INSERT INTO historique_pdf (id_user, code_firme, nom_fichier, date_systeme, start_date, end_date) values ('".$id_user."', '".$code_firme."', '".$id_user."__".$code_firme."__".date('Y-m-d').".pdf', '".date('Y-m-d H:i:s')."', '".$start."', '".$end."')");
                }else{
                    $update_query = "UPDATE historique_pdf SET id_user = '".$id_user."', code_firme= '".$code_firme."', nom_fichier = '".$id_user."__".$code_firme."__".date('Y-m-d').".pdf', date_systeme = '".date('Y-m-d H:i:s')."', start_date = '".$start."', end_date = '".$end."' ";
                }




            $links = array();
            /*array_push($links, "https://www.telecontact.ma/trouver/espace_stats/pdf_images/audiance_globale_1.png");*/
            array_push($links, "http://www.espace-client.telecontact.ma/web/public_statistics/espace_stats/pdf_images/audiance_globale_1.png");
            for($i=2; $i<=5; $i++){
                /*$fileToGet = "/var/www/prod/telecontactV2/telecontact/trouver/espace_stats/pdf_images/".$id_user."__".$code_firme."__".date('Y-m-d')."__".$i.".png";*/
                $fileToGet = "/var/www/prod/espace-client/web/public_statistics/espace_stats/pdf_images/".$id_user."__".$code_firme."__".date('Y-m-d')."__".$i.".png";

                if (file_exists($fileToGet)) {
                    /*array_push($links, "https://www.telecontact.ma/trouver/espace_stats/pdf_images/".$id_user."__".$code_firme."__".date('Y-m-d')."__".$i.".png");*/
                    array_push($links, "http://www.espace-client.telecontact.ma/web/public_statistics/espace_stats/pdf_images/".$id_user."__".$code_firme."__".date('Y-m-d')."__".$i.".png");
                }
            }




            $file = "/var/www/prod/telecontactV2/telecontact/pubs/logos/L".$code_firme.".png";
            /*$file_headers = @get_headers($file);
            if(!$file_headers && $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $url = $file;
                $url = str_replace("'", "", $url);   
            }
            else {
                $url = "https://www.telecontact.ma/pubs/logos/L2107435.png";
            }*/

            $pdf_path = "/var/www/prod/telecontactV2/telecontact/pubs/logos/L".$code_firme.".png";

            if(file_exists($pdf_path)){
                $url = $file;
                $url = str_replace("'", "", $url);   
            }else{
                $url = "/var/www/prod/telecontactV2/telecontact/pubs/logos/L2107435.png";
            }

            

            
            

            $html = $this->container->get('templating')->render('EcommerceBundle:Administration:statistics/pdf.html.twig', array('links' => $links, 'url' => $url, 'code_firme'=> $code_firme, 'raison_sociale' => $raison_sociale));
            $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
            $html2pdf->pdf->SetAuthor('Audiance_Télécontact');
            $html2pdf->pdf->SetTitle('Audiance_Télécontact');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->writeHTML($html);

            $html2pdf->Output('/var/www/prod/espace-client/web/public_statistics/espace_stats/pdf_uploads/'.$id_user.'__'.$code_firme.'__'.date('Y-m-d').'.pdf', 'F');

            $html = $this->container->get('templating')->render('EcommerceBundle:Administration:statistics/pdf.html.twig', array('links' => $links, 'url' => $url, 'code_firme'=> $code_firme, 'raison_sociale' => $raison_sociale));
            $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
            $html2pdf->pdf->SetAuthor('Audiance_Télécontact');
            $html2pdf->pdf->SetTitle('Audiance_Télécontact');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->writeHTML($html);

            $html2pdf->Output('/var/www/prod/espace-client/web/public_statistics/espace_stats/pdf_uploads/'.$id_user.'__'.$code_firme.'__'.date('Y-m-d').'.pdf', 'I');
            
            die();
        return $this->render('EcommerceBundle:Administration:statistics/return.html.twig');
            

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function listpdfAction(Request $request, $code_firme){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


            $id_user = $ses[0]['id_user'];
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname1='localhost';
                $dbname1='espace_clients';
                $username1='pyxicom';
                $password1='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname1;dbname=$dbname1",$username1,$password1)));

                $list = $connection->fetchAll("
                    SELECT h.`id_user`, h.`code_firme`, h.`nom_fichier`, h.`date_systeme`, h.`start_date`, h.`end_date`, f.`rs_comp`, c.`nom`, c.`prenom`
                    FROM  espace_clients.`historique_pdf` h
                    INNER JOIN CRM_EDICOM.`tts_firmes` f on f.`code_firme` = CONCAT('MA', h.`code_firme`)
                    INNER JOIN CRM_EDICOM.`tts_utilisateur` c on c.`id` = h.`id_user`
                    WHERE h.`id_user` = '$id_user' AND h.`code_firme` = '$code_firme'
                    ");

                $list = array_map(function($lc){
                    $lc['rs_comp'] = utf8_encode($lc['rs_comp']);
                    return $lc;
                }, $list);
                

        return $this->render('EcommerceBundle:Administration:statistics/listpdf.html.twig', array('list' => $list));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function AllpdfAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname1='localhost';
                $dbname1='espace_clients';
                $username1='pyxicom';
                $password1='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname1;dbname=$dbname1",$username1,$password1)));


                $id_user = $ses[0]['id_user'];
                /*var_dump($ses[0]);die('ee');*/

                $list = $connection->fetchAll("
                    SELECT h.`id_user`, h.`code_firme`, h.`nom_fichier`, h.`date_systeme`, h.`start_date`, h.`end_date`, f.`rs_comp`, c.`nom`, c.`prenom`
                    FROM  espace_clients.`historique_pdf` h
                    INNER JOIN CRM_EDICOM.`tts_firmes` f on f.`code_firme` = CONCAT('MA', h.`code_firme`)
                    INNER JOIN CRM_EDICOM.`tts_utilisateur` c on c.`id` = h.`id_user`
                    WHERE h.`id_user` = '$id_user' ");

                $list = array_map(function($lc){
                    $lc['rs_comp'] = utf8_encode($lc['rs_comp']);
                    return $lc;
                }, $list);



                $list_all = $connection->fetchAll("
                    SELECT h.`id_user`, h.`code_firme`, h.`nom_fichier`, h.`date_systeme`, h.`start_date`, h.`end_date`, f.`rs_comp`, c.`nom`, c.`prenom`
                    FROM  espace_clients.`historique_pdf` h
                    INNER JOIN CRM_EDICOM.`tts_firmes` f on f.`code_firme` = CONCAT('MA', h.`code_firme`)
                    INNER JOIN CRM_EDICOM.`tts_utilisateur` c on c.`id` = h.`id_user`");
                $list_all = array_map(function($lc){
                    $lc['rs_comp'] = utf8_encode($lc['rs_comp']);
                    return $lc;
                }, $list_all);
                

        return $this->render('EcommerceBundle:Administration:statistics/Allpdf.html.twig', array('list' => $list, 'list_all' => $list_all));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function actualitesAction($code_firme,  Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


                $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname1='localhost';
                $dbname1='telecontact_BackOffice_Site';
                $username1='pyxicom';
                $password1='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname1;dbname=$dbname1;charset=utf8",$username1,$password1)));
                //$connection->mysql_set_charset("utf8");


                $actualite = $connection->fetchAll("SELECT * FROM `actualite` WHERE `code_firme` = '$code_firme'");
                
                

                $actualite = array_map(function($c){

                   
                    $c['conditions'] = utf8_encode($c['conditions']);
                    $c['livraison'] = utf8_encode($c['livraison']);
                    $c['savoir'] = utf8_encode($c['savoir']);

                    return $c;
                }, $actualite);


                if(!empty($actualite[0]['prestations'])){
                    $prestation = explode(',', $actualite[0]['prestations']);
                    $prestation = str_replace('"','', $prestation);
                    $prestation = str_replace('[','', $prestation);
                    $prestation = str_replace(']','', $prestation);
                }else{
                    $prestation = '';
                }
                /*var_dump($prestation);die('aa');*/

               
                

                if($request->isMethod('post')){

                    if(!empty($_POST['conditions'])){
                        $conditions = $_POST['conditions'];
                    }else{
                        $conditions = '';
                    }
                    if(!empty($_POST['livraison'])){
                    $livraison = $_POST['livraison'];
                    }else{
                        $livraison = '';
                    }
                    if(!empty($_POST['savoir'])){
                    $savoir = $_POST['savoir'];
                    }else{
                        $savoir = '';
                    }
                    if(!empty($_POST['valider'])){
                    $valider = $_POST['valider'];
                    }else{
                        $valider = '';
                    }
                    if(!empty($_POST['heure'])){
                    $heure = $_POST['heure'];
                    }else{
                        $heure = '';
                    }
                    if(!empty($_POST['paiement'])){
                       $_paiment= json_encode($_POST['paiement']); 
                    }else{
                        $_paiment = '';
                    }
                    if(!empty($_POST['id_user'])){
                    $user= $_POST['id_user'];
                    /*var_dump($user);die('user found');*/
                    }else{
                        $user = '';
                        /*die('no_user');*/
                    }
                    if(!empty($_POST['field_name'])){
                        $prestations=$_POST['field_name'];
                        /*$prestations = array_map(function($c){
                            $c['prestations']= utf8_encode($c['prestations']);
                            return $c;
                        }, $prestations);*/

                        $prestations= preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $prestations ) );
                        
                        $prestations=addslashes($prestations);
                       /* var_dump($prestations);*/
                    }else{
                        $prestations = '';
                    }

                    /*var_dump($prestations);die('alli');*/
                    

                    /*var_dump($conditions);var_dump($livraison);var_dump($savoir);var_dump($valider);var_dump($heure);var_dump($_paiment);var_dump($user);die();*/
                   
                    

                    if(!empty($actualite)){

                       
                        $update = $connection->executeQuery("UPDATE `actualite` SET `valider`='".$valider."',`conditions`='".$conditions."',`livraison`='".$livraison."',`savoir`='".$savoir."',`paiement`='".$_paiment."',`heure`='".$heure."' , `user` = '".$user."', `prestations` = '". $prestations ."' where `code_firme` = '". $code_firme ."'
                            ");



                    }else{

                        $insert = $connection->executeQuery("INSERT INTO `actualite` (`conditions`, `livraison`, `paiement`, `heure`,`code_firme`,`savoir`,`valider`,`user`, `prestations`) VALUES('".$conditions."','".$livraison."','".$_paiment."','".$heure."','".$code_firme."','".$savoir."','".$valider."','".$user."', '". $prestations ."')");

                    }


                        $actualite = $connection->fetchAll("SELECT * FROM `actualite` WHERE `code_firme` = '$code_firme'");
                        
                        $actualite = array_map(function($c){
                            $c['conditions'] = utf8_encode($c['conditions']);
                            $c['livraison'] = utf8_encode($c['livraison']);
                            $c['savoir'] = utf8_encode($c['savoir']);
                            return $c;
                        }, $actualite);

                        if(!empty($actualite[0]['prestations'])){
                            $prestation = explode(',', $actualite[0]['prestations']);
                            $prestation = str_replace('"','', $prestation);
                            $prestation = str_replace('[','', $prestation);
                            $prestation = str_replace(']','', $prestation);
                        }else{
                            $prestation = '';
                        }

                    return $this->render('EcommerceBundle:Administration:statistics/actualite.html.twig', array('actualite' => $actualite, 'code_firme' => $code_firme, 'prestation' => $prestation));
                    
                }

               /* var_dump($actualite);die();*/
                

        return $this->render('EcommerceBundle:Administration:statistics/actualite.html.twig', array('actualite' => $actualite, 'code_firme' => $code_firme, 'prestation' => $prestation));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function adsAction(Request $request, $code_firme){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /*var_dump($code_firme);die('la cabeza');*/


                $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname='localhost';
                $dbname='adadmin2';
                $username='pyxicom';
                $password='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));
                //$connection->mysql_set_charset("utf8");


                $MonthAgo = strtotime("-1 month");
                
                $banners = $connection->fetchAll("SELECT * FROM `7banner` WHERE `de_nome` LIKE '%$code_firme%' ");

                $count_banners = count($banners);

                /*var_dump($count_banners);die('test technique');*/

                /*
                var_dump($banners);die('ee');
                */
                /*
                $banners = array_map(function($c){

                    $c['de_nome'] = str_replace($code_firme, '', $c['de_nome']);

                    return $c;
                }, $banners);
                */
                
                return $this->render('EcommerceBundle:Administration:statistics/ads2.html.twig', array('banners' => $banners, 'count_banners'=> $count_banners));

        }else{
            return $this->redirect($this->generateUrl('connection'));

        }

        
     }


     public function carreacceuilAction(Request $request){


        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


             $connectionFactory = $this->get('doctrine.dbal.connection_factory');
             $hostname='localhost';
             $dbname='adadmin2';
             $username='pyxicom';
             $password='Yz9nVEXjZ2hqptZT';
             $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            $data_user = $session->get('data_user');
            $code_firme = $data_user[0]['code_firme'];
            $start_period = $request->get('start_period');
            $end_period = $request->get('end_period');
            $id = $request->get('id');


            $sp = $request->get('start_period');
            $selectedDaterange = $session->get('selectedDaterange');
            if (!empty($sp)) {
                $selectedDaterange = array($start_period,$end_period);
                $session->set('selectedDaterange', $selectedDaterange);
                $selectedDaterange = $session->get('selectedDaterange');
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }elseif($selectedDaterange != ''){
                $start_period = $selectedDaterange[0];
                $end_period = $selectedDaterange[1];
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }else{
                $start_period = '-1months';
                $end_period = '-1days';
                $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
                $session->set('selectedrange', $selecterange);
            }


            $start_period = date('Y-m-d', strtotime($start_period));
            $end_period = date('Y-m-d', strtotime($end_period));


            $carreacceuil = $connection->fetchAll("SELECT `id_day`,`nu_pageviews`, `nu_click` from `7banner_stats` where cd_banner = $id AND `id_day` between '".$start_period."' and '".$end_period."' ");

            $carreacceuil_click = $connection->fetchAll("SELECT SUM(nu_click) as click, SUM(bs.`nu_pageviews`) as view, b.`de_nome` from `7banner_stats` bs INNER JOIN `7banner` b on b.`id_banner` = bs.`cd_banner` where cd_banner = $id AND `id_day` between '".$start_period."' and '".$end_period."' ");

            /*var_dump($carreacceuil);die('rania');*/

            
            


            $start_date = $start_period;            
            $start_date = date('Y-m-d', strtotime( $start_date . " -1 days"));

            $date = $start_date;
            $dates = array();
            for($i=0; $i<count($carreacceuil);$i++){
                 $date = date('d-m-Y', strtotime( $date . " +1 days"));
                 $date = date('d-m-Y', strtotime( $date));
                 array_push($dates, $date);
            }


            $date_carreacceuil = array();
            foreach ($carreacceuil as $value) {
                array_push($date_carreacceuil, $value['id_day']);
            }

            /*var_dump($dates);die('edfg');*/
            

            $response = new JsonResponse();
            return $response->setData(array('carreacceuil_click' => $carreacceuil_click,'carreacceuil' => $carreacceuil, 'dates' => $dates, 'date_carreacceuil' => $date_carreacceuil, 'code_firme' => $code_firme));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function ticketAction(Request $request, $code_firme){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';

            $hostname2='46.182.7.30';
            $dbname3='espace_clients';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));
            $connection3= $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3;charset=utf8",$username2,$password2)));

            $ticket = $connection3->fetchAll('SELECT * FROM `ticket` where `code_firme` = "'.$code_firme.'" ');

            if($request->isMethod('post')){

                $sujet = $request->get('input_sujet');
                $sujet = addslashes($sujet);
                $description = $request->get('input_desc');
                $description = addslashes($description);
                $id_user = $request->get('id_user');
                $date = date('Y-m-d H:m:s');

                
                $insert = $connection3->executeQuery("INSERT INTO `ticket` (`code_firme`, `sujet`, `description`, `date_creation`, `etat`, `id_user`) values('".$code_firme."','".$sujet."','".$description."', '".$date."', 0, '".$id_user."') ");


                /* mail rania */

                $destinataire = 'n.belrhazi@edicom.ma';
                $expediteur = '"'.$ses[0]['email'].'"';
                $objet = 'Demande espace client'; 
                $headers = 'From: Espace client Telecontact <' . $destinataire . '> ' . "\r\n" 
                .'Reply-To: Espace client Telecontact <' . $destinataire . '> ' . "\r\n"
                .'Content-Type:text/html;charset=utf-8' . "\r\n" 
                .'X-Mailer: PHP/' . phpversion();
                $headers .= "Return-Path: Espace client telecontact <" . $destinataire . '> ' . "\r\n";
                $message = '<html><head><title>Demande espace client telecontact</title></head><body><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FDFDFD"><tr style="background-color:#ffdd00;color:black"><td height="80" align="center" valign="middle" bgcolor="#ffdd00" color="#ffffff"><h1>Demande espace client telecontact</h1></td></tr><tr><td height="60" color="#000000"><p style="padding-left: 35px;padding-right: 20px;padding-top:35px;font-size: 17px;">Bonjour,<br><br><strong>Sujet :</strong>'.$sujet.'</p></td></tr><tr><td style="padding: 35px;font-size: 17px;line-height: 25px;"><strong>Messgae : </strong><label>'.$description. '</span></td></tr><tr><td><p style="padding-left: 6%;padding-bottom: 20px;font-size: 17px;">Cordialement.</p></td></tr><tr height="70" align="center" valign="middle" bgcolor="#ffdd00" color="#000000"><td><table width="600" border="0" cellspacing="0" cellpadding="0"><tr color="#000000"><td style="padding-left: 20px;padding-right: 20px"><strong style="color:black">'.$ses[0]['raison_sociale'].'</strong></td></tr></table></td></tr></table></body></html>';

                
                $destinataire2 = '"'.$ses[0]['email'].'"';
                $expediteur2 = 'noreplay@edicom.ma';
                $objet2 = 'Confirmation de reception de votre demande espace client';

                $headers2 = 'From: Espace client Telecontact <' . $destinataire2 . '> ' . "\r\n" 
                .'Reply-To: Espace client Telecontact <' . $destinataire2 . '> ' . "\r\n"
                .'Content-Type:text/html;charset=utf-8' . "\r\n" 
                .'X-Mailer: PHP/' . phpversion();
                $headers2 .= "Return-Path: Espace client telecontact <" . $destinataire2 . '> ' . "\r\n";


                $message2 = '<html><head><title>Demande espace client telecontact</title></head><body><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FDFDFD"><tr style="background-color:#ffdd00;color:black"><td height="80" align="center" valign="middle" bgcolor="#ffdd00" color="#ffffff"><h1>Demande espace client telecontact</h1><tr><td style="padding: 35px;font-size: 17px;line-height: 25px;">Bonjour,<br><br><label>Nous accusons bonne réception de votre réclamation et mettons tout en oeuvre pour vous apporter une réponse dans les meilleurs délais.</span></td></tr><tr><td><p style="padding-left: 6%;padding-bottom: 20px;font-size: 17px;">Cordialement.</p></td></tr><tr height="70" align="center" valign="middle" bgcolor="#ffdd00" color="#000000"><td><table width="600" border="0" cellspacing="0" cellpadding="0"><tr color="#000000"><td style="padding-left: 20px;padding-right: 20px"><strong style="color:black">L\'équipe Telecontact</strong></td></tr></table></td></tr></table></body></html>';

                

                mail($destinataire, $objet, $message, $headers);
                mail($destinataire2, $objet2, $message2, $headers2);
                


                /* end mail */








                $ticket = $connection3->fetchAll('SELECT * FROM `ticket` where `code_firme` = "'.$code_firme.'" ');
            return $this->render('EcommerceBundle:Administration:statistics/ticket.html.twig', array('ticket' => $ticket));

            }


            return $this->render('EcommerceBundle:Administration:statistics/ticket.html.twig', array('ticket' => $ticket));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
     }



     public function ticketListAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';

            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));
            $connection3= $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3;charset=utf8",$username2,$password2)));

            

            $Allticket = $connection3->fetchAll('
                      SELECT * FROM espace_clients.`ticket` ti INNER join(
                      SELECT acc.code_firme,acc.raison_sociale,acc.email1,emp.last_name
                      FROM erpprod.`u_yf_ssingleorders` s
                      INNER JOIN erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                      INNER JOIN erpprod.vtiger_account acc on acc.accountid=s.firme
                      WHERE s.support = "Telecontact" and s.type_ordre = "Internet"
                      group by acc.code_firme) res
                      WHERE ti.code_firme = res.code_firme
                ');/*SELECT ti.*, u.`code_firme`, u.`raison_sociale`, u.`email`
                    FROM espace_clients.`ticket` ti 
                    INNER join espace_clients.`utilisateurs`u on u.`id_user` = ti.`id_user`*/

            $ticket = $connection3->fetchAll("
                  SELECT * from espace_clients.ticket ti INNER join(
                  SELECT acc.code_firme,acc.raison_sociale,acc.email1,emp.last_name
                  FROM erpprod.`u_yf_ssingleorders` s
                  INNER JOIN erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                  INNER JOIN erpprod.vtiger_account acc on acc.accountid=s.firme
                  WHERE `responsable` = '".$ses[0]['code_commercial']."' and s.support = 'Telecontact' and s.type_ordre = 'Internet'
                   group by acc.code_firme) res
                   WHERE ti.code_firme = res.code_firme");

           

                   /*die('beh');*/

            return $this->render('EcommerceBundle:Administration:statistics/ticketList.html.twig', array('Allticket' => $Allticket, 'ticket' => $ticket));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }

     public function ticketSolveAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';

            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));
            $connection3= $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3;charset=utf8",$username2,$password2)));

            

            $tickets = $connection3->fetchAll('SELECT * FROM espace_clients.`ticket`');

            return $this->render('EcommerceBundle:Administration:statistics/ticketSolve.html.twig', array('tickets' => $tickets));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function change_ticket_statAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';

            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));
            $connection3= $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3;charset=utf8",$username2,$password2)));

            
            $etat       = $request->get('stat');
            $code_firme = $request->get('code_firme');
            $id         = $request->get('id');



            $update = $connection3->executeQuery('UPDATE espace_clients.`ticket` set `etat` = "'.$etat .'" where `id`= "'.$id.'" ');

            
            

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }



     public function avis_listAction(Request $request, $code_firme){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

        

         


            $comments = $connection->fetchAll('select r.client_id, r.id as id_comment, r.titre_commentaire, r.commentaire, r.date_creation, r.note_globale, r.publication, r.etat, re.id, re.comment_id, re.response, re.date_creation as rd, CASE when r.whoami = 1 THEN CONCAT(cu.nom," ",cu.prenom) WHEN r.whoami = 3 THEN ecu.raison_sociale ELSE CONCAT(u.nom," ",u.prenom) END AS user
                                                from reviews r 
                                                LEFT JOIN replay re on re.comment_id = r.id
                                                LEFT JOIN espace_clients.utilisateurs ecu ON ecu.id_user = r.client_id
                                                LEFT JOIN CRM_EDICOM.tts_utilisateur cu ON cu.id= r.client_id
                                                LEFT JOIN telecontact_BackOffice_Site.`MOBILE_utilisateur_web` u on u.id_user = r.client_id
                                                where r.code_firme = "'.$code_firme.'" ');


            

            $count_rep = $connection->fetchAll('select ecu.raison_sociale as user, r.id, r.titre_commentaire, r.commentaire, r.date_creation, r.note_globale, r.publication, r.etat, re.id, re.comment_id, re.response, count(re.response) as rep, re.date_creation as rd
                                                from reviews r 
                                                LEFT JOIN replay re on re.comment_id = r.id
                                                LEFT JOIN espace_clients.utilisateurs ecu ON ecu.id_user = r.client_id
                                                where r.code_firme = "'.$code_firme.'" ');

           $comments = array_map(function($c){
                $c['commentaire'] = utf8_decode($c['commentaire']);
                return $c;
           }, $comments);


            
            
            return $this->render("EcommerceBundle:Administration:statistics/avis_list.html.twig", array('comments' => $comments));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     /*public function responseAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        if(!empty($ses)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            
            $comment_id       = $request->get('comment');
            $insertdate    = date('Y-m-d H:i:s');
            $response      = $request->get('response');


            $update = $connection->executeQuery('insert into replay(comment_id, response, date_creation) VALUES ("'.$comment_id.'", "'.$response.'", "'.$insertdate.'") ');
                       

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function editresponseAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        if(!empty($ses)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            
            $commentaire       = $request->get('commentaire');
            $insertdate        = date('Y-m-d H:i:s');
            $reponse           = $request->get('reponse');


            $update = $connection->executeQuery('UPDATE replay SET response = "'.$reponse.'" WHERE id = "'.$commentaire.'" ');
                       

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function deleteresponseAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        if(!empty($ses)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            
            $commentaire       = $request->get('commentaire');
            $id                = $request->get('id');


            $update = $connection->executeQuery('DELETE FROM replay WHERE id = "'.$id.'" ');
                       

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function EtatAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        if(!empty($ses)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';

            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            
           




            $id = $_POST['id'];
            $comment_id = $_POST['comment'];
            $response = $_POST['response'];
            $responder_id = $_SESSION['utilisateur_web_id'];
            $insertdate = date('Y-m-d H:i:s');
            $comment_num = $row['id'];



            $id_avis = $POST['id'];
            $etat   =  $POST['etat'];





            $update = $connection->executeQuery('insert into replay(comment_id, response, date_creation) VALUES ("'.$comment_id.'", "'.$response.'", "'.$insertdate.'" )');
                       

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }*/


     public function changestateAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

        
            $id_avis = $request->get('id');
            $etat   =  $request->get('etat');

            $update = $connection->executeQuery('UPDATE reviews SET etat = "'.$etat.'"  WHERE id = "'.$id_avis.'" ');
                       

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function changevisibitityAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

        
            $id_avis = $request->get('id');
            $visibility   =  $request->get('visibility');

            

            $avis_a_supprimer = $connection->fetchAll('SELECT * FROM reviews r  WHERE r.`id` = "'.$id_avis.'" ');

            /* envoi de mail au service client */

            $destinataire = 'r.malk@edicom.ma';
            $expediteur = '"'.$ses[0]['email'].'"';
            $objet = 'Demande suppression d\'un avis sur ma fiche Telecontact'; 
            $headers = 'From: Espace client Telecontact <' . $destinataire . '> ' . "\r\n" 
                .'Reply-To: Espace client Telecontact <' . $destinataire . '> ' . "\r\n"
                .'Content-Type:text/html;charset=utf-8' . "\r\n" 
                .'X-Mailer: PHP/' . phpversion();
            $headers .= "Return-Path: Espace client telecontact <" . $destinataire . '> ' . "\r\n";
            $message = '<html><head><title>Demande suppression avis telecontact</title></head><body><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FDFDFD"><tr style="background-color:#ffdd00;color:black"><td height="80" align="center" valign="middle" bgcolor="#ffdd00" color="#ffffff"><h1>Demande espace client telecontact</h1></td></tr><tr><td height="60" color="#000000"><p style="padding-left: 35px;padding-right: 20px;padding-top:35px;font-size: 17px;">Bonjour,<br><br>Merci de supprimer l\'avis ci dessous de ma fiche telecontact</p></td></tr><tr><td style="padding: 35px;font-size: 17px;line-height: 25px;"><strong>Avis a supprimer : </strong><label>'.utf8_decode($avis_a_supprimer[0]['commentaire']). ' <strong>posté le </strong>'.$avis_a_supprimer[0]['date_creation'].'</span></td></tr><tr><td><p style="padding-left: 6%;padding-bottom: 20px;font-size: 17px;">Cordialement.</p></td></tr><tr height="70" align="center" valign="middle" bgcolor="#ffdd00" color="#000000"><td><table width="600" border="0" cellspacing="0" cellpadding="0"><tr color="#000000"><td style="padding-left: 20px;padding-right: 20px"><strong style="color:black"></strong></td></tr></table></td></tr></table></body></html>';


            $update = $connection->executeQuery('UPDATE reviews SET publication = "'.$visibility.'"  WHERE id = "'.$id_avis.'" ');


            mail($destinataire, $objet, $message, $headers);

            /* end envoi de mail au service client */
                       

            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }


     }

     public function saveReplayAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

        
            $id_avis = $request->get('id');
            $replay  = $request->get('replay');
            $date    = date("Y-m-d H:i:s");


            $replays = $connection->fetchAll('SELECT * FROM replay WHERE comment_id = "'.$id_avis.'" ');

            if(empty($replays)){
                $insert = $connection->executeQuery('INSERT INTO replay (response,comment_id,date_creation) VALUES ("'.$replay.'", "'.$id_avis.'", "'.$date.'")');
            }else{
                $update = $connection->executeQuery('UPDATE replay SET response = "'.$replay.'"  WHERE comment_id = "'.$id_avis.'" ');
            }


            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }


     }

     public function deleteReplayAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

        
            $id_avis = $request->get('id');

            $update = $connection->executeQuery('DELETE FROM replay WHERE comment_id = "'.$id_avis.'" ');
            
            $response = new JsonResponse();
            return $response->setData();
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }


     }


     public function statistiquesAction(Request $request, $code_firme){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

             /* firme info */
            $session=$request->getSession();
            $data_vignette = $session->get('data_vignette');
            /* end firme info */


            return $this->render('EcommerceBundle:Administration:statistics/statistiques.html.twig',array('data_vignette' => $data_vignette));

            
        }else{

            return $this->redirect($this->generateUrl('connection'));

        }


     }

     public function etatMessageAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

            $id = $request->get('id');
            /*var_dump($id);*/
            $devis = $connection->executeQuery("UPDATE Demande_devis_Details SET Etat_Message = 1 where Devis_ID =$id");



            $response = new JsonResponse();
            return $response->setData();

            
        }else{

            return $this->redirect($this->generateUrl('connection'));

        }

     }

     public function etatDevisAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

            $id = $request->get('id');
            $devis = $connection->executeQuery("UPDATE `demande_devis` SET `etat` = 1 where id =$id");


            $response = new JsonResponse();
            return $response->setData();

            
        }else{

            return $this->redirect($this->generateUrl('connection'));

        }

     }





     public function modifierSocieteTlcAction(Request $request, $code_firme,$id){

            ini_set('memory_limit', '-1');
            $id_user=$id;

            /*
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            */

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));


            $firme = $connection->fetchAll("SELECT a.`arrondissement`, v.`ville` , q.`quartier`, f.* 
                                            FROM `firmes` f 
                                            LEFT JOIN quartiers q on q.`code` = f.`code_quart`
                                            LEFT JOIN arrondissements a on a.`code` = f.`code_arr`
                                            LEFT JOIN villes v on v.`code` = f.`code_ville`
                                            WHERE f.`code_firme` = CONCAT('MA', '".$code_firme."')
                                          ");

            $firme = array_map(function($c){
                                $c['ville'] = utf8_encode($c['ville']);
                                $c['rs_comp'] = utf8_encode($c['rs_comp']);
                                $c['lib_voie'] = utf8_encode($c['lib_voie']);
                                $c['comp_voie'] = utf8_encode($c['comp_voie']);
                                $c['quartier'] = utf8_encode($c['quartier']);
                                $c['arrondissement'] = utf8_encode($c['arrondissement']);
                                $c['tp_40'] = utf8_encode($c['tp_40']);
                                $c['tp_48'] = utf8_encode($c['tp_48']);
                                $c['act_longue'] = utf8_encode($c['act_longue']);
                                return $c; }, $firme);


            /* list statuts */            
            $statuts = $connection->fetchAll("SELECT code, status FROM `statuts`");
            /* end list statuts */ 

            /* list natures */
            $natures = $connection->fetchAll("SELECT code, nature FROM `natures`");
            $natures = array_map(function($c){
                                $c['nature'] = utf8_encode($c['nature']);
                                return $c; }, $natures);
            /* list natures */

            /* list villes */
            $villes = $connection->fetchAll("SELECT * FROM `villes`");
            $villes = array_map(function($c){
                                $c['ville'] = utf8_encode($c['ville']);
                                return $c; }, $villes);
            /* end list villes */

            /* list type */
            $type = array('Siège', 'Succursale', 'Agence', 'Usine', 'Showroom');
            /* end list type */

            /* list type */
            $formes_juridiques = $connection->fetchAll("SELECT code,forme_jur FROM formes_juridiques");
            $formes_juridiques = array_map(function($c){
                                $c['forme_jur'] = utf8_encode($c['forme_jur']);
                                return $c; }, $formes_juridiques);
            /* end list type */

            /* list gamme_ca */
            $gamme_ca = $connection->fetchAll("SELECT gamme_ca,libelle FROM gamme_ca");
            $gamme_ca = array_map(function($c){
                                $c['libelle'] = utf8_encode($c['libelle']);
                                return $c; }, $gamme_ca);
            /* end list gamme_ca */



            /* list lien_portable */
            $lien_portable = $connection->fetchAll("SELECT num_ordre,portable FROM lien_portable WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_portable_count = count($lien_portable);
            /* end list lien_portable */


            /* list lien_telephone */
            $lien_telephone = $connection->fetchAll("SELECT num_ordre,tel FROM lien_telephone WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_telephone_count = count($lien_telephone);
            /* end list lien_telephone */


            /* list lien_fax */
            $lien_fax = $connection->fetchAll("SELECT num_ordre,fax FROM lien_fax WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_fax_count = count($lien_fax);
            /* end list lien_fax */


            /* list lien_email */
            $lien_email = $connection->fetchAll("SELECT num_ordre,email FROM lien_email WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_email_count = count($lien_email);
            /* end list lien_email */


            /* list lien_web */
            $lien_web = $connection->fetchAll("SELECT num_ordre,web FROM lien_web WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_web_count = count($lien_web);
            /* end list lien_web */


            /* list lien_dirigeant */
            $lien_dirigeant = $connection->fetchAll("SELECT d.`id`, d.`code_personne`, d.`code_firme`, d.`code_fonction`, d.`email`, d.`tel_1`, d.`tel_2`, d.`fax`, p.`sex`, p.`civilite`, p.`nom`, p.`prenom`
                                               FROM `lien_dirigeant` d
                                               INNER JOIN `personne` p on d.`code_personne` = p.`code_personne`
                                               WHERE `code_firme` = CONCAT('MA','".$code_firme."')
                                              ");
            $lien_dirigeant = array_map(function($c){
                                $c['nom'] = utf8_encode($c['nom']);
                                $c['prenom'] = utf8_encode($c['prenom']);
                                return $c; }, $lien_dirigeant);
            $lien_dirigeant_count = count($lien_dirigeant);
            /* end list lien_dirigeant */

            /* list lien_civilite */
            $lien_civilite = $connection->fetchAll("SELECT * FROM `civilite`");
            $lien_civilite = array_map(function($c){
                                $c['civilite'] = utf8_encode($c['civilite']);
                                return $c; }, $lien_civilite);
            $lien_civilite_count = count($lien_civilite);
            /* end list lien_civilite */

            /* list lien_fonction */
            $lien_fonction = $connection->fetchAll("SELECT * FROM `fonction` WHERE fonction != '' ");
            $lien_fonction = array_map(function($c){
                                $c['fonction'] = utf8_encode($c['fonction']);
                                return $c; }, $lien_fonction);
            $lien_fonction_count = count($lien_fonction);
            /* end list lien_fonction */

            /* list des validations */
            $liste_validation = $connection->fetchAll("SELECT * FROM CRM_EDICOM.`tts_modification_externe` WHERE code_firme = '".$code_firme."' and etat = 1 ");
            $liste_validation = array_map(function($c){
                                $c['old_value'] = utf8_encode($c['old_value']);
                                $c['new_value'] = utf8_encode($c['new_value']);
                                $c['displayed_column'] = utf8_encode($c['displayed_column']);
                                return $c; }, $liste_validation);
            $liste_validation_count = count($liste_validation);
            /* end list des validations */

            /* list des dirigeants */
            $liste_dirigeant = $connection->fetchAll("SELECT d.* , f.`fonction`, c.`civilite`
                                                      FROM CRM_EDICOM.`tts_ajout_dirigeant_externe` d
                                                      LEFT JOIN BD_EDICOM.`fonction` f on f.`code` = d.`code_fonction`
                                                      LEFT JOIN BD_EDICOM.`civilite` c on c.`code` = d.`civilite`
                                                      WHERE d.`code_firme` = '".$code_firme."' and etat = 1 
                                                    ");
            $liste_dirigeant = array_map(function($c){
                                $c['nom'] = utf8_encode($c['nom']);
                                $c['prenom'] = utf8_encode($c['prenom']);
                                $c['fonction'] = utf8_encode($c['fonction']);
                                $c['civilite'] = utf8_encode($c['civilite']);
                                return $c; }, $liste_dirigeant);
            $liste_dirigeant_count = count($liste_dirigeant);
            /* end list des dirigeants */

            /* list des prestation_validations */
            $liste_prestation_validation = $connection->fetchAll("SELECT p.* , r.`Lib_Rubrique`, r.`Code_Rubrique`
                                                      FROM CRM_EDICOM.`tts_ajout_prestation_externe` p
                                                      LEFT JOIN BD_EDICOM.`rubriques` r on r.`Code_Rubrique` = p.`rubrique`
                                                      WHERE p.`code_firme` = '".$code_firme."' and etat = 1 
                                                    ");
            $liste_prestation_validation = array_map(function($c){
                                $c['Lib_Rubrique'] = utf8_encode($c['Lib_Rubrique']);
                                $c['prestation'] = utf8_encode($c['prestation']);
                                return $c; }, $liste_prestation_validation);
            $liste_prestation_validation_count = count($liste_prestation_validation);
            /* end list des prestation_validations */

            /* liste des prestations */
            $liste_prestation = $connection->fetchAll("SELECT p.`id`, p.`prestation`, p.`code_firme`, r.`Code_Rubrique`, Lib_Rubrique FROM firme_prestation p join rubriques r on p.`rubrique_id` = r.`Code_Rubrique` WHERE p.`code_firme` = CONCAT('MA', '".$code_firme."') ");
            $liste_prestation = array_map(function($c){
                                    $c['prestation'] = utf8_encode($c['prestation']);
                                    $c['Lib_Rubrique'] = utf8_encode($c['Lib_Rubrique']);
                                    return $c;
                                    }, $liste_prestation);
            $liste_prestation_count = count($liste_prestation);
            /* end liste des prestations */

            /* liste des marques */
            $liste_marque = $connection->fetchAll("SELECT l.`id`,p.`pays`,m.`nom_marque`,m.`description` FROM marque m LEFT OUTER JOIN pays p ON p.`code_pays` = m.`code_pays` INNER JOIN lien_marque l ON l.`code_marque` = m.`code_marque` AND l.`code_firme` = CONCAT('MA', '".$code_firme."') ");

            
            $liste_marque = array_map(function($c){
                                    $c['pays'] = utf8_encode($c['pays']);
                                    $c['nom_marque'] = utf8_encode($c['nom_marque']);
                                    $c['description'] = utf8_encode($c['description']);
                                    return $c;
                                    }, $liste_marque);
            $liste_marque_count = count($liste_marque);
            /* end liste des marque */

            /* list des rubrique */
            $lien_rubrique = $connection->fetchAll("SELECT `Code_Rubrique`, `Lib_Rubrique` FROM rubriques ");
            $lien_rubrique = array_map(function($c){
                                $c['Lib_Rubrique'] = utf8_encode($c['Lib_Rubrique']);
                                return $c; }, $lien_rubrique);
            /* end list des rubrique */

// die('ici');
            return $this->render("EcommerceBundle:Administration:statistics/modifierSocieteTlc.html.twig", array('code_firme' => $code_firme, 'firme' => $firme, 'villes_list' => $villes, 'type' => $type, 'natures' => $natures, 'statuts' => $statuts, 'formes_juridiques' => $formes_juridiques, 'gamme_ca' => $gamme_ca, 'lien_portable' => $lien_portable, 'lien_portable_count' => $lien_portable_count, 'lien_telephone' => $lien_telephone, 'lien_telephone_count' => $lien_telephone_count, 'lien_fax' => $lien_fax, 'lien_fax_count' => $lien_fax_count, 'lien_email' => $lien_email, 'lien_email_count' => $lien_email_count, 'lien_web' => $lien_web, 'lien_web_count' => $lien_web_count, 'liste_validation' => $liste_validation, 'liste_validation_count' => $liste_validation_count, 'lien_dirigeant' => $lien_dirigeant, 'lien_dirigeant_count' => $lien_dirigeant_count, 'lien_civilite' => $lien_civilite, 'lien_fonction' => $lien_fonction, 'liste_dirigeant' => $liste_dirigeant, 'liste_dirigeant_count' => $liste_dirigeant_count, 'liste_prestation' => $liste_prestation, 'liste_prestation_count' => $liste_prestation_count, 'liste_marque' => $liste_marque, 'liste_marque_count' => $liste_marque_count, 'lien_rubrique' => $lien_rubrique, 'liste_prestation_validation' => $liste_prestation_validation, 'liste_prestation_validation_count' => $liste_prestation_validation_count,'id_user'=> $id_user));

     }

     public function modifierSocieteDataTlcAction(Request $request){



            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            
            $ses_id_user   = $request->get('id_user');
            $code_firme    = $request->get('code_firme');
            $id            = $request->get('id');

            $id_prestation = $request->get('id_prestation');
  
            $table         = $request->get('table');
            $table_row     = $request->get('table_row');
            $champ         = $request->get('champ');
            $new_val       = $request->get('new_val');
            $operation     = $request->get('operation');
            $code_personne = $request->get('code_personne');

            $rs_comps      = $connection->fetchAll("SELECT rs_comp,id FROM BD_EDICOM.firmes WHERE code_firme = CONCAT('MA', '".$code_firme."') ");
            $rs_comp       = $rs_comps[0]['rs_comp'];
             $id_firme     = $rs_comps[0]['id'];
            
            if($operation != 'add'){
                $action = 2;
                if($champ == 'adresse'){        
                    $old_val     = $connection->fetchAll("SELECT f.`num_voie`, f.`lib_voie`, f.`comp_voie`, q.`quartier`, a.`arrondissement` 
                                                          FROM BD_EDICOM.".$table." f
                                                          LEFT JOIN BD_EDICOM.`quartiers` q on q.`code` = f.`code_quart`
                                                          LEFT JOIN BD_EDICOM.arrondissements a on a.`code` = f.`code_arr`
                                                          WHERE f.code_firme = CONCAT('MA', '".$code_firme."') ");
                    $old_val     = $old_val[0]['num_voie'] .' '. $old_val[0]['lib_voie'] .' '. $old_val[0]['comp_voie'] .' '. $old_val[0]['quartier'] .' '. $old_val[0]['arrondissement'];
                }elseif($table == 'personne'){ 
                    $old_val     = $connection->fetchAll("SELECT p.".$table_row."
                                                          FROM BD_EDICOM.`lien_dirigeant` d
                                                          LEFT JOIN BD_EDICOM.".$table." p on d.`code_personne` = p.`code_personne`
                                                          WHERE d.code_firme = CONCAT('MA', '".$code_firme."') AND d.`code_personne` = '".$code_personne."' ");
                    $old_val     = $old_val[0][$table_row];
                }elseif($table == 'firme_prestation'){ 
                    $old_val     = $connection->fetchAll("SELECT ".$table_row." FROM BD_EDICOM.".$table." WHERE code_firme = CONCAT('MA', '".$code_firme."') and id = '".$id_prestation."' ");
                    $old_val     = $old_val[0][$table_row];
                }else{
                    $old_val     = $connection->fetchAll("SELECT ".$table_row." FROM BD_EDICOM.".$table." WHERE code_firme = CONCAT('MA', '".$code_firme."') ");
                    $old_val     = $old_val[0][$table_row];
                }
            }else{
                $old_val     = '';
                $action = 1;
            }

            /*echo "INSERT INTO tts_modification_externe (update_date, edited_table, edited_column, displayed_column, operation, old_value, new_value, id_user, code_firme) VALUES(date('Y-m-d H:i:s'), $table, $table_row, $champ, $action, $old_val, $new_val, $ses_id_user, $code_firme)";*/
            
            $type=2;

            $insert_query  = $connection->prepare("INSERT INTO tts_modification_externe (update_date, edited_table, edited_column, displayed_column, operation, old_value, new_value, id_user, code_firme, code_personne, id_prestation,type,rs_com, id_firme ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $execute_query = $insert_query->execute(array(date('Y-m-d H:i:s'), $table, $table_row, $champ, $action, $old_val, $new_val, $ses_id_user, $code_firme, $code_personne, $id_prestation,$type,$rs_comp, $id_firme ));
            

            die('end');
            $response = new JsonResponse();
            return $response->setData();

   
     }






















     public function modifierSocieteAction(Request $request, $code_firme){

        $session      = $request->getSession();
        $ses          = $session->get('utilisateur');
        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){
            $code_firme = $code_firme;

            /*
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='telecontact_BackOffice_Site';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            */

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));


            $firme = $connection->fetchAll("SELECT a.`arrondissement`, v.`ville` , q.`quartier`, f.* 
                                            FROM `firmes` f 
                                            LEFT JOIN quartiers q on q.`code` = f.`code_quart`
                                            LEFT JOIN arrondissements a on a.`code` = f.`code_arr`
                                            LEFT JOIN villes v on v.`code` = f.`code_ville`
                                            WHERE f.`code_firme` = CONCAT('MA', '".$code_firme."')
                                          ");

            $firme = array_map(function($c){
                                $c['ville'] = utf8_encode($c['ville']);
                                $c['rs_comp'] = utf8_encode($c['rs_comp']);
                                $c['lib_voie'] = utf8_encode($c['lib_voie']);
                                $c['comp_voie'] = utf8_encode($c['comp_voie']);
                                $c['quartier'] = utf8_encode($c['quartier']);
                                $c['arrondissement'] = utf8_encode($c['arrondissement']);
                                $c['tp_40'] = utf8_encode($c['tp_40']);
                                $c['tp_48'] = utf8_encode($c['tp_48']);
                                $c['act_longue'] = utf8_encode($c['act_longue']);
                                return $c; }, $firme);


            /* list statuts */            
            $statuts = $connection->fetchAll("SELECT code, status FROM `statuts`");
            /* end list statuts */ 

            /* list natures */
            $natures = $connection->fetchAll("SELECT code, nature FROM `natures`");
            $natures = array_map(function($c){
                                $c['nature'] = utf8_encode($c['nature']);
                                return $c; }, $natures);
            /* list natures */

            /* list villes */
            $villes = $connection->fetchAll("SELECT * FROM `villes`");
            $villes = array_map(function($c){
                                $c['ville'] = utf8_encode($c['ville']);
                                return $c; }, $villes);
            /* end list villes */

            /* list type */
            $type = array('Siège', 'Succursale', 'Agence', 'Usine', 'Showroom');
            /* end list type */

            /* list type */
            $formes_juridiques = $connection->fetchAll("SELECT code,forme_jur FROM formes_juridiques");
            $formes_juridiques = array_map(function($c){
                                $c['forme_jur'] = utf8_encode($c['forme_jur']);
                                return $c; }, $formes_juridiques);
            /* end list type */

            /* list gamme_ca */
            $gamme_ca = $connection->fetchAll("SELECT gamme_ca,libelle FROM gamme_ca");
            $gamme_ca = array_map(function($c){
                                $c['libelle'] = utf8_encode($c['libelle']);
                                return $c; }, $gamme_ca);
            /* end list gamme_ca */



            /* list lien_portable */
            $lien_portable = $connection->fetchAll("SELECT num_ordre,portable FROM lien_portable WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_portable_count = count($lien_portable);
            /* end list lien_portable */


            /* list lien_telephone */
            $lien_telephone = $connection->fetchAll("SELECT num_ordre,tel FROM lien_telephone WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_telephone_count = count($lien_telephone);
            /* end list lien_telephone */


            /* list lien_fax */
            $lien_fax = $connection->fetchAll("SELECT num_ordre,fax FROM lien_fax WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_fax_count = count($lien_fax);
            /* end list lien_fax */


            /* list lien_email */
            $lien_email = $connection->fetchAll("SELECT num_ordre,email FROM lien_email WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_email_count = count($lien_email);
            /* end list lien_email */


            /* list lien_web */
            $lien_web = $connection->fetchAll("SELECT num_ordre,web FROM lien_web WHERE code_firme = CONCAT('MA','".$code_firme."') order by num_ordre");
            $lien_web_count = count($lien_web);
            /* end list lien_web */


            /* list lien_dirigeant */
            $lien_dirigeant = $connection->fetchAll("SELECT d.`id`, d.`code_personne`, d.`code_firme`, d.`code_fonction`, d.`email`, d.`tel_1`, d.`tel_2`, d.`fax`, p.`sex`, p.`civilite`, p.`nom`, p.`prenom`
                                               FROM `lien_dirigeant` d
                                               INNER JOIN `personne` p on d.`code_personne` = p.`code_personne`
                                               WHERE `code_firme` = CONCAT('MA','".$code_firme."')
                                              ");
            $lien_dirigeant = array_map(function($c){
                                $c['nom'] = utf8_encode($c['nom']);
                                $c['prenom'] = utf8_encode($c['prenom']);
                                return $c; }, $lien_dirigeant);
            $lien_dirigeant_count = count($lien_dirigeant);
            /* end list lien_dirigeant */

            /* list lien_civilite */
            $lien_civilite = $connection->fetchAll("SELECT * FROM `civilite`");
            $lien_civilite = array_map(function($c){
                                $c['civilite'] = utf8_encode($c['civilite']);
                                return $c; }, $lien_civilite);
            $lien_civilite_count = count($lien_civilite);
            /* end list lien_civilite */

            /* list lien_fonction */
            $lien_fonction = $connection->fetchAll("SELECT * FROM `fonction` WHERE fonction != '' ");
            $lien_fonction = array_map(function($c){
                                $c['fonction'] = utf8_encode($c['fonction']);
                                return $c; }, $lien_fonction);
            $lien_fonction_count = count($lien_fonction);
            /* end list lien_fonction */

            /* list des validations */
            $liste_validation = $connection->fetchAll("SELECT * FROM CRM_EDICOM.`tts_modification_externe` WHERE code_firme = '".$code_firme."' and etat = 1 ");
            $liste_validation = array_map(function($c){
                                $c['old_value'] = utf8_encode($c['old_value']);
                                $c['new_value'] = utf8_encode($c['new_value']);
                                $c['displayed_column'] = utf8_encode($c['displayed_column']);
                                return $c; }, $liste_validation);
            $liste_validation_count = count($liste_validation);
            /* end list des validations */

            /* list des dirigeants */
            $liste_dirigeant = $connection->fetchAll("SELECT d.* , f.`fonction`, c.`civilite`
                                                      FROM CRM_EDICOM.`tts_ajout_dirigeant_externe` d
                                                      LEFT JOIN BD_EDICOM.`fonction` f on f.`code` = d.`code_fonction`
                                                      LEFT JOIN BD_EDICOM.`civilite` c on c.`code` = d.`civilite`
                                                      WHERE d.`code_firme` = '".$code_firme."' and etat = 1 
                                                    ");
            $liste_dirigeant = array_map(function($c){
                                $c['nom'] = utf8_encode($c['nom']);
                                $c['prenom'] = utf8_encode($c['prenom']);
                                $c['fonction'] = utf8_encode($c['fonction']);
                                $c['civilite'] = utf8_encode($c['civilite']);
                                return $c; }, $liste_dirigeant);
            $liste_dirigeant_count = count($liste_dirigeant);
            /* end list des dirigeants */

            /* list des prestation_validations */
            $liste_prestation_validation = $connection->fetchAll("SELECT p.* , r.`Lib_Rubrique`, r.`Code_Rubrique`
                                                      FROM CRM_EDICOM.`tts_ajout_prestation_externe` p
                                                      LEFT JOIN BD_EDICOM.`rubriques` r on r.`Code_Rubrique` = p.`rubrique`
                                                      WHERE p.`code_firme` = '".$code_firme."' and etat = 1 
                                                    ");
            $liste_prestation_validation = array_map(function($c){
                                $c['Lib_Rubrique'] = utf8_encode($c['Lib_Rubrique']);
                                $c['prestation'] = utf8_encode($c['prestation']);
                                return $c; }, $liste_prestation_validation);
            $liste_prestation_validation_count = count($liste_prestation_validation);
            /* end list des prestation_validations */

            /* liste des prestations */
            $liste_prestation = $connection->fetchAll("SELECT p.`id`, p.`prestation`, p.`code_firme`, r.`Code_Rubrique`, Lib_Rubrique FROM firme_prestation p join rubriques r on p.`rubrique_id` = r.`Code_Rubrique` WHERE p.`code_firme` = CONCAT('MA', '".$code_firme."') ");
            $liste_prestation = array_map(function($c){
                                    $c['prestation'] = utf8_encode($c['prestation']);
                                    $c['Lib_Rubrique'] = utf8_encode($c['Lib_Rubrique']);
                                    return $c;
                                    }, $liste_prestation);
            $liste_prestation_count = count($liste_prestation);
            /* end liste des prestations */

            /* liste des marques */
            $liste_marque = $connection->fetchAll("SELECT l.`id`,p.`pays`,m.`nom_marque`,m.`description` FROM marque m LEFT OUTER JOIN pays p ON p.`code_pays` = m.`code_pays` INNER JOIN lien_marque l ON l.`code_marque` = m.`code_marque` AND l.`code_firme` = CONCAT('MA', '".$code_firme."') ");

            
            $liste_marque = array_map(function($c){
                                    $c['pays'] = utf8_encode($c['pays']);
                                    $c['nom_marque'] = utf8_encode($c['nom_marque']);
                                    $c['description'] = utf8_encode($c['description']);
                                    return $c;
                                    }, $liste_marque);
            $liste_marque_count = count($liste_marque);
            /* end liste des marque */

            /* list des rubrique */
            $lien_rubrique = $connection->fetchAll("SELECT `Code_Rubrique`, `Lib_Rubrique` FROM rubriques ");
            $lien_rubrique = array_map(function($c){
                                $c['Lib_Rubrique'] = utf8_encode($c['Lib_Rubrique']);
                                return $c; }, $lien_rubrique);
            /* end list des rubrique */


            return $this->render("EcommerceBundle:Administration:statistics/modifierSociete.html.twig", array('code_firme' => $code_firme, 'firme' => $firme, 'villes_list' => $villes, 'type' => $type, 'natures' => $natures, 'statuts' => $statuts, 'formes_juridiques' => $formes_juridiques, 'gamme_ca' => $gamme_ca, 'lien_portable' => $lien_portable, 'lien_portable_count' => $lien_portable_count, 'lien_telephone' => $lien_telephone, 'lien_telephone_count' => $lien_telephone_count, 'lien_fax' => $lien_fax, 'lien_fax_count' => $lien_fax_count, 'lien_email' => $lien_email, 'lien_email_count' => $lien_email_count, 'lien_web' => $lien_web, 'lien_web_count' => $lien_web_count, 'liste_validation' => $liste_validation, 'liste_validation_count' => $liste_validation_count, 'lien_dirigeant' => $lien_dirigeant, 'lien_dirigeant_count' => $lien_dirigeant_count, 'lien_civilite' => $lien_civilite, 'lien_fonction' => $lien_fonction, 'liste_dirigeant' => $liste_dirigeant, 'liste_dirigeant_count' => $liste_dirigeant_count, 'liste_prestation' => $liste_prestation, 'liste_prestation_count' => $liste_prestation_count, 'liste_marque' => $liste_marque, 'liste_marque_count' => $liste_marque_count, 'lien_rubrique' => $lien_rubrique, 'liste_prestation_validation' => $liste_prestation_validation, 'liste_prestation_validation_count' => $liste_prestation_validation_count));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
     }

     public function modifierSocieteDataAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');
        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));



            $code_firme    = $request->get('code_firme');
            $id            = $request->get('id');

            $id_prestation = $request->get('id_prestation');
  
            $table         = $request->get('table');
            $table_row     = $request->get('table_row');
            $champ         = $request->get('champ');
            $new_val       = $request->get('new_val');
            $operation     = $request->get('operation');
            $code_personne = $request->get('code_personne');


            $rs_comps      = $connection->fetchAll("SELECT rs_comp,id FROM BD_EDICOM.firmes WHERE code_firme = CONCAT('MA', '".$code_firme."') ");
            $rs_comp       = $rs_comps[0]['rs_comp'];
            $id_firme     = $rs_comps[0]['id'];

            if($operation != 'add'){
                $action = 2;
                if($champ == 'adresse'){        
                    $old_val     = $connection->fetchAll("SELECT f.`num_voie`, f.`lib_voie`, f.`comp_voie`, q.`quartier`, a.`arrondissement` 
                                                          FROM BD_EDICOM.".$table." f
                                                          LEFT JOIN BD_EDICOM.`quartiers` q on q.`code` = f.`code_quart`
                                                          LEFT JOIN BD_EDICOM.arrondissements a on a.`code` = f.`code_arr`
                                                          WHERE f.code_firme = CONCAT('MA', '".$code_firme."') ");
                    $old_val     = $old_val[0]['num_voie'] .' '. $old_val[0]['lib_voie'] .' '. $old_val[0]['comp_voie'] .' '. $old_val[0]['quartier'] .' '. $old_val[0]['arrondissement'];
                }elseif($table == 'personne'){ 
                    $old_val     = $connection->fetchAll("SELECT p.".$table_row."
                                                          FROM BD_EDICOM.`lien_dirigeant` d
                                                          LEFT JOIN BD_EDICOM.".$table." p on d.`code_personne` = p.`code_personne`
                                                          WHERE d.code_firme = CONCAT('MA', '".$code_firme."') AND d.`code_personne` = '".$code_personne."' ");
                    $old_val     = $old_val[0][$table_row];
                }elseif($table == 'firme_prestation'){ 
                    $old_val     = $connection->fetchAll("SELECT ".$table_row." FROM BD_EDICOM.".$table." WHERE code_firme = CONCAT('MA', '".$code_firme."') and id = '".$id_prestation."' ");
                    $old_val     = $old_val[0][$table_row];
                }else{
                    $old_val     = $connection->fetchAll("SELECT ".$table_row." FROM BD_EDICOM.".$table." WHERE code_firme = CONCAT('MA', '".$code_firme."') ");
                    $old_val     = $old_val[0][$table_row];
                }
            }else{
                $old_val     = '';
                $action = 1;
            }

            /*echo "INSERT INTO tts_modification_externe (update_date, edited_table, edited_column, displayed_column, operation, old_value, new_value, id_user, code_firme) VALUES(date('Y-m-d H:i:s'), $table, $table_row, $champ, $action, $old_val, $new_val, $ses_id_user, $code_firme)";*/
            


            $insert_query  = $connection->prepare("INSERT INTO tts_modification_externe (update_date, edited_table, edited_column, displayed_column, operation, old_value, new_value, id_user, code_firme, code_personne, id_prestation,rs_com,id_firme) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $execute_query = $insert_query->execute(array(date('Y-m-d H:i:s'), $table, $table_row, $champ, $action, $old_val, $new_val, $ses_id_user, $code_firme, $code_personne, $id_prestation,$rs_comp,$id_firme));
            

            die('end');
            $response = new JsonResponse();
            return $response->setData();

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
     }


     public function deleteSocieteEditAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            $id = $request->get('id');

            $query = $connection->executeQuery("DELETE FROM tts_modification_externe WHERE id='".$id."' ");

            $response = new JsonResponse();
            return $response->setData();

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }

     }


     public function saveDirigeantAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');
        $ses_id_user = $ses[0]['id_user'];

        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            $code_firme = $request->get('code_firme');
            $sexe       = $request->get('sexe');
            $civilite   = $request->get('civilite');
            $fonction   = $request->get('fonction');
            $nom        = $request->get('nom');
            $prenom     = $request->get('prenom');
            $email      = $request->get('email');
            $tel_1      = $request->get('tel_1');
            $tel_2      = $request->get('tel_2');
            $fax        = $request->get('fax');




            $query   = $connection->prepare("INSERT INTO `tts_ajout_dirigeant_externe` (`etat`, `date_creation`, `code_firme`, `code_fonction`, `nom`, `prenom`, `email`, `tel_1`, `tel_2`, `fax`, `sex`, `civilite`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $execute = $query->execute(array(1,date('Y-m-d H:i:s'), $code_firme, $fonction, $nom, $prenom, $email, $tel_1, $tel_2, $fax, $sexe, $civilite));


            $response = new JsonResponse();
            return $response->setData();
        }else{
            return redirect($this->generateUrl('connection'));
        }

     }


     public function deleteSocieteDirigeantEditAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            $id = $request->get('id');

            $query = $connection->executeQuery("DELETE FROM tts_ajout_dirigeant_externe WHERE id='".$id."' ");

            $response = new JsonResponse();
            return $response->setData();

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }

     }

     public function savePrestationAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');
        $ses_id_user = $ses[0]['id_user'];

        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            $code_firme     = $request->get('code_firme');
            $rubrique       = $request->get('rubrique');
            $prestation     = $request->get('prestation');
            $action         = $request->get('action');


            $query   = $connection->prepare("INSERT INTO `tts_ajout_prestation_externe` (`etat`, `date_creation`, `code_firme`, `rubrique`, `prestation`, `action`) VALUES (?,?,?,?,?,?)");
            $execute = $query->execute(array(1,date('Y-m-d H:i:s'), $code_firme, $rubrique, $prestation, $action));


            $response = new JsonResponse();
            return $response->setData();
        }else{
            return redirect($this->generateUrl('connection'));
        }

     }


     public function deleteSocietePrestationEditAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            $id = $request->get('id');

            $query = $connection->executeQuery("DELETE FROM tts_ajout_prestation_externe WHERE id='".$id."' ");

            $response = new JsonResponse();
            return $response->setData();

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }
    }

    public function deleteSocietePrestationAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            $id             = $request->get('id');
            $code_firme             = $request->get('code_firme');
            $edited_table   = 'firme_prestation';
            $old_value      = $request->get('prestation');

            $insert_query  = $connection->prepare("INSERT INTO tts_modification_externe (update_date, edited_table, operation, old_value, new_value, id_user, code_firme, to_delete) VALUES(?,?,?,?,?,?,?,?)");
            $execute_query = $insert_query->execute(array(date('Y-m-d H:i:s'), $edited_table, 3, $old_value, '', $ses_id_user, $code_firme, $id));

            $response = new JsonResponse();
            return $response->setData();

        }else{

            return $this->redirect($this->generateUrl('connection'));
        }

    }

    public function deleteSocieteDirigeantAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            $id             = $request->get('id');
            $code_firme     = $request->get('code_firme');
            $edited_table   = 'lien_dirigeant';
            $old_value      = $request->get('nom') .' '. $request->get('prenom');
            $code_personne  = $request->get('code_personne');

            $insert_query  = $connection->prepare("INSERT INTO tts_modification_externe (update_date, edited_table, operation, old_value, new_value, code_personne, id_user, code_firme, to_delete) VALUES(?,?,?,?,?,?,?,?,?)");
            $execute_query = $insert_query->execute(array(date('Y-m-d H:i:s'), $edited_table, 4, $old_value, '', $code_personne, $ses_id_user, $code_firme, $id));

            $response = new JsonResponse();
            return $response->setData();

        }else{

            return $this->redirect($this->generateUrl('connection'));
        }

    }


    public function actualiteapiAction($code_firme,  Request $request){



// die('here');
        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = 'fayssal';
        if(!empty($ses_id_user)){


                $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname='localhost';
                $dbname1='telecontact_BackOffice_Site';
                $dbname2='BD_EDICOM';
                $username='pyxicom';
                $password='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname1;charset=utf8",$username,$password)));
                //$connection->mysql_set_charset("utf8");


   

                $actualite = $connection->fetchAll("SELECT * FROM `actualite` WHERE `code_firme` = '$code_firme'");
       if(!empty($actualite)){
                if(!empty($actualite)){
                    $count_presentation_car = strlen($actualite[0]['livraison']);
                }else{
                    $count_presentation_car = 0;
                }
                




                $actualite = array_map(function($c){

                    $c['conditions'] = utf8_encode($c['conditions']);
                    $c['livraison'] = utf8_encode($c['livraison']);
                    $c['savoir'] = utf8_encode($c['savoir']);

                    return $c;
                }, $actualite);


                if(!empty($actualite[0]['prestations'])){
                    $prestation = explode(',', $actualite[0]['prestations']);
                    $prestation = str_replace('"','', $prestation);
                    $prestation = str_replace('[','', $prestation);
                    $prestation = str_replace(']','', $prestation);
                }else{
                    $prestation = '';
                }


                if(!empty($actualite[0]['produits'])){
                    $produit = explode(',', $actualite[0]['produits']);
                    $produit = str_replace('"','', $produit);
                    $produit = str_replace('[','', $produit);
                    $produit = str_replace(']','', $produit);
                }else{
                    $produit = '';
                }


                if(!empty($actualite[0]['marques'])){
                    $marque = explode(',', $actualite[0]['marques']);
                    $marque = str_replace('"','', $marque);
                    $marque = str_replace('[','', $marque);
                    $marque = str_replace(']','', $marque);
                }else{
                    $marque = '';
                }


                if(!empty($actualite[0]['services_plus'])){
                    $service = explode(',', $actualite[0]['services_plus']);
                    $service = str_replace('"','', $service);
                    $service = str_replace('[','', $service);
                    $service = str_replace(']','', $service);
                }else{
                    $service = '';
                }

                if(!empty($actualite[0]['actes'])){
                    $acte = explode(',', $actualite[0]['actes']);
                    $acte = str_replace('"','', $acte);
                    $acte = str_replace('[','', $acte);
                    $acte = str_replace(']','', $acte);
                }else{
                    $acte = '';
                }

                if(!empty($actualite[0]['zones_de_travail'])){
                    $zones_de_travail = explode(',', $actualite[0]['zones_de_travail']);
                    $zones_de_travail = str_replace('"','', $zones_de_travail);
                    $zones_de_travail = str_replace('[','', $zones_de_travail);
                    $zones_de_travail = str_replace(']','', $zones_de_travail);
                }else{
                    $zones_de_travail = '';
                }
                /*var_dump($produit);die('aa');*/

                $existe_facebook     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'facebook'");
                $existe_instagram    = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'instagram'");
                $existe_linkedin     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'linkedin'");
                $existe_whatsapp     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'whatsapp'");

                           


                    $zon_arr=array();
if($actualite[0]['zones_de_travail']){
                        foreach (json_decode($actualite[0]['zones_de_travail']) as $zon) {

                            // echo "SELECT ville FROM BD_EDICOM.`villes` where code =".$zon;
  if ($zon !='zones_de_travail'){
                             $zones_villes     = $connection->fetchAll("SELECT ville FROM BD_EDICOM.`villes` where code =".$zon);

                             $zon_arr[]=$zones_villes[0]['ville'];

}

         }
}




$livraison=$actualite[0]['livraison'];
$valider=$actualite[0]['valider'];
$zon_arr=$zon_arr;
$heure=$actualite[0]['heure'];
$prestations_arr=json_decode($actualite[0]['prestations']);
$produits_arr=json_decode($actualite[0]['produits']);
$marques_arr=json_decode($actualite[0]['marques']);
$services_plus_arr=json_decode($actualite[0]['services_plus']);
$paiement=$actualite[0]['paiement'];
$numero_urgence=$actualite[0]['numero_urgence'];
$site_web=$actualite[0]['site_web'];
   if ($existe_facebook){
                      
$facebook=$existe_facebook[0]['lien'];
                            }else{
$facebook="";
}


        if ($existe_whatsapp){

                   
$whatsapp=$existe_whatsapp[0]['lien'];

                        }else{

                                $whatsapp="";
                            }


              if ($existe_linkedin){

                            $linkedin=$existe_linkedin[0]['lien'];
}else{

$linkedin="";
                            }

                 if ($existe_instagram){
                            $instagram=$existe_instagram[0]['lien'];
                        }else{ $instagram="";}

$actes_arr=json_decode($actualite[0]['actes']);
$langues_arr=json_decode($actualite[0]['langues']);

         $b = array();
 
         $sets = array();
 
         $params = array(
             '_index' => 'telecontact42'
            
         );


                                          $doc = array(
                                 'presentation_actu'        => $livraison,   
                                 'etat_actu' => $valider,
                                 'zone_actu'        => $zon_arr,
                                 'horaires_actu' => $heure,
                                 'prests_actu'        => $prestations_arr,                                                             
                                 'produits_actu' => $produits_arr,
                                 'marques_actu' => $marques_arr,
                                 'service_plus_actu'        => $services_plus_arr,                                                              
                                 'moyens_paiement_actu' => $paiement,
                                 'numero_urgence_actu'        => $numero_urgence,                                                            
                                 'site_web_actu' => $site_web,
                                 'fb_actu' => $facebook,
                                 'wp_actu'        => $whatsapp,                                                                     
                                 'in_actu'        => $linkedin,                                                               
                                 'insta_actu' => $instagram,
                                 'actes_actu'        => $actes_arr,                                                         
                                 'langue_actu' => $langues_arr                                                             
                                 );
 $docs = array("doc"=> $doc);
    $b[]=json_encode($docs);

         $body =  join("\n", $b) . "\n";
             $header = array(
        "Content-Type: application/json" 
    );
         $conn = curl_init();
         $requestURL = 'http://edicomelastic1.odiso.net:9200/telecontact42/_update/MA'.$code_firme;
         curl_setopt($conn, CURLOPT_URL, $requestURL);
         curl_setopt($conn,CURLOPT_HTTPHEADER, $header);
         curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
         curl_setopt($conn, CURLOPT_FAILONERROR, FALSE);
         curl_setopt($conn, CURLOPT_CUSTOMREQUEST, strtoupper('POST'));
         curl_setopt($conn, CURLOPT_FORBID_REUSE, 0);
 
         if (is_array($body) && count($body)) {
             curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($body));
         } else {
             curl_setopt($conn, CURLOPT_POSTFIELDS, $body);
         }
         
         $response = curl_exec($conn);
print_r(   $response );

}

                               die('ici');

                if($request->isMethod('post')){




                    $livraison          = $request->get('livraison');
                    $zones_de_travail   = $request->get('zones_de_travail');
                    $zones_de_travail_arr =$zones_de_travail;

                    $zon_arr=array();
                        foreach ($zones_de_travail_arr as $zon) {

                            // echo "SELECT ville FROM BD_EDICOM.`villes` where code =".$zon;

                             $zones_villes     = $connection->fetchAll("SELECT ville FROM BD_EDICOM.`villes` where code =".$zon);

                             $zon_arr[]=$zones_villes[0]['ville'];



         }

                    $zones_de_travail   = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $zones_de_travail ) );
                    $zones_de_travail   = addslashes($zones_de_travail);


                    
                    $heure              = $request->get('heure');
                    $prestations        = $request->get('field_name_prestation');
                    $prestations_arr    = $prestations;

                    $prestations        = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $prestations ) );
                    $prestations        = addslashes($prestations);

                    $produits           = $request->get('field_name_produit');

                    $produits_arr       = $produits;

                    $produits           = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $produits ) );
                    $produits           = addslashes($produits);
                    
                    $marques            = $request->get('field_name_marque');
                    $marques_arr        = $marques;

                    $marques            = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $marques ) );
                    $marques            = addslashes($marques);
                    $paiement           = json_encode($request->get('paiement'));


                    $services_plus      = $request->get('field_name_service');
                    $services_plus_arr  = $services_plus;

                    $services_plus      = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $services_plus ) );
                    $services_plus      = addslashes($services_plus);
                    $numero_urgence     = $request->get('numero_urgence');
                    $site_web           = $request->get('site_web');
                    $facebook           = $request->get('facebook');
                    $instagram          = $request->get('instagram');
                    $linkedin           = $request->get('linkedin');
                    $whatsapp           = $request->get('whatsapp');
                    $actes              = $request->get('field_name_acte');
                    $actes_arr          = $actes;

                    $actes              = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $actes ) );
                    $actes              = addslashes($actes);
                    $langues            = $request->get('langues');
                    $langues_arr        = $langues;

     
                    $langues            = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $langues ) );
                    $langues            = addslashes($langues);
                    $id_user            = $request->get('id_user');
                    $startPause         = $request->get('startPause');
                    $endPause           = $request->get('endPause');
                    $valider            = $request->get('valider');
                    $pause_text=$startPause.'  '.$endPause ;

// start vidage sur moteur by fayssal


 $datetime = new \DateTime('now');


$datetime=$datetime->format( 'd-m-Y H:i:s' );


         $b = array();
 
         $sets = array();
 
         $params = array(
             '_index' => 'telecontact42'
            
         );


                                          $doc = array(
                                 'date'           => $datetime,
                                 'presentation_actu'        => $livraison,   
                                 'etat_actu' => $valider,
                                 'zone_actu'        => $zon_arr,
                                 'horaires_actu' => $heure,
                                 'prests_actu'        => $prestations_arr,                                                             
                                 'produits_actu' => $produits_arr,
                                 'marques_actu' => $marques_arr,
                                 'service_plus_actu'        => $services_plus_arr,                                                              
                                 'moyens_paiement_actu' => $paiement,
                                 'numero_urgence_actu'        => $numero_urgence,                                                            
                                 'site_web_actu' => $site_web,
                                 'fb_actu' => $facebook,
                                 'wp_actu'        => $whatsapp,                                                                     
                                 'in_actu'        => $linkedin,                                                               
                                 'insta_actu' => $instagram,
                                 'actes_actu'        => $actes_arr,                                                         
                                 'langue_actu' => $langues_arr                                                             
                                 );

 $docs = array("doc"=> $doc);
    $b[]=json_encode($docs);

         $body =  join("\n", $b) . "\n";
             $header = array(
        "Content-Type: application/json" 
    );
         $conn = curl_init();
         $requestURL = 'http://edicomelastic1.odiso.net:9200/telecontact42/_update/MA'.$code_firme;
         curl_setopt($conn, CURLOPT_URL, $requestURL);
         curl_setopt($conn,CURLOPT_HTTPHEADER, $header);
         curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
         curl_setopt($conn, CURLOPT_FAILONERROR, FALSE);
         curl_setopt($conn, CURLOPT_CUSTOMREQUEST, strtoupper('POST'));
         curl_setopt($conn, CURLOPT_FORBID_REUSE, 0);
 
         if (is_array($body) && count($body)) {
             curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($body));
         } else {
             curl_setopt($conn, CURLOPT_POSTFIELDS, $body);
         }
         
         $response = curl_exec($conn);

        // end vidage sur moteur 

                    if(in_array($code_fichier, array('H53', 'H63', 'H55', 'H65', 'H73', 'H54', 'H56', 'H50', 'H78'))){

                        $count_image = $request->get('count_image');
                        if (!is_null($count_image)) {

                            $targetDir = $this->get('kernel')->getRootDir() . '/../web/uploads/images_actualites/'; 
                            $allowTypes = array('jpg','png','jpeg','gif','pdf');
                            $display_image = $display_certif = $display_marque = '';

                            $start_of_loop = $count_galerie_actu + 1;
                            for($i=$start_of_loop; $i<=$count_image; $i++){
                                $image_name=$request->get('field_name_text_image_'.$i); 
                                $fileName = basename($_FILES["field_name_image_".$i]["name"]);
                                
                                $targetFilePath = $targetDir . $fileName;
                                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                                if(move_uploaded_file($_FILES["field_name_image_".$i]["tmp_name"], $targetFilePath)){
                                    $db = $connection->prepare("INSERT INTO galerie_actu ( `image`,`titre_image`, `code_firme`) values ('".$fileName."' , '".$image_name."' , '".$code_firme."') ");
                                    $db->execute();
                                }
                            }
                        }

                    }else{

                        $count_image = $request->get('count_image');
                        if (!is_null($count_image)) {

                            $targetDir = $this->get('kernel')->getRootDir() . '/../web/uploads/images_actualites/'; 
                            $allowTypes = array('jpg','png','jpeg','gif','pdf');
                            $display_image = $display_certif = $display_marque = '';

                            $start_of_loop = $count_galerie_actu + 1;
                            for($i=$start_of_loop; $i<=$count_image; $i++){
                                $image_name=$request->get('field_name_text_images_'.$i); 
                                $fileName = basename($_FILES["field_name_images_".$i]["name"]);
                                
                                $targetFilePath = $targetDir . $fileName;
                                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                                if(move_uploaded_file($_FILES["field_name_images_".$i]["tmp_name"], $targetFilePath)){
                                    $db = $connection->prepare("INSERT INTO galerie_actu ( `image`,`titre_image`, `code_firme`) values ('".$fileName."' , '".$image_name."' , '".$code_firme."') ");
                                    $db->execute();
                                }
                            }
                        }

                    }
                   

                    if(!empty($actualite)){

                       
                        $update = $connection->executeQuery("UPDATE `actualite` SET `valider`='".$valider."', `livraison`='".$livraison."',`zones_de_travail`='".$zones_de_travail."',`heure`='".$heure."',`prestations`='".$prestations."',`produits`='".$produits."',`marques`='".$marques."' , `paiement` = '".$paiement."', `services_plus` = '". $services_plus ."', `numero_urgence` = '". $numero_urgence ."', `site_web` = '". $site_web ."', `actes` = '". $actes ."', `langues` = '". $langues ."', `startPause` = '". $startPause ."', `endPause` = '". $endPause ."', `user` = '". $id_user ."' where `code_firme` = '". $code_firme ."'
                            ");

                    }else{

                        $insert = $connection->executeQuery("INSERT INTO `actualite` (`livraison`, `zones_de_travail`, `heure`, `prestations`,`produits`,`marques`,`paiement`,`services_plus`, `numero_urgence`, `site_web`, `actes`, `langues`, `code_firme`, `user`, `startPause`, `endPause`, `valider`) VALUES('".$livraison."','".$zones_de_travail."','".$heure."','".$prestations."','".$produits."','".$marques."','".$paiement."','".$services_plus."', '". $numero_urgence ."','".$site_web."','".$actes."','".$langues."', '". $code_firme ."', '". $id_user ."', '". $startPause ."', '". $endPause ."', '".$valider."')");
                      
                    }


                    if(empty($existe_facebook) && !empty($facebook)){
                        $insert_facebook = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$facebook."', 'facebook')");
                    }else{
                        $update_facebook = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$facebook."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'facebook' ");
                    }
                    if(empty($existe_instagram) && !empty($instagram)){
                        $insert_instagram = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$instagram."', 'instagram')");
                    }else{
                        $update_instagram = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$instagram."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'instagram' ");
                    }
                    if(empty($existe_linkedin) && !empty($linkedin)){
                        $insert_linkedin = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$linkedin."', 'linkedin')");
                    }else{
                        $update_linkedin = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$linkedin."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'linkedin' ");
                    }
                    if(empty($existe_whatsapp) && !empty($whatsapp)){
                        $insert_whatsapp = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$whatsapp."', 'whatsapp')");
                    }else{
                        $update_whatsapp = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$whatsapp."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'whatsapp' ");
                    }


                                                $code_fichier = $connection2->fetchAll("SELECT `code_fichier` FROM `firmes` WHERE `code_firme` = CONCAT('MA','$code_firme')");
                                                $code_fichier = $code_fichier[0]['code_fichier'];

                                                $villes = $connection->fetchAll("SELECT * FROM BD_EDICOM.`villes`");   

                                                $actualite = $connection->fetchAll("SELECT * FROM `actualite` WHERE `code_firme` = '$code_firme'");

                                                if(!empty($actualite)){
                                                    $count_presentation_car = strlen($actualite[0]['livraison']);
                                                }else{
                                                    $count_presentation_car = 0;
                                                }

                                                $galerie_actu = $connection->fetchAll("SELECT * FROM `galerie_actu` WHERE `code_firme` = '$code_firme'");

                                                $count_galerie_actu = count($galerie_actu);


                                                $actualite = array_map(function($c){

                                                    $c['conditions'] = utf8_encode($c['conditions']);
                                                    $c['livraison'] = utf8_encode($c['livraison']);
                                                    $c['savoir'] = utf8_encode($c['savoir']);

                                                    return $c;
                                                }, $actualite);


                                                if(!empty($actualite[0]['prestations'])){
                                                    $prestation = explode(',', $actualite[0]['prestations']);
                                                    $prestation = str_replace('"','', $prestation);
                                                    $prestation = str_replace('[','', $prestation);
                                                    $prestation = str_replace(']','', $prestation);
                                                }else{
                                                    $prestation = '';
                                                }


                                                if(!empty($actualite[0]['produits'])){
                                                    $produit = explode(',', $actualite[0]['produits']);
                                                    $produit = str_replace('"','', $produit);
                                                    $produit = str_replace('[','', $produit);
                                                    $produit = str_replace(']','', $produit);
                                                }else{
                                                    $produit = '';
                                                }


                                                if(!empty($actualite[0]['marques'])){
                                                    $marque = explode(',', $actualite[0]['marques']);
                                                    $marque = str_replace('"','', $marque);
                                                    $marque = str_replace('[','', $marque);
                                                    $marque = str_replace(']','', $marque);
                                                }else{
                                                    $marque = '';
                                                }


                                                if(!empty($actualite[0]['services_plus'])){
                                                    $service = explode(',', $actualite[0]['services_plus']);
                                                    $service = str_replace('"','', $service);
                                                    $service = str_replace('[','', $service);
                                                    $service = str_replace(']','', $service);
                                                }else{
                                                    $service = '';
                                                }

                                                if(!empty($actualite[0]['actes'])){
                                                    $acte = explode(',', $actualite[0]['actes']);
                                                    $acte = str_replace('"','', $acte);
                                                    $acte = str_replace('[','', $acte);
                                                    $acte = str_replace(']','', $acte);
                                                }else{
                                                    $acte = '';
                                                }

                                                if(!empty($actualite[0]['zones_de_travail'])){
                                                    $zones_de_travail = explode(',', $actualite[0]['zones_de_travail']);
                                                    $zones_de_travail = str_replace('"','', $zones_de_travail);
                                                    $zones_de_travail = str_replace('[','', $zones_de_travail);
                                                    $zones_de_travail = str_replace(']','', $zones_de_travail);
                                                }else{
                                                    $zones_de_travail = '';
                                                }

                                                $existe_facebook     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'facebook'");
                                                $existe_instagram    = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'instagram'");
                                                $existe_linkedin     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'linkedin'");
                                                $existe_whatsapp     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'whatsapp'");



                    return $this->render('EcommerceBundle:Administration:statistics/actualites_societe.html.twig', array('actualite' => $actualite, 'code_firme' => $code_firme, 'prestation' => $prestation, 'produit' => $produit, 'marque' => $marque, 'villes' => $villes, 'galerie_actu' => $galerie_actu, 'service' => $service, 'acte' => $acte, 'code_fichier' => $code_fichier, 'zones_de_travail' => $zones_de_travail, 'existe_facebook' => $existe_facebook, 'existe_instagram' => $existe_instagram, 'existe_linkedin' => $existe_linkedin, 'existe_whatsapp' => $existe_whatsapp, 'count_galerie_actu' => $count_galerie_actu, 'count_presentation_car' => $count_presentation_car));
                    
                }


        return $this->render('EcommerceBundle:Administration:statistics/actualites_societe.html.twig', array('actualite' => $actualite, 'code_firme' => $code_firme, 'prestation' => $prestation, 'produit' => $produit, 'marque' => $marque, 'villes' => $villes, 'galerie_actu' => $galerie_actu, 'service' => $service, 'acte' => $acte, 'code_fichier' => $code_fichier, 'zones_de_travail' => $zones_de_travail, 'existe_facebook' => $existe_facebook, 'existe_instagram' => $existe_instagram, 'existe_linkedin' => $existe_linkedin, 'existe_whatsapp' => $existe_whatsapp, 'count_galerie_actu' => $count_galerie_actu, 'count_presentation_car' => $count_presentation_car));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }
    public function actualiteAction($code_firme,  Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


                $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname='localhost';
                $dbname1='telecontact_BackOffice_Site';
                $dbname2='BD_EDICOM';
                $username='pyxicom';
                $password='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname1;charset=utf8",$username,$password)));
                $connection2 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname2;charset=utf8",$username,$password)));
                //$connection->mysql_set_charset("utf8");

                $code_fichier = $connection2->fetchAll("SELECT `code_fichier` FROM `firmes` WHERE `code_firme` = CONCAT('MA','$code_firme')");
                $code_fichier = $code_fichier[0]['code_fichier'];

                $villes = $connection->fetchAll("SELECT * FROM BD_EDICOM.`villes` order By ville");   

                $actualite = $connection->fetchAll("SELECT * FROM `actualite` WHERE `code_firme` = '$code_firme'");

                if(!empty($actualite)){
                    $count_presentation_car = strlen($actualite[0]['livraison']);
                }else{
                    $count_presentation_car = 0;
                }
                

                $galerie_actu = $connection->fetchAll("SELECT * FROM `galerie_actu` WHERE `code_firme` = '$code_firme'");

                $count_galerie_actu = count($galerie_actu);


                $actualite = array_map(function($c){

                    $c['conditions'] = utf8_encode($c['conditions']);
                    $c['livraison'] = utf8_encode($c['livraison']);
                    $c['savoir'] = utf8_encode($c['savoir']);

                    return $c;
                }, $actualite);

if (!empty($actualite)){
                if(!(count($actualite[0]['prestations']) === 0) ){

               

                    $prestation = explode(',', $actualite[0]['prestations']);
                    $prestation = str_replace('"','', $prestation);
                    $prestation = str_replace('[','', $prestation);
                    $prestation = str_replace(']','', $prestation);

              
                }else{
                    $prestation = '';
                }
}else{

$prestation = '';
    
}

                if(!empty($actualite[0]['produits'])){
                    $produit = explode(',', $actualite[0]['produits']);
                    $produit = str_replace('"','', $produit);
                    $produit = str_replace('[','', $produit);
                    $produit = str_replace(']','', $produit);
                }else{
                    $produit = '';
                }


                if(!empty($actualite[0]['marques'])){
                    $marque = explode(',', $actualite[0]['marques']);
                    $marque = str_replace('"','', $marque);
                    $marque = str_replace('[','', $marque);
                    $marque = str_replace(']','', $marque);
                }else{
                    $marque = '';
                }


                if(!empty($actualite[0]['services_plus'])){
                    $service = explode(',', $actualite[0]['services_plus']);
                    $service = str_replace('"','', $service);
                    $service = str_replace('[','', $service);
                    $service = str_replace(']','', $service);
                }else{
                    $service = '';
                }

                if(!empty($actualite[0]['actes'])){
                    $acte = explode(',', $actualite[0]['actes']);
                    $acte = str_replace('"','', $acte);
                    $acte = str_replace('[','', $acte);
                    $acte = str_replace(']','', $acte);
                }else{
                    $acte = '';
                }

                if(!empty($actualite[0]['zones_de_travail'])){
                    $zones_de_travail = explode(',', $actualite[0]['zones_de_travail']);
                    $zones_de_travail = str_replace('"','', $zones_de_travail);
                    $zones_de_travail = str_replace('[','', $zones_de_travail);
                    $zones_de_travail = str_replace(']','', $zones_de_travail);
                }else{
                    $zones_de_travail = '';
                }

                /*var_dump($produit);die('aa');*/

                $existe_facebook     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'facebook'");
                $existe_instagram    = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'instagram'");
                $existe_linkedin     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'linkedin'");
                $existe_whatsapp     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'whatsapp'");

               
                               

                if($request->isMethod('post')){




                    $livraison          = $request->get('livraison');
                    $zones_de_travail   = $request->get('zones_de_travail');
                    $zones_de_travail_arr =$zones_de_travail;

                    $zon_arr=array();

            

if(!empty($zones_de_travail_arr)){
                        foreach ($zones_de_travail_arr as $zon) {

                            // echo "SELECT ville FROM BD_EDICOM.`villes` where code =".$zon;
                                                if ($zon !='zones_de_travail'){

                             $zones_villes     = $connection->fetchAll("SELECT ville FROM BD_EDICOM.`villes` where code =".$zon);

                             $zon_arr[]=$zones_villes[0]['ville'];
}


         }}

/*                        print_r($zon_arr);

                        die();*/


                    $zones_de_travail   = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $zones_de_travail ) );
                    $zones_de_travail   = addslashes($zones_de_travail);


                    
                    $heure              = $request->get('heure');
                    $prestations        = $request->get('field_name_prestation');
                    $prestations_arr    = $prestations;

                    $prestations        = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $prestations ) );
                    $prestations        = addslashes($prestations);

                    $produits           = $request->get('field_name_produit');

                    $produits_arr       = $produits;

                    $produits           = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $produits ) );
                    $produits           = addslashes($produits);
                    
                    $marques            = $request->get('field_name_marque');
                    $marques_arr        = $marques;

                    $marques            = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $marques ) );
                    $marques            = addslashes($marques);
                    $paiement           = json_encode($request->get('paiement'));


                    $services_plus      = $request->get('field_name_service');
                    $services_plus_arr  = $services_plus;

                    $services_plus      = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $services_plus ) );
                    $services_plus      = addslashes($services_plus);
                    $numero_urgence     = $request->get('numero_urgence');
                    $site_web           = $request->get('site_web');
                    $facebook           = $request->get('facebook');
                    $instagram          = $request->get('instagram');
                    $linkedin           = $request->get('linkedin');
                    $whatsapp           = $request->get('whatsapp');
                    $actes              = $request->get('field_name_acte');
                    $actes_arr          = $actes;

                    $actes              = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $actes ) );
                    $actes              = addslashes($actes);
                    $langues            = $request->get('langues');
                    $langues_arr        = $langues;

     
                    $langues            = preg_replace( "/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode( $langues ) );
                    $langues            = addslashes($langues);
                    $id_user            = $request->get('id_user');
                    $startPause         = $request->get('startPause');
                    $endPause           = $request->get('endPause');
                    $valider            = $request->get('valider');
                    $pause_text=$startPause.'  '.$endPause ;

// start vidage sur moteur by fayssal


 $datetime = new \DateTime('now');


$datetime=$datetime->format( 'd-m-Y H:i:s' );


         $b = array();
 
         $sets = array();
 
         $params = array(
             '_index' => 'telecontact42'
            
         );
/*print_r($zon_arr);

                        die('ici');*/

                                          $doc = array(
                                 'date'           => $datetime,
                                 'presentation_actu'        => $livraison,   
                                 'etat_actu' => $valider,
                                 'zone_actu'        => $zon_arr,
                                 'horaires_actu' => $heure,
                                 'prests_actu'        => $prestations_arr,                                                             
                                 'produits_actu' => $produits_arr,
                                 'marques_actu' => $marques_arr,
                                 'service_plus_actu'        => $services_plus_arr,                                                              
                                 'moyens_paiement_actu' => $paiement,
                                 'numero_urgence_actu'        => $numero_urgence,                                                            
                                 'site_web_actu' => $site_web,
                                 'fb_actu' => $facebook,
                                 'wp_actu'        => $whatsapp,                                                                     
                                 'in_actu'        => $linkedin,                                                               
                                 'insta_actu' => $instagram,
                                 'actes_actu'        => $actes_arr,                                                         
                                 'langue_actu' => $langues_arr                                                             
                                 );

 $docs = array("doc"=> $doc);
    $b[]=json_encode($docs);

         $body =  join("\n", $b) . "\n";
             $header = array(
        "Content-Type: application/json" 
    );
         $conn = curl_init();
         $requestURL = 'http://edicomelastic1.odiso.net:9200/telecontact42/_update/MA'.$code_firme;
         curl_setopt($conn, CURLOPT_URL, $requestURL);
         curl_setopt($conn,CURLOPT_HTTPHEADER, $header);
         curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
         curl_setopt($conn, CURLOPT_FAILONERROR, FALSE);
         curl_setopt($conn, CURLOPT_CUSTOMREQUEST, strtoupper('POST'));
         curl_setopt($conn, CURLOPT_FORBID_REUSE, 0);
 
         if (is_array($body) && count($body)) {
             curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($body));
         } else {
             curl_setopt($conn, CURLOPT_POSTFIELDS, $body);
         }
         
         $response = curl_exec($conn);

        // end vidage sur moteur 

                    if(in_array($code_fichier, array('H53', 'H63', 'H55', 'H65', 'H73', 'H54', 'H56', 'H50', 'H78'))){

                        $count_image = $request->get('count_image');
                        if (!is_null($count_image)) {

                            $targetDir = $this->get('kernel')->getRootDir() . '/../web/uploads/images_actualites/'; 
                            $allowTypes = array('jpg','png','jpeg','gif','pdf');
                            $display_image = $display_certif = $display_marque = '';

                            $start_of_loop = $count_galerie_actu + 1;
                            for($i=$start_of_loop; $i<=$count_image; $i++){
                                $image_name=$request->get('field_name_text_image_'.$i); 
                                $fileName = basename($_FILES["field_name_image_".$i]["name"]);
                                
                                $targetFilePath = $targetDir . $fileName;
                                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                                if(move_uploaded_file($_FILES["field_name_image_".$i]["tmp_name"], $targetFilePath)){
                                    $db = $connection->prepare("INSERT INTO galerie_actu ( `image`,`titre_image`, `code_firme`) values ('".$fileName."' , '".$image_name."' , '".$code_firme."') ");
                                    $db->execute();
                                }
                            }
                        }

                    }else{

                        $count_image = $request->get('count_image');
                        if (!is_null($count_image)) {

                            $targetDir = $this->get('kernel')->getRootDir() . '/../web/uploads/images_actualites/'; 
                            $allowTypes = array('jpg','png','jpeg','gif','pdf');
                            $display_image = $display_certif = $display_marque = '';

                            $start_of_loop = $count_galerie_actu + 1;
                            for($i=$start_of_loop; $i<=$count_image; $i++){
                                $image_name=$request->get('field_name_text_images_'.$i); 
                                $fileName = basename($_FILES["field_name_images_".$i]["name"]);
                                
                                $targetFilePath = $targetDir . $fileName;
                                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                                if(move_uploaded_file($_FILES["field_name_images_".$i]["tmp_name"], $targetFilePath)){
                                    $db = $connection->prepare("INSERT INTO galerie_actu ( `image`,`titre_image`, `code_firme`) values ('".$fileName."' , '".$image_name."' , '".$code_firme."') ");
                                    $db->execute();
                                }
                            }
                        }

                    }
                   

                    if(!empty($actualite)){

                       
                        $update = $connection->executeQuery("UPDATE `actualite` SET `valider`='".$valider."', `livraison`='".$livraison."',`zones_de_travail`='".$zones_de_travail."',`heure`='".$heure."',`prestations`='".$prestations."',`produits`='".$produits."',`marques`='".$marques."' , `paiement` = '".$paiement."', `services_plus` = '". $services_plus ."', `numero_urgence` = '". $numero_urgence ."', `site_web` = '". $site_web ."', `actes` = '". $actes ."', `langues` = '". $langues ."', `startPause` = '". $startPause ."', `endPause` = '". $endPause ."', `user` = '". $id_user ."' where `code_firme` = '". $code_firme ."'
                            ");

                    }else{

                        $insert = $connection->executeQuery("INSERT INTO `actualite` (`livraison`, `zones_de_travail`, `heure`, `prestations`,`produits`,`marques`,`paiement`,`services_plus`, `numero_urgence`, `site_web`, `actes`, `langues`, `code_firme`, `user`, `startPause`, `endPause`, `valider`) VALUES('".$livraison."','".$zones_de_travail."','".$heure."','".$prestations."','".$produits."','".$marques."','".$paiement."','".$services_plus."', '". $numero_urgence ."','".$site_web."','".$actes."','".$langues."', '". $code_firme ."', '". $id_user ."', '". $startPause ."', '". $endPause ."', '".$valider."')");
                      
                    }


                    if(empty($existe_facebook) && !empty($facebook)){
                        $insert_facebook = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$facebook."', 'facebook')");
                    }else{
                        $update_facebook = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$facebook."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'facebook' ");
                    }
                    if(empty($existe_instagram) && !empty($instagram)){
                        $insert_instagram = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$instagram."', 'instagram')");
                    }else{
                        $update_instagram = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$instagram."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'instagram' ");
                    }
                    if(empty($existe_linkedin) && !empty($linkedin)){
                        $insert_linkedin = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$linkedin."', 'linkedin')");
                    }else{
                        $update_linkedin = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$linkedin."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'linkedin' ");
                    }
                    if(empty($existe_whatsapp) && !empty($whatsapp)){
                        $insert_whatsapp = $connection->executeQuery("INSERT INTO `reseaux_sociaux__actu` (`code_firme`, `lien`, `type`) VALUES('".$code_firme."', '".$whatsapp."', 'whatsapp')");
                    }else{
                        $update_whatsapp = $connection->executeQuery("UPDATE `reseaux_sociaux__actu` SET `lien`='".$whatsapp."' WHERE `code_firme` = '". $code_firme ."' AND `type` = 'whatsapp' ");
                    }


                                                $code_fichier = $connection2->fetchAll("SELECT `code_fichier` FROM `firmes` WHERE `code_firme` = CONCAT('MA','$code_firme')");
                                                $code_fichier = $code_fichier[0]['code_fichier'];

                                                $villes = $connection->fetchAll("SELECT * FROM BD_EDICOM.`villes`");   

                                                $actualite = $connection->fetchAll("SELECT * FROM `actualite` WHERE `code_firme` = '$code_firme'");

                                                if(!empty($actualite)){
                                                    $count_presentation_car = strlen($actualite[0]['livraison']);
                                                }else{
                                                    $count_presentation_car = 0;
                                                }

                                                $galerie_actu = $connection->fetchAll("SELECT * FROM `galerie_actu` WHERE `code_firme` = '$code_firme'");

                                                $count_galerie_actu = count($galerie_actu);


                                                $actualite = array_map(function($c){

                                                    $c['conditions'] = utf8_encode($c['conditions']);
                                                    $c['livraison'] = utf8_encode($c['livraison']);
                                                    $c['savoir'] = utf8_encode($c['savoir']);

                                                    return $c;
                                                }, $actualite);


                                                if(!empty($actualite[0]['prestations'])){
                                                    $prestation = explode(',', $actualite[0]['prestations']);
                                                    $prestation = str_replace('"','', $prestation);
                                                    $prestation = str_replace('[','', $prestation);
                                                    $prestation = str_replace(']','', $prestation);
                                                }else{
                                                    $prestation = '';
                                                }


                                                if(!empty($actualite[0]['produits'])){
                                                    $produit = explode(',', $actualite[0]['produits']);
                                                    $produit = str_replace('"','', $produit);
                                                    $produit = str_replace('[','', $produit);
                                                    $produit = str_replace(']','', $produit);
                                                }else{
                                                    $produit = '';
                                                }


                                                if(!empty($actualite[0]['marques'])){
                                                    $marque = explode(',', $actualite[0]['marques']);
                                                    $marque = str_replace('"','', $marque);
                                                    $marque = str_replace('[','', $marque);
                                                    $marque = str_replace(']','', $marque);
                                                }else{
                                                    $marque = '';
                                                }


                                                if(!empty($actualite[0]['services_plus'])){
                                                    $service = explode(',', $actualite[0]['services_plus']);
                                                    $service = str_replace('"','', $service);
                                                    $service = str_replace('[','', $service);
                                                    $service = str_replace(']','', $service);
                                                }else{
                                                    $service = '';
                                                }

                                                if(!empty($actualite[0]['actes'])){
                                                    $acte = explode(',', $actualite[0]['actes']);
                                                    $acte = str_replace('"','', $acte);
                                                    $acte = str_replace('[','', $acte);
                                                    $acte = str_replace(']','', $acte);
                                                }else{
                                                    $acte = '';
                                                }

                                                if(!empty($actualite[0]['zones_de_travail'])){
                                                    $zones_de_travail = explode(',', $actualite[0]['zones_de_travail']);
                                                    $zones_de_travail = str_replace('"','', $zones_de_travail);
                                                    $zones_de_travail = str_replace('[','', $zones_de_travail);
                                                    $zones_de_travail = str_replace(']','', $zones_de_travail);
                                                }else{
                                                    $zones_de_travail = '';
                                                }

                                                $existe_facebook     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'facebook'");
                                                $existe_instagram    = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'instagram'");
                                                $existe_linkedin     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'linkedin'");
                                                $existe_whatsapp     = $connection->fetchAll("SELECT * FROM `reseaux_sociaux__actu` WHERE `code_firme` = '". $code_firme ."' AND `type` = 'whatsapp'");



                    return $this->render('EcommerceBundle:Administration:statistics/actualites_societe.html.twig', array('actualite' => $actualite, 'code_firme' => $code_firme, 'prestation' => $prestation, 'produit' => $produit, 'marque' => $marque, 'villes' => $villes, 'galerie_actu' => $galerie_actu, 'service' => $service, 'acte' => $acte, 'code_fichier' => $code_fichier, 'zones_de_travail' => $zones_de_travail, 'existe_facebook' => $existe_facebook, 'existe_instagram' => $existe_instagram, 'existe_linkedin' => $existe_linkedin, 'existe_whatsapp' => $existe_whatsapp, 'count_galerie_actu' => $count_galerie_actu, 'count_presentation_car' => $count_presentation_car));
                    
                }


        return $this->render('EcommerceBundle:Administration:statistics/actualites_societe.html.twig', array('actualite' => $actualite, 'code_firme' => $code_firme, 'prestation' => $prestation, 'produit' => $produit, 'marque' => $marque, 'villes' => $villes, 'galerie_actu' => $galerie_actu, 'service' => $service, 'acte' => $acte, 'code_fichier' => $code_fichier, 'zones_de_travail' => $zones_de_travail, 'existe_facebook' => $existe_facebook, 'existe_instagram' => $existe_instagram, 'existe_linkedin' => $existe_linkedin, 'existe_whatsapp' => $existe_whatsapp, 'count_galerie_actu' => $count_galerie_actu, 'count_presentation_car' => $count_presentation_car));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

     }


     public function delete_image_actuAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $response = array();

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname1='telecontact_BackOffice_Site';
            $dbname2='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname1;charset=utf8",$username,$password)));

            $id = $request->get('id');

            $query = $connection->executeQuery("DELETE FROM galerie_actu WHERE id='".$id."' ");

            return new JsonResponse();

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }

     }

    



     public function demandeDevisTelecontactAction(Request $request) {

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];

        /*var_dump($ses_id_user);

        die;*/

        if(!empty($ses_id_user)){

            $response = array();

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname1='telecontact_BackOffice_Site';
            $dbname2='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname1;charset=utf8",$username,$password , array(PDO::ATTR_EMULATE_PREPARES => FALSE))));

            if ($request->isXmlHttpRequest() && $request->isMethod("POST") && $request->request->get("isDataTable") == "true") {

                $draw = $request->request->get("draw");

                $columns = $request->request->get("columns");

                $order = $request->request->get("order");

                $start = intval($request->request->get("start"));

                $length = intval($request->request->get("length"));

                $search = $request->request->get("search");

                $orderQuery = "";

                $query = "SELECT countReponse , `publication`.`id` , `publication`.`titre` , `categorie_pub`.`name` , `publication`.`date_creation` , `publication`.`etat` FROM `publication` LEFT JOIN `categorie_pub` ON `categorie_pub`.`id` = `publication`.`id_cat` LEFT JOIN (SELECT COUNT(`reponse_pub_user`.`id`) AS countReponse , id_pub FROM `reponse_pub_user` GROUP BY `id_pub`) as countR ON countR.`id_pub` = `publication`.`id` WHERE `publication`.`id_user` = ? GROUP BY `publication`.`id`";

                /* Start Sort Section Order By */
                for($i = 0 ; $i < count($order) ; $i++) {
                    $orderBy = array();
                    $columnIndex = intval($order[$i]['column']);
                    $findColumn = $columns[$columnIndex];

                    $columnIdx = array_search( $findColumn['data'], $findColumn );
                    $column = $findColumn[ $columnIdx ];

                    if(!empty($column)) {
                        if ($findColumn["orderable"] == true) {
                            $direction = $order[$i]['dir'] == 'asc' ? 'ASC' : 'DESC';
                            $orderBy[] = '`' . $column . '` ' . $direction;
                        }
                    }
                }

                if(!empty($orderBy)) {
                  $orderQuery = " ORDER BY " . implode(' , ' , $orderBy);
                }

                $query .= " " . $orderQuery . " LIMIT ? , ?";

                $params = array();

                $params[] = $ses_id_user;
                $params[] = $start;
                $params[] = $length;

                /*echo $query;

                die("here");*/

                $stmt = $connection->prepare($query);

                if ($stmt->execute($params)) {

                    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    //var_dump($publications);

                    $count_query = "SELECT COUNT(*) FROM (SELECT countReponse , `publication`.`id` , `publication`.`titre` , `categorie_pub`.`name` , `publication`.`date_creation` , `publication`.`etat` FROM `publication` LEFT JOIN `categorie_pub` ON `categorie_pub`.`id` = `publication`.`id_cat` LEFT JOIN (SELECT COUNT(`reponse_pub_user`.`id`) AS countReponse , id_pub FROM `reponse_pub_user` GROUP BY `id_pub`) as countR ON countR.`id_pub` = `publication`.`id` WHERE `publication`.`id_user` = ? GROUP BY `publication`.`id`) AS count";

                    $stmt_count = $connection->prepare($count_query);

                    if ($stmt_count->execute(array())) {



                        $response['recordsTotal'] = intval($stmt_count->fetch(PDO::FETCH_COLUMN));

                    }

                    $response['recordsFiltered'] = intval(count($stmt->fetchAll(PDO::FETCH_ASSOC)));

                    $response['draw'] = isset($draw) ? intval($draw) : 0;

                    $response['data'] = $publications;

                }

                return new JsonResponse($response);
            }

            return $this->render("EcommerceBundle:Default:demande_devis_telecontact/demande_devis.html.twig" , $response);

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }

     }

    public function detailsDevisTelecontactAction($id , Request $request) {

        $response = array();
        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];
        
        if(!empty($ses_id_user)){
            $response = array();


            $response["data_user"] = $ses;

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname1='telecontact_BackOffice_Site';
            $dbname2='BD_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname1;charset=utf8",$username,$password , array(PDO::ATTR_EMULATE_PREPARES => FALSE))));


            $stmt_publication = $connection->prepare("SELECT * FROM `publication` AS pub WHERE pub.`id` = ?");

            if ($stmt_publication->execute(array($id))) {

                $publication = $stmt_publication->fetch(PDO::FETCH_ASSOC);

                $response['publication'] = $publication;

                $stmt_response = $connection->prepare("SELECT * FROM `reponse_pub_user` as res WHERE res.`id_pub` = ? ORDER BY res.`id` DESC");

                $response['responses'] = array();

                if ($stmt_response->execute(array($publication['id']))) {
                    
                    $response['responses'] = $stmt_response->fetchAll(PDO::FETCH_ASSOC);

                } 

            } else {

                return $this->redirect($this->generateUrl('demandeDevisTelecontact'));

            }

            return $this->render("EcommerceBundle:Default:demande_devis_telecontact/details_demande_devis.html.twig", $response);
        
        } 

        return $this->redirect($this->generateUrl('connection'));

    }

    public function backModifierDevisAction($id , Request $request) {

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');

        $ses_id_user  = $ses[0]['id_user'];

        if(!empty($ses_id_user)){

            $response = array();

            $response["data_user"] = $ses;

            $con = $this->getDoctrine()->getConnection();

            $stmt_fetch_publication = $con->prepare("SELECT cat.`id` AS id_cat , cat.`name` , p.`id` AS id_pub , v.`id` AS id_ville , v.`ville` ,  p.`titre` , p.`mot_cle1` , p.`mot_cle2` , p.`mot_cle3` , p.`description` , p.`plus_details` FROM telecontact_BackOffice_Site.`publication` AS p
            LEFT JOIN telecontact_BackOffice_Site.`MOBILE_ville` AS v ON v.`id` = p.`id_ville`
            LEFT JOIN telecontact_BackOffice_Site.`categorie_pub` AS cat ON cat.`id` = p.`id_cat` 
            WHERE p.`id` = ?");

            if ($stmt_fetch_publication->execute(array($id))) {

                $response["publication"] = $stmt_fetch_publication->fetch(PDO::FETCH_ASSOC);

            }

            $categories = $con->query("SELECT `id`, `name` FROM telecontact_BackOffice_Site.`categorie_pub` ORDER BY `name` ASC");

            $response["categories"] = $categories;

            $villes = $con->query("SELECT `id` , `ville` FROM telecontact_BackOffice_Site.`MOBILE_ville` ORDER BY `ville` ASC")->fetchAll(PDO::FETCH_ASSOC);

            $response["villes"] = $villes;

            if ($request->isXmlHttpRequest() && $request->isMethod("POST")) {

                if ($request->request->get("act") == "publicationUpdate") {

                    $titre = $request->request->get("titre");
                    $motCle1 = $request->request->get("mot-cle1");
                    $motCle2 = $request->request->get("mot-cle2");
                    $motCle3 = $request->request->get("mot-cle3");
                    $idCat = $request->request->get("categorie");
                    $plusDetails = $request->request->get("plusDetails");
                    $description = $request->request->get("description");
                    $idVille = $request->request->get("ville");

                    unset($response["publication"]);
                    unset($response["categories"]);
                    unset($response["villes"]);

                    $response["isUpdated"] = false;

                    $stmt_update_publication = $con->prepare("UPDATE telecontact_BackOffice_Site.`publication` SET `titre` = ? , `mot_cle1` = ? , `mot_cle2` = ? , `mot_cle3` = ? , `description` = ? , `plus_details` = ? , `id_cat` = ? , `id_ville` = ? WHERE `id` = ?");

                    if ($stmt_update_publication->execute(array($titre , $motCle1 , $motCle2 , $motCle3 , $description , $plusDetails , $idCat , $idVille ,  $id))) {

                        $response["isUpdated"] = true;

                    }

                    return new JsonResponse($response);
 
                }

            }

            return $this->render("EcommerceBundle:Default:demande_devis_telecontact/back_modifier_devis.html.twig" , $response);    

        } 

        return $this->redirect($this->generateUrl('connection'));

    }



}