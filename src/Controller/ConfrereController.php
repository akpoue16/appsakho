<?php

namespace App\Controller;

use App\Entity\Confrere;
use App\Form\ConfrereType;
use Spipu\Html2Pdf\Html2Pdf;

use App\Repository\ConfrereRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_AVOCAT")
 * @Route("/confrere")
 */
class ConfrereController extends AbstractController
{
    /**
     * @Route("/", name="app_confrere_index", methods={"GET"})
     */
    public function index(ConfrereRepository $confrereRepository): Response
    {
        return $this->render('confrere/index.html.twig', [
            'confreres' => $confrereRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_confrere_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ConfrereRepository $confrereRepository): Response
    {
        $confrere = new Confrere();
        $form = $this->createForm(ConfrereType::class, $confrere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confrereRepository->add($confrere, true);

            return $this->redirectToRoute('app_confrere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('confrere/new.html.twig', [
            'confrere' => $confrere,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_confrere_show", methods={"GET"})
     */
    public function show(Confrere $confrere): Response
    {
        return $this->render('confrere/show.html.twig', [
            'confrere' => $confrere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_confrere_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Confrere $confrere, ConfrereRepository $confrereRepository): Response
    {
        $form = $this->createForm(ConfrereType::class, $confrere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $confrereRepository->add($confrere, true);

            return $this->redirectToRoute('app_confrere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('confrere/edit.html.twig', [
            'confrere' => $confrere,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_confrere_delete", methods={"POST"})
     */
    public function delete(Request $request, Confrere $confrere, ConfrereRepository $confrereRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $confrere->getId(), $request->request->get('_token'))) {
            $confrereRepository->remove($confrere, true);
        }

        return $this->redirectToRoute('app_confrere_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/sup/{id}", name="confrere_delete")
     */
    public function clientdelete(Confrere $confrere, EntityManagerInterface $em)
    {
        if ($confrere) {
            $em->remove($confrere);
            $em->flush();

            $this->addFlash(
                'success',
                "Le client <span class='font-weight-bold'>{$confrere->getTitre()} {$confrere->getNom()}</span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_confrere_index');
        }
    }


    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/imprimer/liste-confrere", name="index_imprimer_confrere")
     */
    public function index_imprimer(ConfrereRepository $confrereRepository)
    {

        $html = $this->renderView('client/pdf/index.html.twig', [
            'confreres' => $confrereRepository->findAll(),
        ]);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', false, 'UTF-8');
        $html2pdf->setDefaultFont("Arial");
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste_clients.pdf');
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/excel/liste-confrere", name="index_excel_confrere")
     */
    public function index_excel(ConfrereRepository $confrereRepository)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $clients = $confrereRepository->findAll();

        $sheet->setCellValue('A1', 'N°');
        $sheet->setCellValue('B1', 'Titre');
        $sheet->setCellValue('C1', 'Nom');
        $sheet->setCellValue('D1', 'Prenoms');
        $sheet->setCellValue('E1', 'Telephone 1');
        $sheet->setCellValue('F1', 'Telephone 2');
        $sheet->setCellValue('G1', 'Email');

        $next = 2;
        foreach ($clients as $client) {
            $sheet->setCellValue('A' . $next, $client->getId());
            $sheet->setCellValue('B' . $next, $client->getTitre());
            $sheet->setCellValue('C' . $next, $client->getNom());
            $sheet->setCellValue('D' . $next, $client->getPrenom());
            $sheet->setCellValue('E' . $next, $client->getTel());
            $sheet->setCellValue('F' . $next, $client->getCel());
            $sheet->setCellValue('G' . $next, $client->getEmail());

            $next++;
        }

        $sheet->setTitle("Liste Client");
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'liste_client.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
