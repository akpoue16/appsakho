<?php

namespace App\Controller;

use App\Entity\TypePerso;
use App\Form\TypePersoType;
use App\Repository\TypePersoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/type/perso")
 */
class TypePersoController extends AbstractController
{
    /**
     * @Route("/", name="app_typeperso_index", methods={"GET"})
     */
    public function index(TypePersoRepository $typePersoRepository): Response
    {
        return $this->render('type_perso/index.html.twig', [
            'type_persos' => $typePersoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_typeperso_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TypePersoRepository $typePersoRepository): Response
    {
        $typePerso = new TypePerso();
        $form = $this->createForm(TypePersoType::class, $typePerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typePersoRepository->add($typePerso, true);

            return $this->redirectToRoute('app_typeperso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_perso/new.html.twig', [
            'type_perso' => $typePerso,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_typeperso_show", methods={"GET"})
     */
    public function show(TypePerso $typePerso): Response
    {
        return $this->render('type_perso/show.html.twig', [
            'type_perso' => $typePerso,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_typeperso_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypePerso $typePerso, TypePersoRepository $typePersoRepository): Response
    {
        $form = $this->createForm(TypePersoType::class, $typePerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typePersoRepository->add($typePerso, true);

            return $this->redirectToRoute('app_typeperso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_perso/edit.html.twig', [
            'type_perso' => $typePerso,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_typeperso_delete", methods={"POST"})
     */
    public function delete(Request $request, TypePerso $typePerso, TypePersoRepository $typePersoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typePerso->getId(), $request->request->get('_token'))) {
            $typePersoRepository->remove($typePerso, true);
        }

        return $this->redirectToRoute('app_typeperso_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/sup/{id}", name="typeperso_delete")
     */
    public function qualitedelete(TypePerso $typePerso, EntityManagerInterface $em)
    {
        if ($typePerso) {
            $em->remove($typePerso);
            $em->flush();

            $this->addFlash(
                'success',
                "La Fonction <span class='font-weight-bold'>{$typePerso->getNom()}</span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_typeperso_index');
        }
    }
}
