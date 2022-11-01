<?php

namespace App\Controller;

use App\Entity\Audience;
use App\Form\AudienceType;
use App\Repository\AudienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/audience")
 */
class AudienceController extends AbstractController
{
    /**
     * @Route("/", name="app_audience_index", methods={"GET"})
     */
    public function index(AudienceRepository $audienceRepository): Response
    {
        return $this->render('audience/index.html.twig', [
            'audiences' => $audienceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_audience_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AudienceRepository $audienceRepository): Response
    {
        $audience = new Audience();
        $form = $this->createForm(AudienceType::class, $audience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $audienceRepository->add($audience, true);

            return $this->redirectToRoute('app_audience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('audience/new.html.twig', [
            'audience' => $audience,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_audience_show", methods={"GET"})
     */
    public function show(Audience $audience): Response
    {
        return $this->render('audience/show.html.twig', [
            'audience' => $audience,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_audience_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Audience $audience, AudienceRepository $audienceRepository): Response
    {
        $form = $this->createForm(AudienceType::class, $audience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $audienceRepository->add($audience, true);

            return $this->redirectToRoute('app_audience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('audience/edit.html.twig', [
            'audience' => $audience,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_audience_delete", methods={"POST"})
     */
    public function delete(Request $request, Audience $audience, AudienceRepository $audienceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$audience->getId(), $request->request->get('_token'))) {
            $audienceRepository->remove($audience, true);
        }

        return $this->redirectToRoute('app_audience_index', [], Response::HTTP_SEE_OTHER);
    }
}
