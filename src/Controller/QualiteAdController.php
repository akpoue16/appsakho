<?php

namespace App\Controller;

use App\Entity\QualiteAd;
use App\Form\QualiteAdType;
use App\Repository\QualiteAdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/qualite/ad")
 */
class QualiteAdController extends AbstractController
{
    /**
     * @Route("/", name="app_qualite_ad_index", methods={"GET"})
     */
    public function index(QualiteAdRepository $qualiteAdRepository): Response
    {
        return $this->render('qualite_ad/index.html.twig', [
            'qualite_ads' => $qualiteAdRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_qualite_ad_new", methods={"GET", "POST"})
     */
    public function new(Request $request, QualiteAdRepository $qualiteAdRepository): Response
    {
        $qualiteAd = new QualiteAd();
        $form = $this->createForm(QualiteAdType::class, $qualiteAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualiteAdRepository->add($qualiteAd, true);

            return $this->redirectToRoute('app_qualite_ad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qualite_ad/new.html.twig', [
            'qualite_ad' => $qualiteAd,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_qualite_ad_show", methods={"GET"})
     */
    public function show(QualiteAd $qualiteAd): Response
    {
        return $this->render('qualite_ad/show.html.twig', [
            'qualite_ad' => $qualiteAd,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_qualite_ad_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, QualiteAd $qualiteAd, QualiteAdRepository $qualiteAdRepository): Response
    {
        $form = $this->createForm(QualiteAdType::class, $qualiteAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qualiteAdRepository->add($qualiteAd, true);

            return $this->redirectToRoute('app_qualite_ad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qualite_ad/edit.html.twig', [
            'qualite_ad' => $qualiteAd,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_qualite_ad_delete", methods={"POST"})
     */
    public function delete(Request $request, QualiteAd $qualiteAd, QualiteAdRepository $qualiteAdRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $qualiteAd->getId(), $request->request->get('_token'))) {
            $qualiteAdRepository->remove($qualiteAd, true);
        }

        return $this->redirectToRoute('app_qualite_ad_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/sup/{id}", name="qualite_ad_delete")
     */
    public function qualitedelete(QualiteAd $qualitead, EntityManagerInterface $em)
    {
        if ($qualitead) {
            $em->remove($qualitead);
            $em->flush();

            $this->addFlash(
                'success',
                "La qualité adversaire <span class='font-weight-bold'>{$qualitead->getTitre()}</span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_qualite_ad_index');
        }
    }
}
