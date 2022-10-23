<?php

namespace App\Controller;

use App\Entity\Adversaire;
use App\Form\AdversaireType;
use App\Repository\AdversaireRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_AVOCAT")
 * @Route("/adversaire")
 */
class AdversaireController extends AbstractController
{
    /**
     * @Route("/", name="app_adversaire_index", methods={"GET"})
     */
    public function index(AdversaireRepository $adversaireRepository): Response
    {
        return $this->render('adversaire/index.html.twig', [
            'adversaires' => $adversaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_adversaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AdversaireRepository $adversaireRepository): Response
    {
        $adversaire = new Adversaire();
        $form = $this->createForm(AdversaireType::class, $adversaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adversaireRepository->add($adversaire, true);

            return $this->redirectToRoute('app_adversaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adversaire/new.html.twig', [
            'adversaire' => $adversaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adversaire_show", methods={"GET"})
     */
    public function show(Adversaire $adversaire): Response
    {
        return $this->render('adversaire/show.html.twig', [
            'adversaire' => $adversaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_adversaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Adversaire $adversaire, AdversaireRepository $adversaireRepository): Response
    {
        $form = $this->createForm(AdversaireType::class, $adversaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adversaireRepository->add($adversaire, true);

            return $this->redirectToRoute('app_adversaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adversaire/edit.html.twig', [
            'adversaire' => $adversaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adversaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Adversaire $adversaire, AdversaireRepository $adversaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adversaire->getId(), $request->request->get('_token'))) {
            $adversaireRepository->remove($adversaire, true);
        }

        return $this->redirectToRoute('app_adversaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
