<?php

namespace App\Controller;

use App\Entity\Tribunal;
use App\Form\TribunalType;
use App\Repository\TribunalRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_AVOCAT")
 * @Route("/tribunal")
 */
class TribunalController extends AbstractController
{
    /**
     * @Route("/", name="app_tribunal_index", methods={"GET"})
     */
    public function index(TribunalRepository $tribunalRepository): Response
    {
        return $this->render('tribunal/index.html.twig', [
            'tribunals' => $tribunalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_tribunal_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TribunalRepository $tribunalRepository): Response
    {
        $tribunal = new Tribunal();
        $form = $this->createForm(TribunalType::class, $tribunal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tribunalRepository->add($tribunal, true);

            return $this->redirectToRoute('app_tribunal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tribunal/new.html.twig', [
            'tribunal' => $tribunal,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tribunal_show", methods={"GET"})
     */
    public function show(Tribunal $tribunal): Response
    {
        return $this->render('tribunal/show.html.twig', [
            'tribunal' => $tribunal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tribunal_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tribunal $tribunal, TribunalRepository $tribunalRepository): Response
    {
        $form = $this->createForm(TribunalType::class, $tribunal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tribunalRepository->add($tribunal, true);

            return $this->redirectToRoute('app_tribunal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tribunal/edit.html.twig', [
            'tribunal' => $tribunal,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tribunal_delete", methods={"POST"})
     */
    public function delete(Request $request, Tribunal $tribunal, TribunalRepository $tribunalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tribunal->getId(), $request->request->get('_token'))) {
            $tribunalRepository->remove($tribunal, true);
        }

        return $this->redirectToRoute('app_tribunal_index', [], Response::HTTP_SEE_OTHER);
    }
}
