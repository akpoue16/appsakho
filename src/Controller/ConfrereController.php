<?php

namespace App\Controller;

use App\Entity\Confrere;
use App\Form\ConfrereType;
use App\Repository\ConfrereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/confrere")
 */
class ConfrereController extends AbstractController
{
    /**
     * @Route("/", name="app_confrere_index", methods={"GET"})
     */
    public function index(ConfrereRepository $confrereRepository): Response
    {
        return $this->render('confrere/index.html.twig', [
            'confreres' => $confrereRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_confrere_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ConfrereRepository $confrereRepository): Response
    {
        $confrere = new Confrere();
        $form = $this->createForm(ConfrereType::class, $confrere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confrereRepository->add($confrere, true);

            return $this->redirectToRoute('app_confrere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('confrere/new.html.twig', [
            'confrere' => $confrere,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_confrere_show", methods={"GET"})
     */
    public function show(Confrere $confrere): Response
    {
        return $this->render('confrere/show.html.twig', [
            'confrere' => $confrere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_confrere_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Confrere $confrere, ConfrereRepository $confrereRepository): Response
    {
        $form = $this->createForm(ConfrereType::class, $confrere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confrereRepository->add($confrere, true);

            return $this->redirectToRoute('app_confrere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('confrere/edit.html.twig', [
            'confrere' => $confrere,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_confrere_delete", methods={"POST"})
     */
    public function delete(Request $request, Confrere $confrere, ConfrereRepository $confrereRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$confrere->getId(), $request->request->get('_token'))) {
            $confrereRepository->remove($confrere, true);
        }

        return $this->redirectToRoute('app_confrere_index', [], Response::HTTP_SEE_OTHER);
    }
}
