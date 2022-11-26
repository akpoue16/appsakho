<?php

namespace App\Controller;

use App\Entity\Audience;
use App\Entity\Contentieux;
use App\Form\ContentieuxType;
use App\Repository\AudienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContentieuxRepository;
use App\Repository\DiligenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contentieux")
 */
class ContentieuxController extends AbstractController
{
    /**
     * @Route("/", name="app_contentieux_index", methods={"GET"})
     */
    public function index(ContentieuxRepository $contentieuxRepository): Response
    {
        return $this->render('contentieux/index.html.twig', [
            'contentieuxes' => $contentieuxRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_contentieux_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ContentieuxRepository $contentieuxRepository): Response
    {
        $contentieux = new Contentieux();
        $form = $this->createForm(ContentieuxType::class, $contentieux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contentieuxRepository->add($contentieux, true);

            return $this->redirectToRoute('app_contentieux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contentieux/new.html.twig', [
            'contentieux' => $contentieux,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contentieux_show", methods={"GET"})
     */
    public function show(Contentieux $contentieux, AudienceRepository $audienceRepository, DiligenceRepository $diligenceRepository): Response
    {
        return $this->render('contentieux/show.html.twig', [
            'contentieux' => $contentieux,
            'audiences' => $audienceRepository->findBy(['contentieux' => $contentieux], ['createdAt' => 'DESC']),
            'diligences' => $diligenceRepository->findBy(['contentieux' => $contentieux], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contentieux_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contentieux $contentieux, ContentieuxRepository $contentieuxRepository): Response
    {
        $form = $this->createForm(ContentieuxType::class, $contentieux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contentieuxRepository->add($contentieux, true);

            return $this->redirectToRoute('app_contentieux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contentieux/edit.html.twig', [
            'contentieux' => $contentieux,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contentieux_delete", methods={"POST"})
     */
    public function delete(Request $request, Contentieux $contentieux, ContentieuxRepository $contentieuxRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contentieux->getId(), $request->request->get('_token'))) {
            $contentieuxRepository->remove($contentieux, true);
        }

        return $this->redirectToRoute('app_contentieux_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/sup/{id}", name="contentieux_delete")
     */
    public function clientdelete(Contentieux $contentieux, EntityManagerInterface $em)
    {
        if ($contentieux) {
            $em->remove($contentieux);
            $em->flush();

            $this->addFlash(
                'success',
                "Le contentieux N° <span class='font-weight-bold'>{$contentieux->getCode()} de {$contentieux->getClient()->getfullName()}</span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_contentieux_index');
        }
    }
}
