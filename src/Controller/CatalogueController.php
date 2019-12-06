<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{


    /**
     * @Route("/")
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue()
    {
        return $this->render('catalogue/index.html.twig');
    }
}
