<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Audience;
use App\Form\AudienceType;
use App\Entity\Contentieux;
use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\AudienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContentieuxRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $user = $this->getUser();

        if ($user->getRoles() == 'ROLE_CLIENT') {
            return $this->render('audience/index.html.twig', [
                'audiences' => $audienceRepository->clientAudience($user),
            ]);
        }

        return $this->render('audience/index.html.twig', [
            'audiences' => $audienceRepository->findAll(),
            'clientAudiences' => $audienceRepository->clientAudience($user),
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
     * @Route("/contentieux/{id}", name="app_audience_contentieux", methods={"GET", "POST"})
     */
    public function newContentieux(Request $request, AudienceRepository $audienceRepository, ContentieuxRepository $contentieuxRepository, Contentieux $contentieux): Response
    {
        $audience = new Audience();
        $derniereaudience = new Audience();

        $form = $this->createForm(AudienceType::class, $audience, ['contentieux' => $contentieux]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $audience->setContentieux($contentieux);
            $audienceRepository->add($audience, true);

            return $this->redirectToRoute('app_audience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('audience/newcontentieux.html.twig', [
            'audience' => $audience,
            'derniereAudience' => $audienceRepository->findBy(['contentieux' => $contentieux], ['contentieux' => 'DESC'], 1, 1),
            'contentieux' => $contentieuxRepository->find($contentieux),
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
        if ($this->isCsrfTokenValid('delete' . $audience->getId(), $request->request->get('_token'))) {
            $audienceRepository->remove($audience, true);
        }

        return $this->redirectToRoute('app_audience_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/sup/{id}", name="audience_delete")
     */
    public function audiencedelete(Audience $audience, EntityManagerInterface $em)
    {
        if ($audience) {
            $em->remove($audience);
            $em->flush();

            $this->addFlash(
                'success',
                "L'audience <span class='font-weight-bold'>{$audience->getCode()} </span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_audience_index');
        }
    }

    /**
     * @Route("/imprimer/search-audience", name="search_audience_day")
     */
    public function index_imprimer(AudienceRepository $audienceRepository, Pdf $knpSnappyPdf, Request $request)
    {

        if ($request->isMethod('post')) {
            //$audienses = $request->request->all();
            $audiences = $request->request->get("search");
            //dd($audiences);


            $html = $this->renderView('audience/pdf/index.html.twig', [
                'audiences' => $audienceRepository->searchAudience($audiences)
            ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr', false, 'UTF-8');
            $html2pdf->setDefaultFont("Arial");
            $html2pdf->writeHTML($html);
            $html2pdf->output('search_audience.pdf');
        }
    }
}
