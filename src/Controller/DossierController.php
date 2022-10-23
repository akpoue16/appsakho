<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Dossier;
use App\Form\DossierType;
use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dossier")
 */
class DossierController extends AbstractController
{
    /**
     * @Route("/", name="app_dossier_index", methods={"GET"})
     */
    public function index(DossierRepository $dossierRepository): Response
    {
        $user = $this->getUser();

        if(in_array('ROLE_CLIENT', $user->getRoles())){
            return $this->render('dossier/index.html.twig', [
                'dossiers' => $dossierRepository->findByClient($user),
            ]); 
        }
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossierRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_dossier_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DossierRepository $dossierRepository): Response
    {
        $dossier = new Dossier();
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierRepository->add($dossier, true);

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossier/new.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_dossier_show", methods={"GET"})
     */
    public function show(Dossier $dossier): Response
    {
        return $this->render('dossier/show.html.twig', [
            'dossier' => $dossier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_dossier_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Dossier $dossier, DossierRepository $dossierRepository): Response
    {
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierRepository->add($dossier, true);

            return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossier/edit.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/sup/{id}", name="dossier_delete")
     */
    public function dossierdelete(Dossier $dossier, EntityManagerInterface $em)
    {
        if($dossier){
             $em->remove($dossier);
             $em->flush();

             $this->addFlash('success',
             "Le collaborateur <span class='font-weight-bold'>{$dossier->getNom()}</span> a été supprimé avec succés");
           return $this->redirectToRoute('app_dossier_index');
        }
    }

    /**
     * @Route("/{id}", name="app_dossier_delete", methods={"POST"})
     */
    public function delete(Request $request, Dossier $dossier, DossierRepository $dossierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossier->getId(), $request->request->get('_token'))) {
            $dossierRepository->remove($dossier, true);
        }

        return $this->redirectToRoute('app_dossier_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/imprimer/liste-dossier", name="index_imprimer_dossier")
     */
    public function index_imprimer(DossierRepository $dossierRepository, Pdf $knpSnappyPdf)
    {
        
        $html = $this->renderView('dossier/pdf/index.html.twig', [
            'dossiers' => $dossierRepository->findAll(),
        ]);
       
        $html2pdf = new Html2Pdf('P','A4','fr', false, 'UTF-8');
        $html2pdf->setDefaultFont("Arial");
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste_collaborateurs.pdf');
    }

    /**
     * @Route("/excel/liste-dossier", name="index_excel_dossier")
     */
    public function index_excel(DossierRepository $dossierRepository)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $dossiers = $dossierRepository->findAll();

        $sheet->setCellValue('A1', 'N°');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Date de création');
        $sheet->setCellValue('D1', 'Date de fermeture');
        $sheet->setCellValue('E1', 'Commentaire');

        $next = 2;
        foreach($dossiers as $dossier)
        {
            $sheet->setCellValue('A'. $next, $dossier->getId());
            $sheet->setCellValue('B'. $next, $dossier->getNom());
            $sheet->setCellValue('C'. $next, $dossier->getCreatedAt());
            $sheet->setCellValue('D'. $next, $dossier->getUpdateAt());
            $sheet->setCellValue('E'. $next, $dossier->getCommentaire());

            $next++;
        }

        $sheet->setTitle("Liste Dossier");
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        
        // Create a Temporary file in the system
        $fileName = 'liste_dossiers.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);
        
        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
