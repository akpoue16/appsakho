<?php

namespace App\Controller;

use App\Entity\Diligence;
use App\Form\DiligenceType;
use App\Repository\DiligenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/diligence")
 */
class DiligenceController extends AbstractController
{
    /**
     * @Route("/", name="app_diligence_index", methods={"GET"})
     */
    public function index(DiligenceRepository $diligenceRepository): Response
    {
        return $this->render('diligence/index.html.twig', [
            'diligences' => $diligenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_diligence_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DiligenceRepository $diligenceRepository): Response
    {
        $diligence = new Diligence();
        $form = $this->createForm(DiligenceType::class, $diligence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diligenceRepository->add($diligence, true);

            return $this->redirectToRoute('app_diligence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('diligence/new.html.twig', [
            'diligence' => $diligence,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_diligence_show", methods={"GET"})
     */
    public function show(Diligence $diligence): Response
    {
        return $this->render('diligence/show.html.twig', [
            'diligence' => $diligence,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_diligence_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Diligence $diligence, DiligenceRepository $diligenceRepository): Response
    {
        $form = $this->createForm(DiligenceType::class, $diligence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diligenceRepository->add($diligence, true);

            return $this->redirectToRoute('app_diligence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('diligence/edit.html.twig', [
            'diligence' => $diligence,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_diligence_delete", methods={"POST"})
     */
    public function delete(Request $request, Diligence $diligence, DiligenceRepository $diligenceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diligence->getId(), $request->request->get('_token'))) {
            $diligenceRepository->remove($diligence, true);
        }

        return $this->redirectToRoute('app_diligence_index', [], Response::HTTP_SEE_OTHER);
    }
}
