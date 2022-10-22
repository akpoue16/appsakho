<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\DossierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(DossierRepository $dossierRepository, ClientRepository $clientRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'dossiers' => $dossierRepository->findAll(),
            'clients' => $clientRepository->findAll(),
        ]);
    }
    
}
