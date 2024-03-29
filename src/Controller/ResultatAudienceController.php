<?php

namespace App\Controller;

use App\Entity\Audience;
use App\Entity\ResultatAudience;
use App\Form\ResultatAudienceType;
use App\Repository\AudienceRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ResultatAudienceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/resultat")
 */
class ResultatAudienceController extends AbstractController
{
    /**
     * @Route("/", name="app_resultat_audience_index", methods={"GET"})
     */
    public function index(ResultatAudienceRepository $resultatAudienceRepository): Response
    {
        return $this->render('resultat_audience/index.html.twig', [
            'resultat_audiences' => $resultatAudienceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_resultat_audience_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ResultatAudienceRepository $resultatAudienceRepository): Response
    {
        $resultatAudience = new ResultatAudience();
        $form = $this->createForm(ResultatAudienceType::class, $resultatAudience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resultatAudienceRepository->add($resultatAudience, true);

            return $this->redirectToRoute('app_resultat_audience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resultat_audience/new.html.twig', [
            'resultat_audience' => $resultatAudience,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/audience/{id}", name="app_resultat_audience", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function newAudience(Request $request, ResultatAudienceRepository $resultatAudienceRepository, AudienceRepository $audienceRepository, Audience $audience): Response
    {
        $resultatAudience = new ResultatAudience();

        $form = $this->createForm(ResultatAudienceType::class, $resultatAudience, ['audience' => $audience]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resultatAudience->setAudience($audience);
            $resultatAudienceRepository->add($resultatAudience, true);

            $this->addFlash(
                'success',
                "Le resultat N° <span class='font-weight-bold'>{$resultatAudience->getResultat()} de l'audience N° {$audience->getCode()}</span> a été enregistré avec succès"
            );
            return $this->redirectToRoute('app_audience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resultat_audience/newaudience.html.twig', [
            'resultat_audience' => $resultatAudience,
            'dernierResultat' => $resultatAudienceRepository->findBy(['audience' => $audience], ['audience' => 'DESC'], 1, 1),
            'audience' => $audienceRepository->find($audience),
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="app_resultat_audience_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(ResultatAudience $resultatAudience): Response
    {
        return $this->render('resultat_audience/show.html.twig', [
            'resultat_audience' => $resultatAudience,
        ]);
    }

    /**
     * @Route("/{audience_id}/{id}/edit", name="app_resultat_audience_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, int $audience_id, AudienceRepository $audienceRepository, ResultatAudience $resultatAudience, ResultatAudienceRepository $resultatAudienceRepository): Response
    {
        $audience = $audienceRepository->findById($audience_id);
        //dd($audience);
        $form = $this->createForm(ResultatAudienceType::class, $resultatAudience, ['audience' => $audience]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resultatAudienceRepository->add($resultatAudience, true);
            $this->addFlash(
                'success',
                "Le resultat de l'audience <span class='font-weight-bold'>N° {$resultatAudience->getAudience()->getCode()}</span> a bien été modifié"
            );
            return $this->redirectToRoute('app_audience_index');
        }

        return $this->renderForm('resultat_audience/edit.html.twig', [
            'resultat_audience' => $resultatAudience,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_resultat_audience_delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, ResultatAudience $resultatAudience, ResultatAudienceRepository $resultatAudienceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $resultatAudience->getId(), $request->request->get('_token'))) {
            $resultatAudienceRepository->remove($resultatAudience, true);
        }

        return $this->redirectToRoute('app_resultat_audience_index', [], Response::HTTP_SEE_OTHER);
    }
}
