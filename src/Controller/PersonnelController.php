<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Personnel;
use App\Form\PersonnelType;
use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @IsGranted("ROLE_AVOCAT")
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
    public function new(Request $request, PersonnelRepository $personnelRepository, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $personnel = new Personnel();
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personnel->setRoles(["ROLE_AVOCAT"]);
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

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

            $this->addFlash(
                'success',
                "Le client <strong>{$personnel->getNom()} {$personnel->getPrenom()}</strong> a bien été enregistré!"
            );

            return $this->redirectToRoute('app_personnel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personnel/new.html.twig', [
            'personnel' => $personnel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/modal", name="app_avocat_modal", methods="POST")
     */
    public function modal(Request $request, PersonnelRepository $personnelRepository): Response
    {
        $avocat = new Personnel();
        $form = $this->createForm(PersonnelType::class, $avocat);
        $form->handleRequest($request);

        $personnelRepository->add($avocat, true);

        return $this->json([
            'code' => 200,
            'message' => 'OK',
            'avocat' => $avocat,
        ], 200);
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
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

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
            } else {
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
        if ($personnel) {
            $em->remove($personnel);
            $em->flush();

            $this->addFlash(
                'success',
                "Le collaborateur <span class='font-weight-bold'>{$personnel->getNom()}</span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_personnel_index');
        }
    }

    /**
     * @Route("/{id}", name="app_personnel_delete", methods={"POST"})
     */
    public function delete(Request $request, Personnel $personnel, PersonnelRepository $personnelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $personnel->getId(), $request->request->get('_token'))) {
            $personnelRepository->remove($personnel, true);
        }

        return $this->redirectToRoute('app_personnel_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/imprimer/liste-collaborateur", name="index_imprimer")
     */
    public function index_imprimer(PersonnelRepository $personnelRepository, Pdf $knpSnappyPdf)
    {

        $html = $this->renderView('personnel/pdf/index.html.twig', [
            'personnels' => $personnelRepository->findAll(),
        ]);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', false, 'UTF-8');
        $html2pdf->setDefaultFont("Arial");
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste_collaborateurs.pdf');
    }

    /**
     * @Route("/excel/liste-collaborateur", name="index_excel")
     */
    public function index_excel(PersonnelRepository $personnelRepository)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $personnels = $personnelRepository->findAll();

        $sheet->setCellValue('A1', 'N°');
        $sheet->setCellValue('B1', 'Image');
        $sheet->setCellValue('C1', 'Titre');
        $sheet->setCellValue('D1', 'Nom');
        $sheet->setCellValue('E1', 'Prenoms');
        $sheet->setCellValue('F1', 'Telephone 1');
        $sheet->setCellValue('G1', 'Telephone 2');
        $sheet->setCellValue('H1', 'Email');

        $next = 2;
        foreach ($personnels as $personnel) {
            $sheet->setCellValue('A' . $next, $personnel->getId());
            $sheet->setCellValue('B' . $next, $personnel->getImage());
            $sheet->setCellValue('C' . $next, $personnel->getTitre());
            $sheet->setCellValue('D' . $next, $personnel->getNom());
            $sheet->setCellValue('E' . $next, $personnel->getPrenom());
            $sheet->setCellValue('F' . $next, $personnel->getTel());
            $sheet->setCellValue('G' . $next, $personnel->getCel());
            $sheet->setCellValue('H' . $next, $personnel->getEmail());

            $next++;
        }

        $sheet->setTitle("Liste Collaborateur");
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'liste_collaborateurs.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
