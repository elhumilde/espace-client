<?php

namespace Ecommerce\EcommerceBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Liuggio\ExcelBundle\LiuggioExcelBundle;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Ecommerce\EcommerceBundle\Entity\Statistics;
use Ecommerce\EcommerceBundle\Entity\StatisticsGron;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Utilisateurs Espace Client controller.
 *
 */
class UtilisateursEspaceClientController extends Controller
{

    /**
     * Lists all users.
     *
     *
    public function usersAction()
    {    
         return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/users.html.twig');  
    }/


    /**
     * user connection.
     *
     */
    public function connectionAction(Request $request)
    {    

        // Add these lines somewhere on top of your PHP file:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '-1');
        
    $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
    $this->get('session')->set('data_user' , $_SESSION['data_user']);

   
    $session=$request->getSession();



    $data_user = $session->get('utilisateur');
    $type = $data_user[0]['type'];
    $cf = $data_user[0]['code_firme'];


    if(!empty($cf)){

        return $this->render('EcommerceBundle:Administration:statistics/homepage2.html.twig',array('data_user' => $data_user, 'code_firme' =>$cf));

    }else{



        if($request->isMethod('post')) {
         
            $email = $request->get('email');
            $passwd= $request->get('password');

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname='espace_clients';
            $dbname2='CRM_EDICOM';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $connection2 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname2",$username,$password)));
            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));
            

           /* $connection2->exec(SET CHARACTER SET utf8);*/

           

            $findAdmin = $connection2->fetchAll("
                SELECT nom, prenom, email, code_commercial, id_service, code_commande,id as id_user,'admin' as profile
                FROM `tts_utilisateur`
                WHERE login = '$email' and `pwd` = '".MD5($passwd)."'  and actif = 1 and code_commercial in('000','25','rania','admin')
                ");




            $findResCommercial = $connection2->fetchAll("
                SELECT u.`id`, u.`nom`, u.`prenom`, u.`email`, u.`code_commercial`, u.`id_service`, u.`code_commande`, ua.`id_utilisateur`, ua.`id_utilisateur_affecte`, u.`id` as id_user, 'responsable' as profile
                FROM `tts_utilisateur` u
                inner join tts_utilisateur_affecte ua on ua.`id_utilisateur` = u.`id`
                /* LEFT JOIN telecontact_BackOffice_Site.`firme` fir on  */
                WHERE login = '".$email."' AND `pwd` = '".MD5($passwd)."' AND actif = 1
                ");

            $findCommercial = $connection2->fetchAll("
                SELECT nom, prenom, email, code_commercial, id_service, code_commande,id as id_user,'commercial' as profile
                FROM `tts_utilisateur`
                WHERE login = '$email' AND `pwd` = '".MD5($passwd)."' AND actif = 1
                ");


             $findClient = $connection->fetchAll("
                SELECT u.*, p.`nom`, p.`prenom`, u.`code_firme` ,'client' as profile
                FROM espace_clients .`utilisateurs`  u
                INNER JOIN BD_EDICOM .`lien_dirigeant` d on d.`code_firme` = CONCAT('MA', u.`code_firme` )
                INNER JOIN BD_EDICOM .`personne` p on p.`code_personne` = d.`code_personne`
                WHERE u.`email` = '$email' and u.`password` = '".MD5($passwd)."' 
                ");

/*             echo "
                SELECT u.*, p.`nom`, p.`prenom`, u.`code_firme` ,'client' as profile
                FROM espace_clients .`utilisateurs`  u
                INNER JOIN BD_EDICOM .`lien_dirigeant` d on d.`code_firme` = CONCAT('MA', u.`code_firme` )
                INNER JOIN BD_EDICOM .`personne` p on p.`code_personne` = d.`code_personne`
                WHERE u.`email` = '$email' and u.`password` = '".MD5($passwd)."' 
                ";

                die();
*/

              $findClient_telecontact = $connection->fetchAll("
                  SELECT u.`id_user`, u.`udid` as id, u.`email`, u.`nom`, u.`prenom`, u.`code_firme`, u.`ville`, v.`ville`, u.`societe`, f.`rs_comp` as raison_sociale, 'client' as profile 
                  FROM telecontact_BackOffice_Site.`MOBILE_utilisateur_web` u
                  left JOIN BD_EDICOM.`firmes` f on f.`code_firme` = CONCAT('MA', u.`code_firme`)
                  left JOIN BD_EDICOM.`villes` v on v.`code`= u.`ville`
                  WHERE email='$email' AND password='".MD5($passwd)."' AND source!='facebook' 
                ");



            // accés client
                
            if(!empty($findAdmin)){




                unset($findAdmin[0]['password']);
                
                $this->get('session')->set('utilisateur' , $findAdmin);
                

                /*$token = $_SESSION['utilisateur_web_id'] * 1256 . '&' . str_shuffle ('ala') . $_SESSION['utilisateur_web_email'] . '&' .  $_SESSION['utilisateur_web_type'] . '&' . str_shuffle ('ele') . $_SESSION['utilisateur_web_nom'] . '&' . $_SESSION['utilisateur_web_prenom'] . '&' . $_SESSION['utilisateur_profile'] . '&'. $_SESSION['utilisateur_code_comm'] . str_shuffle('9876') . '&' . $_SESSION['connexion_code'] .'&' . $_SESSION['source'];*/

                $session=$request->getSession();
                $data_user = $session->get('utilisateur');
                $this->get('session')->set('data_user' , $data_user);
 
                $historique_connexion = $connection->executeQuery("INSERT INTO `historique_connexion` (id_user, date_connexion, type_user, source, action ) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 1, 1, 1) ");
                /*$this->get('session')->set('deconnecion_code' , 62);*/
                $this->get('session')->set('source' , 1);
                $_SESSION['is_client']                  = 1;

                $client= $connection3->fetchAll("SELECT acc.code_firme,acc.raison_sociale,l.email as email1 ,emp.last_name
                FROM erpprod.`u_yf_ssingleorders` s
                INNER join erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                left join  BD_EDICOM.lien_email l on l.code_firme = CONCAT('MA', acc.code_firme)
                WHERE s.support = 'Telecontact' and s.type_ordre = 'Internet'
                group by acc.code_firme
                ");/*`responsable` = 25 and */

                $client = array_map(function($c){
                    $c['raison_sociale'] = json_decode($c['raison_sociale']);
                    $c['last_name'] = json_decode($c['last_name']);
                    return $c;
                }, $client);



 
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/clients.html.twig', array('data_user'=>$data_user,'client'=>$client));




            // accés Responsable commercial

            }elseif(!empty($findResCommercial)){
                /*die("in");*/



                $id_commercial_affecte = array();
                    for ($i=0; $i<count($findResCommercial); $i++){
                        array_push($id_commercial_affecte, $findResCommercial[$i]['id_utilisateur_affecte']);
                }

                $commercial_affecte = $connection2->fetchAll("
                    SELECT u.id, u.nom, u.prenom, u.email, u.code_commercial
                    FROM tts_utilisateur u
                    WHERE u.id in(" . implode(',', $id_commercial_affecte) . ")
                    ");


                /*var_dump($commercial_affecte[0]);die('res');*/



                unset($findResCommercial[0]['password']);
                
                $this->get('session')->set('utilisateur' , $findResCommercial);

                $session=$request->getSession();
                $data_user = $session->get('utilisateur');
                $this->get('session')->set('data_user' , $data_user);
                /*var_dump($session);
                var_dump($data_user[0]['id_user']);
                die('ff');*/
                $historique_connexion = $connection->executeQuery("INSERT INTO `historique_connexion` (id_user, date_connexion, type_user, source, action) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 1, 1, 1) ");
                $this->get('session')->set('source' , 1);
                $_SESSION['is_client']                  = 1;

               

                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/commerciaux.html.twig', array('data_user' => $data_user, 'commercial_affecte' => $commercial_affecte));




            // accés commerciaux

            }elseif(!empty($findCommercial)){


                unset($findCommercial[0]['password']);
                
                $this->get('session')->set('utilisateur' , $findCommercial);

                $session=$request->getSession();
                $data_user = $session->get('utilisateur');
                $this->get('session')->set('data_user' , $data_user);

                $historique_connexion = $connection->executeQuery("INSERT INTO `historique_connexion` (id_user, date_connexion, type_user, source, action) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 1, 1, 1) ");
                $this->get('session')->set('source' , 1);
                $_SESSION['is_client']                  = 1;
                

                $client= $connection3->fetchAll("SELECT acc.code_firme,acc.raison_sociale,l.email as email1,emp.last_name
                FROM erpprod.`u_yf_ssingleorders` s
                INNER join erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                left join  BD_EDICOM.lien_email l on l.code_firme = CONCAT('MA', acc.code_firme)
                WHERE `responsable` = '".$data_user[0]['code_commercial']."' and s.support = 'Telecontact' and s.type_ordre = 'Internet'
                group by acc.code_firme
                ");
                
                

                /* $client = array_map(function($c){
                    $c['raison_sociale'] = json_encode($c['raison_sociale']);
                    return $c;
                }, $client);

                var_dump($client);
                echo "<br>111";
                die('ee'); */

               
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/clientsComm.html.twig', array('client' => $client));


            }elseif (!empty($findClient)) {

                unset($findClient[0]['password']);
                $this->get('session')->set('utilisateur' , $findClient);
                $session=$request->getSession();
                $session_data = $session->get('utilisateur');

                /* traitement ville */

                $ville        = $session_data[0]['ville'];
                $ville        = strtolower($ville); 
                $ville        = str_replace('.','',$ville);
                $ville        = str_replace(',','',$ville);
                $ville        = str_replace('\'','-',$ville);
                $ville        = str_replace('’','-',$ville);
                $ville        = str_replace(' ','-',$ville);
                $ville        = str_replace('','-',$ville);
                $ville        = str_replace('--','-',$ville);
                $ville        = str_replace('°','-',$ville);
                $ville        = preg_replace('/[áàãâä]/ui', 'a', $ville);
                $ville        = preg_replace('/[éèêë]/ui', 'e', $ville);
                $ville        = preg_replace('/[íìîï]/ui', 'i', $ville);
                $ville        = preg_replace('/[óòõôö]/ui', 'o', $ville);
                $ville        = preg_replace('/[úùûü]/ui', 'u', $ville);
                $ville        = preg_replace('/[ç]/ui', 'c', $ville);

                /* end traitelent ville */


                /* traitement rs */
                echo  $session_data[0]['raison_sociale'].'no go <br/>';

             

                $raison_sociale        = $session_data[0]['raison_sociale'];
                $raison_sociale        = strtolower($raison_sociale); 
                $raison_sociale        = str_replace('.','',$raison_sociale);
                $raison_sociale        = str_replace(',','',$raison_sociale);
                $raison_sociale        = str_replace('\'','-',$raison_sociale);
                $raison_sociale        = str_replace('’','-',$raison_sociale);
                $raison_sociale        = str_replace(' ','-',$raison_sociale);
                $raison_sociale        = str_replace('','-',$raison_sociale);
                $raison_sociale        = str_replace('--','-',$raison_sociale);
                $raison_sociale        = str_replace('°','-',$raison_sociale);
                $raison_sociale        = preg_replace('/[áàãâä]/ui', 'a', $raison_sociale);
                $raison_sociale        = preg_replace('/[éèêë]/ui', 'e', $raison_sociale);
                $raison_sociale        = preg_replace('/[íìîï]/ui', 'i', $raison_sociale);
                $raison_sociale        = preg_replace('/[óòõôö]/ui', 'o', $raison_sociale);
                $raison_sociale        = preg_replace('/[úùûü]/ui', 'u', $raison_sociale);
                $raison_sociale_for_link        = preg_replace('/[ç]/ui', 'c', $raison_sociale);

                /* end traitelent rs */


                $data_user = $session->get('utilisateur');
                $this->get('session')->set('data_user' , $data_user);
                $this->get('session')->set('ville' , $ville);
                $this->get('session')->set('raison_sociale_for_link' , $raison_sociale_for_link);

                $cf =$data_user[0]['code_firme'];

                

                

               $historique_connexion = $connection->executeQuery("INSERT INTO `historique_connexion` (id_user, date_connexion, type_user, source, action) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 2, 1, 1) ");
               $this->get('session')->set('source' , 2);
               $_SESSION['is_client']                   = 3;



               if(empty($findClient[0]['code_firme'])){
                    return $this->redirect('https://www.telecontact.ma/');
                }
               /*echo "INSERT INTO `historique_connexion` (id_user, date_connexion, type_user) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 1)";die('1');*/

                $vignette = $connection3->executeQuery("SELECT acc.code_firme,acc.raison_sociale,s.date_ordre,code_produit/*,atr.debut_mise_en_ligne, atr.fin_mise_en_ligne*/
                        FROM erpprod.u_yf_ssingleorders s
                        INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                        INNER join erpprod.u_yf_ssingleorders_inventory i on i.id=s.ssingleordersid
                        INNER join erpprod.vtiger_service se on se.serviceid=i.name
                        /*INNER join erpprod.u_yf_atribution atr on atr.serviceid=i.name*/
                        WHERE s.support='telecontact' AND s.type_ordre='Internet' AND code_produit in('VA','VB','V0','V1') AND acc.code_firme=$cf
                        group by s.num_ordre");

                
                        
                $resultvignette = $vignette->fetchAll();

                

                $session=$request->getSession();

                if(!empty($resultvignette)){

                    $rs =  $resultvignette[0]['raison_sociale'];
                    $rs =  utf8_encode($rs);

                    $data_vignette =array(
                    'code_firme'=>$resultvignette[0]['code_firme'],
                    'raison_sociale'=>$rs,
                    'ville'=>$ville,
                    'raison_sociale_for_link'=>$raison_sociale_for_link
                    /*'debut_mise_en_ligne'=>$resultvignette[0]['debut_mise_en_ligne'],
                    'fin_mise_en_ligne'=>$resultvignette[0]['fin_mise_en_ligne'],*/
                    );

                    
                    $session->set('data_vignette', $data_vignette);
                    $data_vignette = $session->get('data_vignette');

                }else{
                    $data_vignette = 0;
                }

                return $this->render('EcommerceBundle:Administration:statistics/homepage2.html.twig',array('data_vignette' => $data_vignette, 'data_user' => $data_user, 'code_firme' =>$cf));






            // accés administrateur

            }elseif(!empty($findClient_telecontact)) {

                unset($findClient_telecontact[0]['password']);
                $this->get('session')->set('utilisateur' , $findClient_telecontact);
                $session=$request->getSession();

                $session_data = $session->get('utilisateur');

                /* traitement ville */

               /* $ville        = $session_data[0]['ville'];
                $ville        = strtolower($ville); 
                $ville        = str_replace('.','',$ville);
                $ville        = str_replace(',','',$ville);
                $ville        = str_replace('\'','-',$ville);
                $ville        = str_replace('’','-',$ville);
                $ville        = str_replace(' ','-',$ville);
                $ville        = str_replace('','-',$ville);
                $ville        = str_replace('--','-',$ville);
                $ville        = str_replace('°','-',$ville);
                $ville        = preg_replace('/[áàãâä]/ui', 'a', $ville);
                $ville        = preg_replace('/[éèêë]/ui', 'e', $ville);
                $ville        = preg_replace('/[íìîï]/ui', 'i', $ville);
                $ville        = preg_replace('/[óòõôö]/ui', 'o', $ville);
                $ville        = preg_replace('/[úùûü]/ui', 'u', $ville);
                $ville        = preg_replace('/[ç]/ui', 'c', $ville);*/

                /* end traitelent ville */

                /* traitement rs */

                /*$raison_sociale        = $session_data[0]['raison_sociale'];
                $raison_sociale        = strtolower($raison_sociale); 
                $raison_sociale        = str_replace('.','',$raison_sociale);
                $raison_sociale        = str_replace(',','',$raison_sociale);
                $raison_sociale        = str_replace('\'','-',$raison_sociale);
                $raison_sociale        = str_replace('’','-',$raison_sociale);
                $raison_sociale        = str_replace(' ','-',$raison_sociale);
                $raison_sociale        = str_replace('','-',$raison_sociale);
                $raison_sociale        = str_replace('--','-',$raison_sociale);
                $raison_sociale        = str_replace('°','-',$raison_sociale);
                $raison_sociale        = preg_replace('/[áàãâä]/ui', 'a', $raison_sociale);
                $raison_sociale        = preg_replace('/[éèêë]/ui', 'e', $raison_sociale);
                $raison_sociale        = preg_replace('/[íìîï]/ui', 'i', $raison_sociale);
                $raison_sociale        = preg_replace('/[óòõôö]/ui', 'o', $raison_sociale);
                $raison_sociale        = preg_replace('/[úùûü]/ui', 'u', $raison_sociale);
                $raison_sociale_for_link        = preg_replace('/[ç]/ui', 'c', $raison_sociale);*/

                /* end traitelent rs */


                $data_user = $session->get('utilisateur');
                $this->get('session')->set('data_user' , $data_user);
                if (isset($ville)) {
                    $this->get('session')->set('ville' , $ville);
                }
                if (isset($raison_sociale_for_link)) {
                    $this->get('session')->set('raison_sociale_for_link' , $raison_sociale_for_link);
                }

                $cf =$data_user[0]['code_firme'];

                if(empty($findClient_telecontact[0]['code_firme'])){

                    $user_id =$findClient_telecontact[0]['id_user'];

                    if (empty($clients_telecontact)) {
                        $clients_telecontact = array();
                    }

                    if (empty($client)) {
                        $client = array();  
                    }

                    return $this->render('EcommerceBundle:Administration:statistics/homepage2.html.twig', array('clients_telecontact'=>$clients_telecontact, 'client' =>$client , 'is_client_telecontact' => true));
                }

               
                $historique_connexion = $connection->executeQuery("INSERT INTO `historique_connexion` (id_user, date_connexion, type_user, source, action) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 3, 1, 1) ");
                $this->get('session')->set('deconnecion_code' , 3);
                $_SESSION['is_client']                  = 2;

                /*echo "INSERT INTO `historique_connexion` (id_user, date_connexion, type_user) VALUES ('".$data_user[0]['id_user']."',  '".date("Y-m-d H:i:s")."', 3)";die('3');*/

                $vignette = $connection3->executeQuery("SELECT acc.code_firme,acc.raison_sociale,s.date_ordre,code_produit/*,atr.debut_mise_en_lign, atr.fin_mise_en_ligne*/
                        FROM erpprod.u_yf_ssingleorders s
                        INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                        INNER join erpprod.u_yf_ssingleorders_inventory i on i.id=s.ssingleordersid
                        INNER join erpprod.vtiger_service se on se.serviceid=i.name
                        /*INNER join erpprod.u_yf_atribution atr on atr.serviceid=i.name*/
                        WHERE s.support='telecontact' AND s.type_ordre='Internet' AND code_produit in('VA','VB','V0','V1') AND acc.code_firme=$cf
                        group by s.num_ordre");
                        
                $resultvignette = $vignette->fetchAll();


                $session=$request->getSession();

                if(!empty($resultvignette)){

                    $rs =  $resultvignette[0]['raison_sociale'];
                    $rs =  utf8_encode($rs);

                    $data_vignette =array(
                    'code_firme'=>$resultvignette[0]['code_firme'],
                    'raison_sociale'=>$rs
                    /*'debut_mise_en_ligne'=>$resultvignette[0]['debut_mise_en_ligne'],
                    'fin_mise_en_ligne'=>$resultvignette[0]['fin_mise_en_ligne'],*/
                    );

                    
                    $session->set('data_vignette', $data_vignette);
                    $data_vignette = $session->get('data_vignette');
                }else{
                    $data_vignette = 0;
                }


               

                return $this->render('EcommerceBundle:Administration:statistics/homepage2.html.twig',array('data_vignette' => $data_vignette, 'data_user' => $data_user, 'code_firme' =>$cf));






            // accés administrateur

            }else {
                $request->getSession()->getFlashBag()->add('danger', 'login ou mot de passe incorrect');
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/login.html.twig');

            }
        }


    }
        return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/login.html.twig');  
    }

    public function reintialiserMdpAction(Request $request){


        $email = $request->get('email');


        if($request->isMethod('post')){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection =$connectionFactory->createConnection(array('pdo'=>new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

                $exist   = $connection->fetchAll("SELECT * FROM `utilisateurs` WHERE `email` = '".$email."' ");
                    if(!$exist){
                        $exist = $connection->fetchAll("SELECT * FROM  telecontact_BackOffice_Site.`MOBILE_utilisateur_web` WHERE `email` = '".$email."' ");
                    }

                if(!$exist){
                    $request->getSession()->getFlashBag()->add('danger', 'L’email saisi ne correspond à aucun compte. Merci de renseigner un autre email ou créez votre compte.');

                }else{
                    $sujet = 'récuperation mot de passe espace client telecontact';

              

                $destinataire = '"'.$email.'"';
                $expediteur = 'noreplay@edicom.ma';
                $headers = 'From: Espace client Telecontact <' . $destinataire . '> ' . "\r\n" 
                .'Reply-To: Espace client Telecontact <' . $destinataire . '> ' . "\r\n"
                .'Content-Type:text/html;charset=utf-8' . "\r\n" 
                .'X-Mailer: PHP/' . phpversion();
                $headers .= "Return-Path: Espace client telecontact <" . $destinataire . '> ' . "\r\n";
                $message = '<html><head><title>Espace client telecontact</title></head><body><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FDFDFD"><tr style="background-color:#ffdd00;color:black"><td height="80" align="center" valign="middle" bgcolor="#ffdd00" color="#ffffff"><h1>Espace client telecontact</h1></td></tr><tr><td height="60" color="#000000"><p style="padding-left: 35px;padding-right: 20px;padding-top:35px;font-size: 17px;">Bonjour,<br><br></p></td></tr><tr><td style="padding: 35px;font-size: 17px;line-height: 25px;"><label>Ce mail fait suite à votre demande de changement de mot de passe sur l\'espace client telecontact.<br>Pour générer un nouveau mot de passe, veuillez cliquer sur le lien ci-dessous:<br><a href="https://www.telecontact.ma/espace-client/web/valider_reintialisation_mdp">https://www.telecontact.ma/espace-client/web/valider_reintialisation_mdp</a></span></td></tr><tr><td><p style="padding-left: 6%;padding-bottom: 20px;font-size: 17px;">Cordialement.<br>L\'équipe Telecontact</p></td></tr><tr height="70" align="center" valign="middle" bgcolor="#ffdd00" color="#000000"><td><table width="600" border="0" cellspacing="0" cellpadding="0"><tr color="#000000"><td style="padding-left: 20px;padding-right: 20px"><strong style="color:black">'.$ses[0]['raison_sociale'].'</strong></td></tr></table></td></tr></table></body></html>';

                mail($destinataire, $sujet, $message, $headers);

                }

                
            
           return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/reinitialiser_mdp.html.twig');
            
        }else{

            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/reinitialiser_mdp.html.twig');
        }


    }

    public function validerReintialisationMdpAction(Request $request){


        $email = $request->get('email');
        $password = $request->get('password');


        if($request->isMethod('post')){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection =$connectionFactory->createConnection(array('pdo'=>new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

            $exist   = $connection->fetchAll("SELECT * FROM `utilisateurs` WHERE `email` = '".$email."' ");

                if(!$exist){
                        $exist = $connection->fetchAll("SELECT * FROM  telecontact_BackOffice_Site.`MOBILE_utilisateur_web` WHERE `email` = '".$email."' ");
                }

                if(!$exist){
                    $request->getSession()->getFlashBag()->add('danger', 'L’email saisi ne correspond à aucun compte. Merci de renseigner un autre email ou créez votre compte.');
                }

            $Update = $connection->executeQuery(" UPDATE `utilisateurs` SET `password` = MD5('".$password."') WHERE  `email` = '".$email."' ");
            $Update = $connection->executeQuery(" UPDATE telecontact_BackOffice_Site.`MOBILE_utilisateur_web` SET `password` = MD5('".$password."') WHERE  `email` = '".$email."' ");

            $request->getSession()->getFlashBag()->add('success', 'Votre mot de passe est modifié.');


            return $this->redirect($this->generateUrl('connection'));
            
        }else{

            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/validerReintialisationMdp.html.twig');
        }


    }


    public function homepage2Action(Request $request, $code_firme){

        $session=$request->getSession();

        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        
        if(!empty($ses_id_user)){

            $start_period = '-1months';
            $end_period = '-1days';

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='BD_EDICOM';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';
            $connection =$connectionFactory->createConnection(array('pdo'=>new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));


            $queryClient ="
                    SELECT f.`code_firme` , f.`rs_comp` as raison_sociale, f.`rs_abr` as abr
                    FROM `firmes` f
                    WHERE f.`code_firme` = CONCAT('MA', '".$code_firme."') 
                    GROUP BY `code_firme`
                    ";


            $client= $connection->fetchAll($queryClient);

            $clients_telecontact = count($client);


            $session = $request->getSession();
            $session->set('cf', $code_firme);
            $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
            $session->set('selectedrange', $selecterange);
            
            $rs = utf8_encode($client[0]['raison_sociale']);
            $abr = utf8_encode($client[0]['abr']);
            $cf = array('code_firme' => $code_firme); 
            $rs = array('raison_sociale' => $rs); 
            $abr = array('abr' => $abr); 

            $session=$request->getSession();
            $data_user = $session->get('utilisateur');
            $data_user[0] = array_merge($data_user[0], $cf, $rs, $abr);

            $this->get('session')->set('data_user' , $data_user);
            if(!empty($_SESSION['connexion_code'])){
                $this->get('session')->set('connexion_code' , $_SESSION['connexion_code']);


            

        }
            



           
            

            return $this->render('EcommerceBundle:Administration:statistics/homepage.html.twig', array('clients_telecontact'=>$clients_telecontact, 'client' =>$client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    }


    public function homepageAction(Request $request, $code_firme="", $token=""){

        $session=$request->getSession();


        if(!empty($token)){


                
                $token = base64_decode($token);
                $token = explode('&', $token);
                $utilisateur_espace_client[0] = array(
                            "id_user" => $token[0]/1256,
                            "email" => substr($token[1], 3),
                            "type" => $token[2],
                            "nom" => substr($token[3], 3),
                            "prenom" => $token[4],
                            "profile" => $token[5],
                            "code_commercial" => substr($token[6], 0, -4),
                        );
                $session->remove('data_user');$session->remove('utilisateur');
                $_SESSION['utilisateur'][0] = $utilisateur_espace_client[0];
                $_SESSION['data_user'][0]   = $utilisateur_espace_client[0];
                $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
                $this->get('session')->set('source' , $token[8]);
                if(!empty($_SESSION['connexion_code'])){
                    $this->get('session')->set('connexion_code' , $token[7]  );
                }

                $ses = $session->get('utilisateur');
            }
            


        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $start_period = '-1months';
            $end_period = '-1days';

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='BD_EDICOM';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';
            $connection =$connectionFactory->createConnection(array('pdo'=>new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));




            $queryClient ="
                    SELECT f.`code_firme`, f.`rs_comp` as raison_sociale , v.`ville`
                    FROM `firmes` f 
                    LEFT JOIN `villes` v on v.`code`= f.`code_ville`
                    WHERE f.`code_firme` = CONCAT('MA', '".$code_firme."')
                    GROUP BY `code_firme`
                    ";

                  /*  var_dump($ses[0]['code_commercial']);die('ed');*/
            $client= $connection->fetchAll($queryClient);

            $clients_telecontact = count($client);


            $session = $request->getSession();
            $session->set('cf', $code_firme);
            $selecterange=array('start_period' => $start_period, 'end_period' => $end_period);
            $session->set('selectedrange', $selecterange);
            
            $rs = utf8_encode($client[0]['raison_sociale']);
            $cf = array('code_firme' => $code_firme); 
            $rs = array('raison_sociale' => $rs); 

            $session=$request->getSession();
            $data_user = $session->get('utilisateur');

            $data_user[0] = array_merge($data_user[0], $cf, $rs);


            $this->get('session')->set('data_user' , $data_user);
            if(!empty($_SESSION['connexion_code'])){
                $this->get('session')->set('connexion_code' , $_SESSION['connexion_code']);
            }

                /* traitement ville */

                    $ville        = $client[0]['ville'];
                    $ville        = strtolower($ville); 
                    $ville        = str_replace('.','',$ville);
                    $ville        = str_replace(',','',$ville);
                    $ville        = str_replace('\'','-',$ville);
                    $ville        = str_replace('’','-',$ville);
                    $ville        = str_replace(' ','-',$ville);
                    $ville        = str_replace('','-',$ville);
                    $ville        = str_replace('--','-',$ville);
                    $ville        = str_replace('°','-',$ville);
                    $ville        = preg_replace('/[áàãâä]/ui', 'a', $ville);
                    $ville        = preg_replace('/[éèêë]/ui', 'e', $ville);
                    $ville        = preg_replace('/[íìîï]/ui', 'i', $ville);
                    $ville        = preg_replace('/[óòõôö]/ui', 'o', $ville);
                    $ville        = preg_replace('/[úùûü]/ui', 'u', $ville);
                    $ville        = preg_replace('/[ç]/ui', 'c', $ville);

                /* end traitelent ville */


                /* traitement rs */



                    $raison_sociale        = strtolower($rs['raison_sociale']); 
                    $raison_sociale        = str_replace('.','',$raison_sociale);
                    $raison_sociale        = str_replace(',','',$raison_sociale);
                    $raison_sociale        = str_replace('\'','-',$raison_sociale);
                    $raison_sociale        = str_replace('’','-',$raison_sociale);
                    $raison_sociale        = str_replace(' ','-',$raison_sociale);
                    $raison_sociale        = str_replace('','-',$raison_sociale);
                    $raison_sociale        = str_replace('--','-',$raison_sociale);
                    $raison_sociale        = str_replace('°','-',$raison_sociale);
                    $raison_sociale        = preg_replace('/[íìîï]/ui', 'i', $raison_sociale);
                    $raison_sociale        = preg_replace('/[áàãâä]/ui', 'a', $raison_sociale);
                    $raison_sociale        = preg_replace('/[éèêë]/ui', 'e', $raison_sociale);
                    $raison_sociale        = preg_replace('/[óòõôö]/ui', 'o', $raison_sociale);
                    $raison_sociale        = preg_replace('/[úùûü]/ui', 'u', $raison_sociale);
                    $raison_sociale_for_link        = preg_replace('/[ç]/ui', 'c', $raison_sociale);


                /* end traitelent rs */

            $this->get('session')->set('ville' , $ville );
            $this->get('session')->set('raison_sociale_for_link' , $raison_sociale_for_link );


            /* check vignette */


            $vignette = $connection3->executeQuery("SELECT acc.code_firme,acc.raison_sociale,s.date_ordre,code_produit
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

            /* end check vignette */

            $code_commercial = $ses[0]['code_commercial'];
            
            if(!empty($code_commercial)){

                /*$insert = $connection->executeQuery("
                                                    INSERT INTO espace_clients.`tracabilite` (id_user, date_acces, code_firme, type) VALUES ('".$ses_id_user."',  '".date("Y-m-d H:i:s")."', '".$code_firme."', 3)
                                                    ");*/

                $insert = $connection->executeQuery("
                                                    INSERT INTO espace_clients.`historique_connexion` (id_user, date_connexion, code_firme, action) VALUES ('".$ses_id_user."',  '".date("Y-m-d H:i:s")."', '".$code_firme."', 3)
                                                    ");
                
            }
            

            return $this->render('EcommerceBundle:Administration:statistics/homepage2.html.twig', array('clients_telecontact'=>$clients_telecontact, 'client' =>$client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    }

    public function clientsTelecontactAction(Request $request, $token=""){
        
            $session=$request->getSession();

            if(isset($_SESSION['utilisateur_web_id'])){

                $utilisateur_espace_client[0] = array(
                        "id_user" => $_SESSION['utilisateur_web_id'],
                        "email" => $_SESSION['utilisateur_web_email'],
                        "type" => $_SESSION['utilisateur_web_type'],
                        "nom" => $_SESSION['utilisateur_web_nom'],
                        "prenom" => $_SESSION['utilisateur_web_prenom'],
                        "profile" => $_SESSION['utilisateur_profile'],
                        "code_commercial" => $_SESSION['utilisateur_code_comm'],
                    );
                $session->remove('data_user');$session->remove('utilisateur');
                $_SESSION['utilisateur'][0]= $utilisateur_espace_client[0];
                $_SESSION['data_user'][0]= $utilisateur_espace_client[0];
                $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
                if(!empty($_SESSION['connexion_code'])){
                    $this->get('session')->set('connexion_code' , $_SESSION['connexion_code']  );
                }
            }elseif(!empty($token)){
                
                $token = base64_decode($token);
                $token = explode('&', $token);
                $utilisateur_espace_client[0] = array(
                            "id_user" => $token[0]/1256,
                            "email" => substr($token[1], 3),
                            "type" => $token[2],
                            "nom" => substr($token[3], 3),
                            "prenom" => $token[4],
                            "profile" => $token[5],
                            "code_commercial" => substr($token[6], 0, -4),
                        );
                $session->remove('data_user');$session->remove('utilisateur');
                $_SESSION['utilisateur'][0] = $utilisateur_espace_client[0];
                $_SESSION['data_user'][0]   = $utilisateur_espace_client[0];
                $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
                $this->get('session')->set('source' , $token[8]);
                if(!empty($_SESSION['connexion_code'])){
                    $this->get('session')->set('connexion_code' , $token[7]  );
                }

                $ses = $session->get('utilisateur');
            }
            
            /*var_dump($_SESSION);
            echo "<br><br>";
            var_dump($session->get('utilisateur'));
            die('jjjj');*/

        
            


        $ses = $session->get('utilisateur');

        
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            /*var_dump($session->get('utilisateur'));die('eereeree');*/


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));

            $queryClient = "SELECT acc.`code_firme`,acc.`raison_sociale`,l.`email` as email1
            FROM erpprod.`u_yf_ssingleorders` s
            INNER JOIN erpprod.vtiger_ossemployees emp ON emp.`ossemployeesid`=s.`comm`
            INNER JOIN erpprod.vtiger_account acc on acc.`accountid`=s.`firme`
            left join  BD_EDICOM.`lien_email` l on l.`code_firme` = CONCAT('MA', acc.`code_firme`)
            WHERE  s.`support` = 'Telecontact' and s.`type_ordre` = 'Internet'
            GROUP BY acc.`code_firme`";

            $client= $connection3->fetchAll($queryClient);
            $clients_telecontact = count($client);
            

            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/clients.html.twig', array('clients_telecontact'=>$clients_telecontact));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    }


    public function compteClientsTelecontactAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));
                
                $client= $connection3->fetchAll("SELECT acc.code_firme,acc.raison_sociale,l.email as email1,emp.last_name
                FROM erpprod.`u_yf_ssingleorders` s
                INNER join erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                left join  BD_EDICOM.lien_email l on l.code_firme = CONCAT('MA', acc.code_firme)
                WHERE  s.support = 'Telecontact' and s.type_ordre = 'Internet'
                group by acc.code_firme
                ");/*`responsable` = 25 and*/

            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/compteClients.html.twig', array('client'=>$client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
    }


    public function clientsCommercialAction(Request $request, $code_commercial, $token=""){

        $session=$request->getSession();
        if(isset($_SESSION['utilisateur_web_id'])){
            $utilisateur_espace_client[0] = array(
                    "id_user" => $_SESSION['utilisateur_web_id'],
                    "email" => $_SESSION['utilisateur_web_email'],
                    "type" => $_SESSION['utilisateur_web_type'],
                    "nom" => $_SESSION['utilisateur_web_nom'],
                    "prenom" => $_SESSION['utilisateur_web_prenom'],
                    "profile" => $_SESSION['utilisateur_profile'],
                    "code_commercial" => $_SESSION['utilisateur_code_comm'],
                );
            $session->remove('data_user');

            $_SESSION['utilisateur'][0]= $utilisateur_espace_client[0];
            $_SESSION['data_user'][0]= $utilisateur_espace_client[0]; 
            $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
            if(!empty($_SESSION['connexion_code'])){
                $this->get('session')->set('connexion_code' , $_SESSION['connexion_code']  );
            }

        }elseif(!empty($token)){
                
                $token = base64_decode($token);
                $token = explode('&', $token);
                $utilisateur_espace_client[0] = array(
                            "id_user" => $token[0]/1256,
                            "email" => substr($token[1], 3),
                            "type" => $token[2],
                            "nom" => substr($token[3], 3),
                            "prenom" => $token[4],
                            "profile" => $token[5],
                            "code_commercial" => substr($token[6], 0, -4),
                        );
                $session->remove('data_user');$session->remove('utilisateur');
                $_SESSION['utilisateur'][0] = $utilisateur_espace_client[0];
                $_SESSION['data_user'][0]   = $utilisateur_espace_client[0];
                $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
                $this->get('session')->set('source' , $token[8]);
                if(!empty($_SESSION['connexion_code'])){
                    $this->get('session')->set('connexion_code' , $token[7]  );
                }

                $ses = $session->get('utilisateur');
            }
          
        /*var_dump($session->get('utilisateur'));

        die();*/

        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));

            $client= $connection3->fetchAll("
                SELECT acc.code_firme,acc.raison_sociale,acc.email1,emp.last_name
                FROM erpprod.`u_yf_ssingleorders` s
                INNER join erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                WHERE `responsable` = '".$code_commercial."' and s.support = 'Telecontact' and s.type_ordre = 'Internet'
                group by acc.code_firme
                ");

           

                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/clientsComm.html.twig', array('client' => $client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    } 


    public function ResCommercialAction(Request $request, $id_utilisateur, $token=""){

        $session=$request->getSession();

        if(isset($_SESSION['utilisateur_web_id'])){
            $utilisateur_espace_client[0] = array(
                    "id_user" => $_SESSION['utilisateur_web_id'],
                    "id_utilisateur" => $_SESSION['utilisateur_web_id'],
                    "email" => $_SESSION['utilisateur_web_email'],
                    "type" => $_SESSION['utilisateur_web_type'],
                    "nom" => $_SESSION['utilisateur_web_nom'],
                    "prenom" => $_SESSION['utilisateur_web_prenom'],
                    "profile" => $_SESSION['utilisateur_profile'],
                    "code_commercial" => $_SESSION['utilisateur_code_comm'],
                );
            $session->remove('data_user');
            $_SESSION['utilisateur'][0]= $utilisateur_espace_client[0];
            $_SESSION['data_user'][0]= $utilisateur_espace_client[0]; 
            $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
            if(!empty($_SESSION['connexion_code'])){
                $this->get('session')->set('connexion_code' , $_SESSION['connexion_code']  );
            }
        }elseif(!empty($token)){
                
                $token = base64_decode($token);
                $token = explode('&', $token);
                $utilisateur_espace_client[0] = array(
                            "id_user" => $token[0]/1256,
                            "id_utilisateur" => $token[0]/1256,
                            "email" => substr($token[1], 3),
                            "type" => $token[2],
                            "nom" => substr($token[3], 3),
                            "prenom" => $token[4],
                            "profile" => $token[5],
                            "code_commercial" => substr($token[6], 0, -4),
                            "code_firme" => "",
                        );
                $session->remove('data_user');$session->remove('utilisateur');
                $_SESSION['utilisateur'][0] = $utilisateur_espace_client[0];
                $_SESSION['data_user'][0]   = $utilisateur_espace_client[0];
                $this->get('session')->set('utilisateur' , $_SESSION['utilisateur']);
                $this->get('session')->set('data_user' , $_SESSION['utilisateur']);
                $this->get('session')->set('source' , $token[8]);
                if(!empty($_SESSION['connexion_code'])){
                    $this->get('session')->set('connexion_code' , $token[7]  );
                }

                $ses = $session->get('utilisateur');

                $ses_user = $session->get('data_user');
               

            }


        
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname2='CRM_EDICOM';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection2 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname2",$username,$password)));

                /*$session=$request->getSession();
                $data_user = $session->get('utilisateur');*/

                $findResCommercial = $connection2->fetchAll("
                SELECT u.id, u.nom, u.prenom, u.email, u.code_commercial, u.id_service, u.code_commande, ua.id_utilisateur, ua.id_utilisateur_affecte, ua.id as id_user, 'responsable' as profile
                FROM `tts_utilisateur` u
                inner join tts_utilisateur_affecte ua on ua.id_utilisateur = u.id
                WHERE id_utilisateur = '".$id_utilisateur."' and actif = 1
                ");
               
                $id_commercial_affecte = array();
                    for ($i=0; $i<count($findResCommercial); $i++){
                        array_push($id_commercial_affecte, $findResCommercial[$i]['id_utilisateur_affecte']);
                }

                $commercial_affecte = $connection2->fetchAll("
                    SELECT u.id,u.nom, u.prenom, u.email, u.code_commercial
                    FROM tts_utilisateur u
                    WHERE u.id in(" . implode(',', $id_commercial_affecte) . ")
                    ");
                

                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/commerciaux.html.twig', array('commercial_affecte' => $commercial_affecte));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    } 


    public function ResClientsAction(Request $request, $code_commercial){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));



               
                
                $client= $connection3->fetchAll("SELECT acc.code_firme,acc.raison_sociale,l.email as email1,emp.last_name
                FROM erpprod.`u_yf_ssingleorders` s
                INNER join erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                left join  BD_EDICOM.lien_email l on l.code_firme = CONCAT('MA', acc.code_firme)
                WHERE `responsable` = '".$code_commercial."' and s.support = 'Telecontact' and s.type_ordre = 'Internet'
                group by acc.code_firme
                ");

                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/clientsComm.html.twig', array('client' => $client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    } 


    public function disconnectAction(Request $request)
    {

        /*$session=$request->getSession();*/
        
        /*
         $ses = $session->get('utilisateur');
         if(!empty($ses)){
            $request->getSession()->clear();
            $this->getRequest()->getSession()->clear();            
         } 
         die(); 
         */

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){
        
         
             $data_user = $session->get('utilisateur');


             $type_user = $session->get('source');
             if(!(isset($type_user) && trim($type_user) != "")){
                $type_user = "telecontact";
             }


             $connectionFactory = $this->get('doctrine.dbal.connection_factory');
             $hostname='localhost';
             $dbname='espace_clients';
             $username='pyxicom';
             $password='Yz9nVEXjZ2hqptZT';
             $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

             $stmt_historique_connexion = $connection->prepare("INSERT INTO `historique_connexion` (id_user, date_connexion, type_user, source, action) VALUES (? , ? , ? , ? , ?) "); 

            $stmt_historique_connexion->execute(array($data_user[0]['id_user'] , date("Y-m-d H:i:s") , $type_user , 1 , 2));
             

            session_destroy();
            $request->getSession()->invalidate();

            return $this->redirect($this->generateUrl('connection'));

        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    }



    public function session_insertAction(Request $request)
    {
        $request->getSession();

        $utilisateur_espace_client = $request->get('aa');
        var_dump($utilisateur_espace_client);
        $this->get('session')->set('test' , $utilisateur_espace_client);
        
        var_dump($request->getSession('test'));
        return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/session_insert.html.twig', array());

    }



    public function monCompteAction(Request $request, $code_firme)
    {


        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname='localhost';
                $dbname='espace_clients';
                $username='pyxicom';
                $password='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));


            $client = $connection->fetchAll("
                    SELECT *,'client' as profile 
                    FROM `utilisateurs` 
                    WHERE code_firme = '$code_firme'
                    ");
           
            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/MonCompte.html.twig', array('client' => $client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
    }


    public function updateCompteAction(Request $request, $code_firme)
    {


        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $dbname='espace_clients';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));


            $client = $connection->fetchAll("
                    SELECT * FROM `utilisateurs` 
                    WHERE code_firme = '$code_firme'
                    ");


            $apassword = $request->get('apassword');
            $npassword = $request->get('npassword');
            $email = $request->get('email');

            $query = "UPDATE `utilisateurs` set ";
            $old_pass = $connection->fetchAll("
                    SELECT password FROM `utilisateurs` 
                    WHERE code_firme = '$code_firme'
                    ");

            if(!empty($apassword) && !empty($npassword)){
                
                $query .= "password = '".MD5($npassword)."',";
            }


            if($old_pass[0]['password'] == MD5($apassword)){
                $query .= " email = '". $email ."' WHERE code_firme = '$code_firme'";
                $client_pass = $connection->executeQuery($query);
                $request->getSession()->getFlashBag()->add('success', 'Vos Modifications ont été enregistré avec succès');
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/MonCompte.html.twig', array('client' => $client));
            }else{
                $request->getSession()->getFlashBag()->add('danger', 'votre ancien mot de passe est incorrect!');
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/MonCompte.html.twig', array('client' => $client));
            }

           
            
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
    }



    public function listeComptesClientAction(Request $request){

        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){
            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));

                $client= $connection3->fetchAll("SELECT acc.code_firme,acc.raison_sociale,l.email as email1,emp.last_name
                FROM erpprod.`u_yf_ssingleorders` s
                INNER join erpprod.vtiger_ossemployees emp on emp.ossemployeesid=s.comm
                INNER join erpprod.vtiger_account acc on acc.accountid=s.firme
                left join  BD_EDICOM.lien_email l on l.code_firme = CONCAT('MA', acc.code_firme)
                WHERE  s.support = 'Telecontact' and s.type_ordre = 'Internet'
                group by acc.code_firme
                ");/*`responsable` = 25 and*/

                $client = array_map(function($c){

                    $c['raison_sociale'] = utf8_encode($c['raison_sociale']);
                    return $c;

                }, $client);

            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/listeComptesClient.html.twig', array('client'=>$client));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
    }
    


    


    public function updateCompteClientAction(Request $request, $code_firme)
    {


        $session=$request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            if($request->isMethod('post')){


                $npassword = $request->get('npassword');
                $email     = $request->get('email');

                $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                    $hostname='localhost';
                    $dbname='espace_clients';
                    $username='pyxicom';
                    $password='Yz9nVEXjZ2hqptZT';
                    $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

                $client = $connection->fetchAll("
                        SELECT * FROM `utilisateurs` 
                        WHERE code_firme = '$code_firme'
                        ");

                $client = array_map(function($c){

                    $c['raison_sociale'] = utf8_encode($c['raison_sociale']);
                    return $c;

                }, $client);

                    $query =" UPDATE `utilisateurs` set email = '".$email."' ";
                    if(!empty($npassword)){
                    $query .=" , password = '".MD5($npassword)."' ";
                    }
                    $query .=" WHERE code_firme = '$code_firme' ";

                    $client_pass = $connection->executeQuery($query);

                    $request->getSession()->getFlashBag()->add('success', 'Le mot de passe a été modifié avec succès');

                    return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/compte_client_view.html.twig', array('client' => $client));
                


            }else{

                $connectionFactory = $this->get('doctrine.dbal.connection_factory');
                $hostname='localhost';
                $dbname='espace_clients';
                $username='pyxicom';
                $password='Yz9nVEXjZ2hqptZT';
                $connection = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));


                $client = $connection->fetchAll("
                        SELECT *,'client' as profile 
                        FROM `utilisateurs` 
                        WHERE code_firme = '$code_firme'
                        ");


                $client = array_map(function($c){

                    $c['raison_sociale'] = utf8_encode($c['raison_sociale']);
                    return $c;

                }, $client);
            

                $code_commercial = $ses[0]['code_commercial'];
            
                if(!empty($code_commercial)){
                    /*$insert = $connection->executeQuery("
                                                        INSERT INTO espace_clients.`tracabilite` (id_user, date_acces, code_firme, type) VALUES ('".$ses_id_user."',  '".date("Y-m-d H:i:s")."', '".$code_firme."', 4)
                                                        ");*/

                    $insert = $connection->executeQuery("
                                                        INSERT INTO espace_clients.`historique_connexion` (id_user, date_connexion, code_firme, action) VALUES ('".$ses_id_user."',  '".date("Y-m-d H:i:s")."', '".$code_firme."', 4)
                                                        ");
                    
                }


               
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/compte_client_view.html.twig', array('client' => $client));    
            }
                
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
    }




    public function prospectsAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');

        $connectionFactory = $this->get('doctrine.dbal.connection_factory');
        $hostname ='localhost';
        $dbname='BD_EDICOM';
        $username='pyxicom';
        $password='Yz9nVEXjZ2hqptZT';
        $connection =$connectionFactory->createConnection(array('pdo'=>new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));
       
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){

            if($request->isMethod('post')){

                /*$query = "
                    SELECT f.`code_firme` , f.`rs_comp`, f.`code_ville`, v.`ville`
                    FROM `firmes` f
                    INNER JOIN villes v on v.`code` = f.`code_ville`
                    LEFT JOIN annonceur_production ap ON ap.`code_firme` = f.`code_firme`
                    WHERE ap.`code_firme` IS NULL
                    AND code_fichier != 'O20'
                    AND maj_k NOT
                    IN ( 0, 8 )
                    AND maj_n NOT
                    IN ( 0, 8 )";*/


                $query ="
                    SELECT f.`code_firme` , f.`rs_comp`, f.`code_ville`, v.`ville`
                    FROM `firmes` f
                    INNER JOIN villes v on v.`code` = f.`code_ville`
                    where f.`code_firme` is not null
                    ";



                
                $code_firme = $request->get('code_firme');
                if(!empty($code_firme)){
                    $code_firme = 'MA'.$code_firme;
                    $query .= " AND f.`code_firme` = '".$code_firme."' ";
                }

                $raison_s = $request->get('raison_sociale');
                if(!empty($raison_s)){
                    $query .= " AND f.`rs_comp` like '%".$raison_s."%' ";
                }

                $ville = $request->get('ville');
                if(!empty($ville)){
                    $query .= " AND f.`code_ville` = '".$ville."' ";
                }

                $query .= " LIMIT 50 ";

                $prospect = $connection->fetchAll($query);
                $prospect = array_map(function($c){
                    $c['code_firme'] = substr($c["code_firme"], 2);
                    $c['rs_comp'] = utf8_encode($c['rs_comp']);
                    $c['ville'] = utf8_encode($c['ville']);
                    return $c;
                }, $prospect);



                $villes =$connection->fetchAll(" SELECT `code`, `ville` FROM BD_EDICOM.`villes` ");
                $villes = array_map(function($c) {
                    $c['ville'] = utf8_encode($c["ville"]);
                    return $c;
                }, $villes);

                /*var_dump($prospect);die();*/
                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/prospects.html.twig',array('villes' => $villes,'prospect' => $prospect));


            }else{

                $villes =$connection->fetchAll(" SELECT `code`, `ville` FROM BD_EDICOM.`villes` ");

                $villes = array_map(function($c) {
                    $c['ville'] = utf8_encode($c["ville"]);
                    return $c;
                }, $villes);

                $prospect='';

                return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/prospects.html.twig',array('villes' => $villes,'prospect' => $prospect));
            }
            
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }      


    }


    public function affectationAction(Request $request){
        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){
            $code_commercial = $ses[0]['code_commercial'];

            $connectionFactory=$this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $dbname='BD_EDICOM';

            $connection = $connectionFactory->createConnection(array('pdo' =>new \PDO("mysql:host=$hostname;dbname=$dbname",$username,$password)));

            $query = "SELECT af.code_firme, f.`rs_comp`, v.`ville`
                      FROM `affectation` af 
                      INNER JOIN BD_EDICOM.`firmes` f ON f.`code_firme`= CONCAT('MA', af.`code_firme`)
                      INNER JOIN BD_EDICOM.`villes` v ON f.`code_ville`= v.`code`
                      WHERE af.`courtier` ='".$code_commercial."'";


            $affected_clients=$connection->fetchAll($query);

            $affected_clients = array_map(function($c){
                $c['rs_comp'] = utf8_encode($c['rs_comp']);
                $c['ville'] = utf8_encode($c['ville']);
                return $c;
            }, $affected_clients);

            /*var_dump($affected_clients);die();*/

            return $this->render('EcommerceBundle:Administration:UtilisateursEspaceClient/affectation.html.twig', array('affected_clients'=>$affected_clients));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }
    }




    public function dataTableServerAction(Request $request){

        $response = array();

        $draw = $request->get("draw");

        $draw = (isset($draw)) ? intval($draw) : 0;

        $start = $request->get("start");
        $length = $request->get("length");

        $search = $request->get("search");

        $search = (isset($search) && !empty($search)) ? $search["value"] : "";

        $filterResult = 0;

        $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname='localhost';
            $hostname2='46.182.7.30';
            $dbname3='erpprod';
            $username='pyxicom';
            $password='Yz9nVEXjZ2hqptZT';
            $username2='edicom';
            $password2='dnSQ8Tg5HRYNwpli';

            $connection3 = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname2;dbname=$dbname3",$username2,$password2)));

             $queryClient = "SELECT acc.`code_firme`,acc.`raison_sociale`,l.`email` as email1
            FROM erpprod.`u_yf_ssingleorders` s
            INNER JOIN erpprod.vtiger_ossemployees emp ON emp.`ossemployeesid`=s.`comm`
            INNER JOIN erpprod.vtiger_account acc on acc.`accountid`=s.`firme`
            left join  BD_EDICOM.`lien_email` l on l.`code_firme` = CONCAT('MA', acc.`code_firme`)
            WHERE  s.`support` = 'Telecontact' and s.`type_ordre` = 'Internet'";

            if(isset($search) && $search != "") {
                $queryClient .= " and (acc.`code_firme` LIKE '%" . $search . "%' OR acc.`raison_sociale` LIKE '%" . $search . "%' )";
            }

            $queryClient .= " GROUP BY acc.`code_firme`
            LIMIT $start , $length";

            $client= $connection3->fetchAll($queryClient);/*`responsable` = 25 and*/

           

            $client = array_map(function($c){
                $c['code_firme'] = utf8_encode($c['code_firme']);
                $c['raison_sociale'] = utf8_encode($c['raison_sociale']);
                $c['email1'] = utf8_encode($c['email1']);
                return $c;

            }, $client);

            $countClient = $connection3->fetchAssoc("SELECT COUNT(*) AS countC FROM (SELECT acc.code_firme,acc.raison_sociale,acc.email1
            FROM erpprod.`u_yf_ssingleorders` s
            INNER JOIN erpprod.vtiger_ossemployees emp ON emp.`ossemployeesid`=s.`comm`
            INNER JOIN erpprod.vtiger_account acc on acc.`accountid`=s.`firme`
            left join  BD_EDICOM.`lien_email` l on l.`code_firme` = CONCAT('MA', acc.`code_firme`)
            WHERE  s.`support` = 'Telecontact' and s.`type_ordre` = 'Internet'
            GROUP BY acc.`code_firme`) AS countClient
            ");

            if(isset($search) && $search != "") {
                $filterResult = count($client);
            } else {
                $filterResult =  $countClient["countC"];
            }



         $response["draw"] = $draw;
         $response["data"] = $client;       
         $response["recordsTotal"] = $countClient["countC"];
         $response["recordsFiltered"] = $filterResult;

        return new JsonResponse($response);

    }


    public function historique_connectionAction(Request $request){

        $session = $request->getSession();
        $ses = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'telecontact_BackOffice_Site';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection        = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            $connection_history = $connection->fetchAll("SELECT h.`id` , h.`id_user` , h.`date_connexion` , h.`type_user`,
                                                         CASE WHEN h.`type_user` = 2 THEN ec.`raison_sociale`
                                                         WHEN h.`type_user`      = 1 THEN CONCAT( ce.`nom`,' ', ce.`prenom` )
                                                         WHEN h.`type_user`      = 3 THEN CONCAT( ut.`nom` ,' ', ut.`prenom` )
                                                         END AS user,
                                                         CASE WHEN h.`action`    = 1 THEN  'Connexion'
                                                         WHEN h.`action`         = 2 THEN 'Déconnexion'
                                                         WHEN h.`action`         = 3 THEN 'Consultation firme'
                                                         WHEN h.`action`         = 4 THEN 'Modification compte'
                                                         END AS Action,
                                                         CASE WHEN h.`source`    = 2 THEN 'Telecontact' ELSE 'Espace client' END AS Source,
                                                         CASE WHEN h.`type_user` = 2 THEN ec.`code_firme` WHEN h.`type_user` = 3 THEN ut.`code_firme` ELSE '' END AS code_firme
                                                         FROM espace_clients.`historique_connexion` h
                                                         LEFT JOIN espace_clients.`utilisateurs` ec ON h.`id_user` = ec.`id_user`
                                                         LEFT JOIN CRM_EDICOM.`tts_utilisateur` ce ON h.`id_user` = ce.`id`
                                                         LEFT JOIN telecontact_BackOffice_Site.`MOBILE_utilisateur_web` ut ON h.`id_user` = ut.`id_user`
                                                         where h.`code_firme` = 0
                                                         ORDER BY date_connexion DESC");


                                                        /*SELECT h.`id` , h.`id_user` , h.`date_connexion` , h.type_user,
                                                         CASE WHEN h.`type_user` in (1,12,612,61) THEN ec.`raison_sociale`
                                                         WHEN h.`type_user` in (2,22,62,622) THEN CONCAT( ce.`nom`,' ', ce.`prenom` )
                                                         ELSE CONCAT( ut.`nom` ,' ', ut.`prenom` )
                                                         END AS user,
                                                         CASE WHEN h.`type_user` in (0,61,62,63,611,612,622) THEN  'déconnexion'
                                                         ELSE 'cnx'
                                                         END AS etat,
                                                         CASE WHEN h.`type_user` in (22,11,12,622,611,612) THEN  'Telecontact'
                                                         ELSE 'Espace client'
                                                         END AS Source
                                                         FROM espace_clients.`historique_connexion` h
                                                         LEFT JOIN espace_clients.`utilisateurs` ec ON h.`id_user` = ec.`id_user`
                                                         LEFT JOIN CRM_EDICOM.`tts_utilisateur` ce ON h.`id_user` = ce.`id`
                                                         LEFT JOIN telecontact_BackOffice_Site.`MOBILE_utilisateur_web` ut ON h.`id_user` = ut.`id_user`
                                                         ORDER BY date_connexion DESC*/



            return $this->render("EcommerceBundle:Administration:UtilisateursEspaceClient/historique_connection.html.twig", array('connection_history' => $connection_history));
        }else{
            return $this->redirect($this->generateUrl('connection'));
        }

    }


    public function SuiviCommercialAction(Request $request){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'CRM_EDICOM';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection        = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));


            /*$commerciaux = $connection->fetchAll("SELECT u.*, CASE WHEN count(id_user) =0 THEN 0 ELSE count(id_user) END as total FROM CRM_EDICOM.tts_utilisateur u
                                                  left join espace_clients.tracabilite t on t.id_user=u.id
                                                  WHERE u.`actif` = 1 AND u.`id_service` IN (2,3)
                                                  group by u.id");*/

            $suivi = $connection->fetchAll("
                                            SELECT ce.`id`, ce.`code_commercial`,ce.`email`, f.`rs_comp`, hc.`date_connexion` AS date_connexion, CONCAT( ce.`nom` , ' ', ce.`prenom` ) AS user,
                                              CASE WHEN hc.`source` = 2 THEN 'Telecontact' ELSE 'Espace client' END AS source,
                                              CASE 
                                              WHEN hc.`action` =2  THEN 'Deconnexion' 
                                              WHEN hc.`action` =1  THEN 'Connexion'
                                              WHEN hc.`action` =3  THEN 'Consultation firme'
                                              WHEN hc.`action` =4  THEN 'Modification compte' 
                                              END AS action,
                                              CASE WHEN hc.`action` in(3,4) THEN hc.`code_firme` ELSE '' END AS code_firme
                                            FROM espace_clients.`historique_connexion` hc
                                            LEFT JOIN CRM_EDICOM.`tts_utilisateur` ce ON hc.`id_user` = ce.`id`
                                            LEFT JOIN BD_EDICOM.`firmes` f ON f.`code_firme` = CONCAT('MA', hc.`code_firme`) 
                                            WHERE ce.`actif` = 1 AND ce.`id_service` IN (2,3)
                                            order by date_connexion desc
                                            ");/*WHERE hc.`id_user` = $id*/

            
            return $this->render("EcommerceBundle:Administration:UtilisateursEspaceClient/SuiviCommercial.html.twig",array(/*'commerciaux' => $commerciaux, */'suivi' => $suivi));

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }


    }

    public function historique_cnx_commAction(Request $request, $id){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'espace_clients';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection        = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

            

            $commerciaux = $connection->fetchAll("
                                                  SELECT hc.`date_connexion` AS date_connexion, CONCAT( ce.`nom` , ' ', ce.`prenom` ) AS user,
                                                  CASE WHEN hc.`source` =1 THEN 'Espace client' WHEN hc.`source` =2 THEN 'Telecontact' ELSE '' END AS source,
                                                  CASE 
                                                    WHEN hc.`action` =2  THEN 'Deconnexion' 
                                                    WHEN hc.`action` =1  THEN 'Connexion'
                                                    WHEN hc.`action` =3  THEN 'Consultation firme'
                                                    WHEN hc.`action` =4  THEN 'Modification compte' 
                                                  END AS action,
                                                  CASE WHEN hc.`action` in(3,4) THEN hc.`code_firme` ELSE '' END AS code_firme
                                                  FROM espace_clients.`historique_connexion` hc
                                                  LEFT JOIN CRM_EDICOM.`tts_utilisateur` ce ON hc.`id_user` = ce.`id`
                                                  WHERE hc.`id_user` = $id
                                                  order by date_connexion desc
                                                ");

            $nom_commercial = $connection->fetchAll("
                                                  SELECT CONCAT(nom,' ', prenom) as user
                                                  FROM CRM_EDICOM.`tts_utilisateur`
                                                  WHERE id = $id;
                                                ");

                                                /*SELECT hc.`date_connexion` AS date_connexion, 'Espace client' AS source,
                                                  CASE WHEN hc.`type_user` =0 THEN 'Deconnexion' ELSE 'Connexion' END AS etat
                                                  FROM espace_clients.`historique_connexion` hc
                                                  WHERE hc.`id_user` = $id
                                                  UNION
                                                  SELECT muc.`date_connexion` AS date_connexion, 'Télécontact' AS source,
                                                  CASE WHEN muc.`type_user` =0 THEN 'Deconnexion' ELSE 'Connexion' END AS etat
                                                  FROM telecontact_BackOffice_Site.`MOBILE_utilisateur_connexion` muc
                                                  WHERE muc.`id_user` = $id
                                                  order by date_connexion desc*/

            
            return $this->render("EcommerceBundle:Administration:UtilisateursEspaceClient/historique_cnx_comm.html.twig",array('commerciaux' => $commerciaux, 'nom_commercial' => $nom_commercial));

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }


    }


    public function tracabiliteAction(Request $request, $id){

        $session = $request->getSession();
        $ses     = $session->get('utilisateur');
        $ses_id_user    = $ses[0]['id_user'];
        if(!empty($ses_id_user)){


            $connectionFactory = $this->get('doctrine.dbal.connection_factory');
            $hostname          = 'localhost';
            $dbname            = 'espace_clients';
            $username          = 'pyxicom';
            $password          = 'Yz9nVEXjZ2hqptZT';
            $connection        = $connectionFactory->createConnection(array('pdo' => new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$username,$password)));

           
            $tracabilite = $connection->fetchAll("
                                                  SELECT t.`id_user`, t.`date_acces`, t.`code_firme`, CONCAT( ce.`nom` , ' ', ce.`prenom` ) AS user, f.`rs_comp`,
                                                  CASE WHEN t.`type` = 1 THEN 'consultation de firme' ELSE 'Modification compte' END AS action
                                                  FROM espace_clients.`tracabilite` t
                                                  LEFT JOIN CRM_EDICOM.`tts_utilisateur` ce ON t.`id_user` = ce.`id`
                                                  INNER JOIN BD_EDICOM.`firmes` f ON  f.`code_firme` = CONCAT('MA',t.`code_firme`)
                                                  WHERE t.`id_user` = $id
                                                  order by date_acces desc
                                                ");


            $nom_commercial = $connection->fetchAll("
                                                  SELECT CONCAT(nom,' ', prenom) as user
                                                  FROM CRM_EDICOM.`tts_utilisateur`
                                                  WHERE id = $id;
                                                ");

            
            return $this->render("EcommerceBundle:Administration:UtilisateursEspaceClient/tracabilite.html.twig",array('tracabilite' => $tracabilite, 'nom_commercial' => $nom_commercial));

        }else{

            return $this->redirect($this->generateUrl('connection'));

        }


    }

    public function createTokenAction(Request $request){

        /*$create_token = $request->get('create_token');*/

        $session=$request->getSession();

        $utilisateur = $session->get('utilisateur');
        $data_user   = $session->get('data_user');
        $source      = $session->get('source');

        
            $utilisateur_web_id     = $utilisateur[0]['id_user'];
            $utilisateur_web_email  = $utilisateur[0]['email'];
            $utilisateur_web_nom    = $utilisateur[0]['nom'];
            $utilisateur_web_prenom = $utilisateur[0]['prenom'];
            $utilisateur_profile    = $utilisateur[0]['profile'];
            if (($utilisateur_profile == "admin" or $utilisateur_profile == "commercial" or $utilisateur_profile == "resCommercial" or $utilisateur_profile == "Responsable") ? $utilisateur_code_comm  = $utilisateur[0]['code_commercial'] : $utilisateur_code_comm  = "");
            if (isset($utilisateur[0]['type']) ? $utilisateur_web_type = $utilisateur[0]['type'] : $utilisateur_web_type = "");
            if (isset($data_user[0]['code_firme']) ? $utilisateur_web_cfir = $data_user[0]['code_firme'] : $utilisateur_web_cfir = "");
            if (isset($utilisateur[0]['ville']) ? $utilisateur_web_ville = $utilisateur[0]['ville'] : $utilisateur_web_ville = "");
            

            $token = $utilisateur_web_id * 1256 . '&' . str_shuffle ('ala') . $utilisateur_web_email . '&' . $utilisateur_web_type . '&' . str_shuffle ('ele') . $utilisateur_web_nom .'&' . $utilisateur_web_prenom . '&' . $utilisateur_profile . '&' . $utilisateur_code_comm . str_shuffle('9876') . '&' . $utilisateur_web_cfir . '&' . $utilisateur_web_ville . '&' . $source;

            
            $response = base64_encode($token);

            return new JsonResponse($response);

    }

   


    

}