<?php

namespace App\Controller;

use App\Entity\Nature;
use App\Form\NatureType;
use App\Repository\NatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nature")
 */
class NatureController extends AbstractController
{
    /**
     * @Route("/", name="app_nature_index", methods={"GET"})
     */
    public function index(NatureRepository $natureRepository): Response
    {
        return $this->render('nature/index.html.twig', [
            'natures' => $natureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_nature_new", methods={"GET", "POST"})
     */
    public function new(Request $request, NatureRepository $natureRepository): Response
    {
        $nature = new Nature();
        $form = $this->createForm(NatureType::class, $nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $natureRepository->add($nature, true);

            return $this->redirectToRoute('app_nature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nature/new.html.twig', [
            'nature' => $nature,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_nature_show", methods={"GET"})
     */
    public function show(Nature $nature): Response
    {
        return $this->render('nature/show.html.twig', [
            'nature' => $nature,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_nature_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Nature $nature, NatureRepository $natureRepository): Response
    {
        $form = $this->createForm(NatureType::class, $nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $natureRepository->add($nature, true);

            return $this->redirectToRoute('app_nature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nature/edit.html.twig', [
            'nature' => $nature,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_nature_delete", methods={"POST"})
     */
    public function delete(Request $request, Nature $nature, NatureRepository $natureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nature->getId(), $request->request->get('_token'))) {
            $natureRepository->remove($nature, true);
        }

        return $this->redirectToRoute('app_nature_index', [], Response::HTTP_SEE_OTHER);
    }
}
