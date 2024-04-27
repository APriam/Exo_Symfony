<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCheck{
    public function checkSession(SessionInterface $session){
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('user_connexion');
        }
    }
}