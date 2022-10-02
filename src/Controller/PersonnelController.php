<?php

namespace App\Controller;

use App\Entity\Personnel;
use App\Form\PersonnelType;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/personnel")
 */
class PersonnelController extends AbstractController
{
    /**
     * @Route("/", name="app_personnel_index", methods={"GET"})
     */
    public function index(PersonnelRepository $personnelRepository): Response
    {
        return $this->render('personnel/index.html.twig', [
            'personnels' => $personnelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_personnel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PersonnelRepository $personnelRepository,UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $personnel = new Personnel();
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personnel->setImage($newFilename);
            }

            $mdp = 'password';

           $hashedPassword = $passwordHasher->hashPassword($personnel, $mdp);
           $personnel->setPassword($hashedPassword);

            $personnelRepository->add($personnel, true);

            return $this->redirectToRoute('app_personnel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personnel/new.html.twig', [
            'personnel' => $personnel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_personnel_show", methods={"GET"})
     */
    public function show(Personnel $personnel): Response
    {
        return $this->render('personnel/show.html.twig', [
            'personnel' => $personnel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_personnel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Personnel $personnel, PersonnelRepository $personnelRepository, SluggerInterface $slugger): Response
    {   //Si l'image n'est pas modifié, on conserve le nom de l'image qui existe
        $imageName = $personnel->getImage();

        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personnel->setImage($newFilename);
            }else{
               $personnel->setImage($imageName);
            }
          
            $personnelRepository->add($personnel, true);

            return $this->redirectToRoute('app_personnel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personnel/edit.html.twig', [
            'personnel' => $personnel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/sup/{id}", name="personnel_delete")
     */
    public function personneldelete(Personnel $personnel, EntityManagerInterface $em)
    {
        if($personnel){
             $em->remove($personnel);
             $em->flush();

             $this->addFlash('success',
             "Le collaborateur <span class='font-weight-bold'>{$personnel->getNom()}</span> a été supprimé avec succés");
           return $this->redirectToRoute('app_personnel_index');
        }
    }

    /**
     * @Route("/{id}", name="app_personnel_delete", methods={"POST"})
     */
    public function delete(Request $request, Personnel $personnel, PersonnelRepository $personnelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personnel->getId(), $request->request->get('_token'))) {
            $personnelRepository->remove($personnel, true);
        }

        return $this->redirectToRoute('app_personnel_index', [], Response::HTTP_SEE_OTHER);
    }
}
