<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user instanceof \Applisun\CompteBundle\Entity\User)
        {
            return $this->render('ApplisunCompteBundle:Default:index.html.twig');
        }
        else{
            $comptes = $user->getComptes();
            return $this->render('ApplisunCompteBundle:Default:index.html.twig', array('nom' => $user->getUsername(), 'comptes' => $comptes));
        }
    }
    
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
 
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
 
        return $this->render('ApplisunCompteBundle:Default:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
}
