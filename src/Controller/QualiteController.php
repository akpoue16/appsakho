<?php

namespace App\Controller;

use App\Entity\Qualite;
use App\Form\QualiteType;
use App\Repository\QualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_AVOCAT")
 * @Route("/qualite")
 */
class QualiteController extends AbstractController
{
    /**
     * @Route("/", name="app_qualite_index", methods={"GET"})
     */
    public function index(QualiteRepository $qualiteRepository): Response
    {
        return $this->render('qualite/index.html.twig', [
            'qualites' => $qualiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_qualite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, QualiteRepository $qualiteRepository): Response
    {
        $qualite = new Qualite();
        $form = $this->createForm(QualiteType::class, $qualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualiteRepository->add($qualite, true);

            return $this->redirectToRoute('app_qualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qualite/new.html.twig', [
            'qualite' => $qualite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_qualite_show", methods={"GET"})
     */
    public function show(Qualite $qualite): Response
    {
        return $this->render('qualite/show.html.twig', [
            'qualite' => $qualite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_qualite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Qualite $qualite, QualiteRepository $qualiteRepository): Response
    {
        $form = $this->createForm(QualiteType::class, $qualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualiteRepository->add($qualite, true);

            return $this->redirectToRoute('app_qualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qualite/edit.html.twig', [
            'qualite' => $qualite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_qualite_delete", methods={"POST"})
     */
    public function delete(Request $request, Qualite $qualite, QualiteRepository $qualiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$qualite->getId(), $request->request->get('_token'))) {
            $qualiteRepository->remove($qualite, true);
        }

        return $this->redirectToRoute('app_qualite_index', [], Response::HTTP_SEE_OTHER);
    }
}
