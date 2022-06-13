<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @Route("/hello/{name}", name="app_hello")
     */
    public function homepage(string $name = 'world'): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
