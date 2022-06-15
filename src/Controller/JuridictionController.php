<?php

namespace App\Controller;

use App\Entity\Juridiction;
use App\Form\JuridictionType;
use App\Repository\JuridictionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/juridiction")
 */
class JuridictionController extends AbstractController
{
    /**
     * @Route("/", name="app_juridiction_index", methods={"GET"})
     */
    public function index(JuridictionRepository $juridictionRepository): Response
    {
        return $this->render('juridiction/index.html.twig', [
            'juridictions' => $juridictionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_juridiction_new", methods={"GET", "POST"})
     */
    public function new(Request $request, JuridictionRepository $juridictionRepository): Response
    {
        $juridiction = new Juridiction();
        $form = $this->createForm(JuridictionType::class, $juridiction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $juridictionRepository->add($juridiction, true);

            return $this->redirectToRoute('app_juridiction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('juridiction/new.html.twig', [
            'juridiction' => $juridiction,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_juridiction_show", methods={"GET"})
     */
    public function show(Juridiction $juridiction): Response
    {
        return $this->render('juridiction/show.html.twig', [
            'juridiction' => $juridiction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_juridiction_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Juridiction $juridiction, JuridictionRepository $juridictionRepository): Response
    {
        $form = $this->createForm(JuridictionType::class, $juridiction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $juridictionRepository->add($juridiction, true);

            return $this->redirectToRoute('app_juridiction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('juridiction/edit.html.twig', [
            'juridiction' => $juridiction,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_juridiction_delete", methods={"POST"})
     */
    public function delete(Request $request, Juridiction $juridiction, JuridictionRepository $juridictionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$juridiction->getId(), $request->request->get('_token'))) {
            $juridictionRepository->remove($juridiction, true);
        }

        return $this->redirectToRoute('app_juridiction_index', [], Response::HTTP_SEE_OTHER);
    }
}
