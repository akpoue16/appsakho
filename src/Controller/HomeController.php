<?php

namespace App\Controller;

use App\Entity\Audience;
use App\Repository\ClientRepository;
use App\Repository\AudienceRepository;
use App\Repository\ContentieuxRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(AudienceRepository $audienceRepository, ContentieuxRepository $contentieuxRepository, ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();

        return $this->render('home/index.html.twig', [
            'contentieux' => $contentieuxRepository->findAll(),
            'clients' => $clientRepository->findAll(),
            'unContentieux' => $contentieuxRepository->findByClient($user),
            'audiences' => $audienceRepository->derniereAudience(),
            'unAudience' => $audienceRepository->clientAudience($user)
        ]);
    }
}
