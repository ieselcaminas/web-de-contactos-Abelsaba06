<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class PageController extends AbstractController
{
    #[Route('/',name:'inicio')]
    public function inicio():Response 
    {
        return $this->render('index.html.twig');
    }
}