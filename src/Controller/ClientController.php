<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Client;
use App\Entity\PasswordUpdate;
use App\Form\ClientType;
use App\Form\PasswordUpdateType;
use Spipu\Html2Pdf\Html2Pdf;
use App\Repository\ClientRepository;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/", name="app_client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/new", name="app_client_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClientRepository $clientRepository,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //création du roles
            $client->setRoles(["ROLE_CLIENT"]);
            
            //Création de mot de passe
            $mdp = 'password';
            $hashedPassword = $passwordHasher->hashPassword($client, $mdp);
            $client->setPassword($hashedPassword);

            $clientRepository->add($client, true);

            $this->addFlash(
                'success',
                "Le client <strong>{$client->getNom()} {$client->getPrenom()}</strong> a bien été enregistré!"
            );

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_client_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show(Client $client, DossierRepository $dossierRepository): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
            'dossiers' => $dossierRepository->findByClient($client)
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_client_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client, true);

            $this->addFlash(
                'success',
                "Le client <strong>{$client->getNom()} {$client->getPrenom()}</strong> a bien été modifié !"
            );

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/sup/{id}", name="client_delete")
     */
    public function clientdelete(Client $client, EntityManagerInterface $em)
    {
        if ($client) {
            $em->remove($client);
            $em->flush();

            $this->addFlash(
                'success',
                "Le client <span class='font-weight-bold'>{$client->getNom()}</span> a été supprimé avec succés"
            );
            return $this->redirectToRoute('app_client_index');
        }
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/{id}", name="app_client_delete", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/imprimer/liste-client", name="index_imprimer_client")
     */
    public function index_imprimer(ClientRepository $clientRepository, Pdf $knpSnappyPdf)
    {

        $html = $this->renderView('client/pdf/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', false, 'UTF-8');
        $html2pdf->setDefaultFont("Arial");
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste_clients.pdf');
    }

    /**
     * @IsGranted("ROLE_AVOCAT")
     * @Route("/excel/liste-client", name="index_excel_client")
     */
    public function index_excel(ClientRepository $clientRepository)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $clients = $clientRepository->findAll();

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

    /**
     * @IsGranted("ROLE_CLIENT")
     * @Route("/profil", name="client_profil")
     */
    public function profil(): Response
    {
        $user = $this->getUser();

        return $this->render('client/compte/profil.html.twig', [
            'client' => $user,
        ]);
    }
    
    /**
     * @IsGranted("ROLE_CLIENT")
     * @Route("/profil/password", name="client_password")
     */
    public function password(): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        return $this->render('client/compte/modif_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
