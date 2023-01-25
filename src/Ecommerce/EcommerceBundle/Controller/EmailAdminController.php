<?php


namespace Ecommerce\EcommerceBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections;

class EmailAdminController extends Controller
{


    public function emailByIdAction()
    {
     
        $em = $this->getDoctrine()->getManager();
        $by_tr=$this->container->get('security.context')->getToken()->getUser()->getId();
     
        if($by_tr != null)
        {
            $query = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
                ->where('t.utilisateur = :user')
                ->setParameter('user',$by_tr)
                ->getQuery()->getResult();
            return $this->render('EcommerceBundle:Administration:Mails/emailById.html.twig', array(
                'emailsbyid' => $query,
            ));
        }
        else
        {
            return $this->render('EcommerceBundle:Administration:Mails/emailById.html.twig');
        }
    }
    public function emailBygroupAction()
    {   
        ini_set('memory_limit', '-1');
        $em = $this->getDoctrine()->getManager();
        $by_tr=$this->container->get('security.context')->getToken()->getUser()->getId();
        if($by_tr == 4) {
            /*$entities = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('m')->select('m')
                 *->where($entities->in( 'm.utilisateur_id',
                         $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')->select('u')
                             ->where('u.groupe like  :group')
                             ->setParameter('group', '%Kilaouy%')
                         )
                ->orderBy('m.dateCreation', 'desc')
                ->getQuery()->getResult();)*/
            $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('u')->select('u.id')
                /*  ->groupBy('u.groupe')*/
                ->where('u.groupe like  :group')
                ->setParameter('group', '%Kilaouy%')
                ->getQuery()->getResult();
            /*var_dump($query);
            die();*/
            /*  $entities = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('u')->select(array('u'))*/
            /*->getOneOrNullResult()(' . implode(",", $query) . ')')*/
            /*->where('u.utilisateur = :user')
             ->setParameter('user', $query)*/
            /* ->where('u.utilisateur IN ('. $query.')')
             ->getQuery()->getResult();
             $returnArray = array();
             foreach ($entities as $entity) {
             $returnArray[get_class($entity)] = $entity;
             }
             /*  ->getResult();
foreach ($query as $entity) {
$returnArray[get_class($entity)] = $entity;


                }*/
            /* ->getQuery()->getResult();*/
            /*  var_dump($returnArray);
                die();*/



            $query = $em->createQuery('SELECT m FROM EcommerceBundle:Mails m WHERE m.utilisateur IN (SELECT u.id FROM UtilisateursBundle:Utilisateurs u WHERE u.groupe like \'%televente%\')');

            $devisbygroup = $this->get('knp_paginator')->paginate($query, $this->get('request')->query->get('page', 1), 9000000);
            return $this->render('EcommerceBundle:Administration:Group/emailByGroupCommercial.html.twig', array(
                'devisbygroup' => $devisbygroup,
            ));
        }



        else
            if($by_tr == 9) {
                $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                    ->where('t.groupe like  :group')
                    ->setParameter('group', '%Alami%')
                    ->getQuery()->getResult();
                $query_f = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
                    ->where('t.utilisateur in (:user)')
                    ->setParameter('user',$query)
                    ->orderBy('t.dateCreation', 'desc')
                    ->getQuery()->getResult();
                return $this->render('EcommerceBundle:Administration:Group/emailByGroupCommercial.html.twig', array(
                    'devisbygroup' => $query_f,
                ));
            }
            else
            if($by_tr == 77) {

               
                $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                    ->where('t.groupe like  :group')
                    ->setParameter('group', '%televente%')
                    ->getQuery()->getResult();
                $query_f = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
                    ->where('t.utilisateur in (:user)')
                    ->setParameter('user',$query)
                    ->orderBy('t.dateCreation', 'desc')
                    ->getQuery()->getResult();
                return $this->render('EcommerceBundle:Administration:Group/emailByGroupCommercial.html.twig', array(
                    'devisbygroup' => $query_f,
                ));
            }
            else
            if($by_tr == 52) {
                $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                    ->where('t.groupe like  :group')
                    ->setParameter('group', '%Kilaouy%')
                    ->getQuery()->getResult();

                $query_f = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
                    ->where('t.utilisateur in (:user)')
                    ->setParameter('user',$query)
                    ->orderBy('t.dateCreation', 'desc')
                    ->getQuery()->getResult();
                return $this->render('EcommerceBundle:Administration:Group/emailByGroupCommercial.html.twig', array(
                    'devisbygroup' => $query_f,
                ));
            }

            else
                if($by_tr == 25) {
                    $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                        ->where('t.groupe like  :group')
                        ->setParameter('group', '%Chraibi%')
                        ->getQuery()->getResult();
                    $query_f = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
                        ->where('t.utilisateur in (:user)')
                        ->setParameter('user',$query)
                        ->orderBy('t.dateCreation', 'desc')
                        ->getQuery()->getResult();
                    return $this->render('EcommerceBundle:Administration:Group/emailByGroupCommercial.html.twig', array(
                        'devisbygroup' => $query_f,
                    ));
                }
                else
                    if($by_tr == 32) {
                        $query = $em->getRepository('UtilisateursBundle:Utilisateurs')->createQueryBuilder('t')->select('t.id')
                            ->where('t.groupe like  :group')
                            ->setParameter('group', '%Benzahra%')
                            ->getQuery()->getResult();
                        $query_f = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
                            ->where('t.utilisateur in (:user)')
                            ->setParameter('user',$query)
                            ->orderBy('t.dateCreation', 'desc')
                            ->getQuery()->getResult();

                        return $this->render('EcommerceBundle:Administration:Group/emailByGroupCommercial.html.twig', array(
                            'devisbygroup' => $query_f,
                        ));
                    }

    }

    public function indexAction()
    {
        ini_set('memory_limit', '-1');

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('EcommerceBundle:Mails')->createQueryBuilder('t')->select('t')
            ->orderBy('t.dateCreation', 'desc')
            ->getQuery()->getResult();
        return $this->render('EcommerceBundle:Administration:Mails/index.html.twig', array(
            'emails' => $query,
        ));
    }
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:Mails')->find($id);
        $affichage = $entity->getAffichage();
        $referencement = $entity->getReferencement();
        $panier =$entity->getContenu();
        $paiement =$entity->getPaiement();
        $profession =$entity->getProfession();
        $desrubref=$entity->getDesrubref();
        $code=$entity->getCode();
        $marque=$entity->getMarque();
        $result =$entity->getResultat();
        return $this->render('EcommerceBundle:Default:produits/layout/contactd.html.twig', array('entity'=>$entity, 'referencement' => $referencement,'contenu' => $panier,'affichage' => $affichage,'paiement'=>$paiement,'somme'=>$result,'profession'=>$profession,'desrubref'=>$desrubref,'code'=>$code,'marque'=>$marque));

    }
    public function emailAction($id)
    {

        $this->container->get('setNewEmail')->contactsec($id)->Output('E-contact.pdf');
        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');
        return $response;

    }

}