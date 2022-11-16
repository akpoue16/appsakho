<?php

namespace App\Controller;

use App\Entity\Typecolab;
use App\Form\TypecolabType;
use App\Repository\TypecolabRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/typecolab")
 */
class TypecolabController extends AbstractController
{
    /**
     * @Route("/", name="app_typecolab_index", methods={"GET"})
     */
    public function index(TypecolabRepository $typecolabRepository): Response
    {
        return $this->render('typecolab/index.html.twig', [
            'typecolabs' => $typecolabRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_typecolab_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TypecolabRepository $typecolabRepository): Response
    {
        $typecolab = new Typecolab();
        $form = $this->createForm(TypecolabType::class, $typecolab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typecolabRepository->add($typecolab, true);

            return $this->redirectToRoute('app_typecolab_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typecolab/new.html.twig', [
            'typecolab' => $typecolab,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_typecolab_show", methods={"GET"})
     */
    public function show(Typecolab $typecolab): Response
    {
        return $this->render('typecolab/show.html.twig', [
            'typecolab' => $typecolab,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_typecolab_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Typecolab $typecolab, TypecolabRepository $typecolabRepository): Response
    {
        $form = $this->createForm(TypecolabType::class, $typecolab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typecolabRepository->add($typecolab, true);

            return $this->redirectToRoute('app_typecolab_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typecolab/edit.html.twig', [
            'typecolab' => $typecolab,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_typecolab_delete", methods={"POST"})
     */
    public function delete(Request $request, Typecolab $typecolab, TypecolabRepository $typecolabRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typecolab->getId(), $request->request->get('_token'))) {
            $typecolabRepository->remove($typecolab, true);
            
        }

        return $this->redirectToRoute('app_typecolab_index', [], Response::HTTP_SEE_OTHER);
    }
}
