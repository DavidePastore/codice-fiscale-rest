<?php

namespace DavidePastore\CodiceFiscaleRest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WebsiteController
 */
class WebsiteController extends AbstractController
{

    /**
     * Home page
     * @Route("/", name="homepage")
     */
    public function calculate(): Response
    {
        return $this->render('index.twig');
    }

    /**
     * Documentazione
     * @Route("/documentazione", name="documentazione")
     */
    public function documentazione(): Response
    {
        return $this->render('documentazione.twig');
    }

    /**
     * Prova
     * @Route("/prova", name="prova")
     */
    public function prova(): Response
    {
        return $this->render('prova.twig');
    }

}