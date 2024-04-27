<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NavController extends AbstractController
{
    public function navbar(): Response
    {
        return $this->render('nav/navbar.html.twig', [
        ]);
    }
}
