<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\PhotoProfil;
use App\Form\DocumentType;
use App\Form\PhotoType;

use App\Repository\DocumentRepository;
use App\Repository\PhotoProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class DocumentController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return Response
     */
    #[Route('profil/add/document', name: 'add_document')]
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, DocumentRepository $documentRepository, PhotoProfilRepository $photoProfilRepository): Response
    {
        $document = new Document();
        $formAddDocument = $this->createForm(DocumentType::class,$document);
        $formAddDocument->handleRequest($request);
        $photoNumber=count($photoProfilRepository->findBy(['user'=>$this->getUser()]));
        $docNumber=count($documentRepository->findBy(['proprietaire'=>$this->getUser()]));

        if($formAddDocument->isSubmitted() && $formAddDocument->isValid()) {
            $userDocument=$formAddDocument->get('filename')->getData();
            if ($userDocument) {
                $originalFilename=pathinfo($userDocument->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename=$safeFilename.'-'.uniqid($this->getUser()->getPrenom(),true).'.'.$userDocument->guessExtension();

                try {
                    $userDocument->move(
                        $this->getParameter('document_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   $this->addFlash('ErrorUploadDocument','Il y eu une erreur lors de l\'upload du document, veuillez réessayer.');
                    $this->redirectToRoute('add_document');
                }
                
                $document->setFilename($newFilename);
            }
            $user=$this->getUser();
            $isAlreadyDocument = $user->getDocuments();
            if(count($isAlreadyDocument)>=2) {
                $this->addFlash('alreadyDocs','Seulement deux documents peuvent être enregistrés.');
                $this->redirectToRoute('add_document');
            } else {
                $document->setProprietaire($user);
                $entityManager->persist($document);
                $entityManager->flush();
                $docNumber=count($documentRepository->findBy(['proprietaire'=>$user]));
                $this->addFlash('successDoc','Document bien enregistré.');
                $this->redirectToRoute('add_document');
            }
        } elseif ($formAddDocument->isSubmitted() && !$formAddDocument->isValid()){
            $this->addFlash('errorDoc','Document pas valide.');
            $this->redirectToRoute('add_document');
        }

        $formAddPhoto = $this->createForm(PhotoType::class);
        $formAddPhoto->handleRequest($request);

        if($formAddPhoto->isSubmitted() && $formAddPhoto->isValid()) {
            $photo=$formAddPhoto->get('imageName')->getData();

            $fileImage = md5(uniqid()).'.'.$photo->guessExtension();
            $photo->move(
                $this->getParameter('photo_directory'),
                $fileImage
            );

            $photoProfil= new PhotoProfil();
            $photoProfil->setFilename($fileImage);

            $userPhoto=$this->getUser()->getFilename();
            if ($userPhoto !== null) {
                $this->addFlash('alreadyPhoto','Une seule photo autorisé.');
                $this->redirectToRoute('add_document');
            } else {
                $user=$this->getUser();
                $photoProfil->setUser($user);
                $entityManager->persist($photoProfil);
                $entityManager->flush();

                $photoNumber=count($photoProfilRepository->findBy(['user'=>$user]));

                $this->addFlash('successPhoto','La photo est bien enregistré.');
                $this->redirectToRoute('add_document');
            }
        }elseif ($formAddDocument->isSubmitted() && !$formAddDocument->isValid()){
            $this->addFlash('errorPhoto','Photo non valide.');
            $this->redirectToRoute('add_document');
        }

        return $this->render('document/index.html.twig', [
            'formAddDocument' => $formAddDocument->createView(),
            'formPhoto'=>$formAddPhoto->createView(),
            'docNumber'=>$docNumber,
            'photoNumber'=>$photoNumber
        ]);
    }

    /**
     * @param Document $document
     * @param KernelInterface $kernel
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    #[Route('profil/document{id}' ,name:'app_view_document')]
    public function pdfView(Document $document, KernelInterface $kernel) {
        $projectRoot = $kernel->getProjectDir();
        $filename=$document;
        return $this->file($projectRoot.'/public/uploads/document/'.$filename);
    }

    #[Route('profil/edit/document/{id}' ,name:'edit_document')]
    public function editDocument(Document $document, Request $request,EntityManagerInterface $entityManager,SluggerInterface $slugger, KernelInterface $kernel, Filesystem $filesystem, ValidatorInterface $validator)
    {
        $formEditDocument=$this->createForm(DocumentType::class,$document);
        $formEditDocument->handleRequest($request);
        $oldFilename=$document->getFilename();

        $userDocument=$formEditDocument->get('filename')->getData();
        if ($userDocument) {
            $fileSize=$userDocument->getSize();
            if($fileSize > 1024000) {
                $this->addFlash('documentBig','Fichier trop volumineux, ne pas dépasser 1024K.');
                return $this->redirectToRoute('app_profil');
            } else {
                if($formEditDocument->isSubmitted() && $formEditDocument->isValid()) {
                    $originalFilename=pathinfo($userDocument->getClientOriginalName(),PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename=$safeFilename.'-'.uniqid($this->getUser()->getPrenom(),true).'.'.$userDocument->guessExtension();
                    try {
                        $userDocument->move(
                            $this->getParameter('document_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('ErrorUploadDocument','Il y eu une erreur lors de l\'upload du document, veuillez réessayer.');
                        $this->redirectToRoute('add_document');
                    }
                    $this->getUser()->setModifDate(new \DateTime());
                    $entityManager->persist($this->getUser());
                    $document->setFilename($newFilename);
                    $entityManager->flush();
                    //delete photo from public folder
                    $path=$kernel->getProjectDir() .'/public/uploads/document/'.$oldFilename;
                    $filesystem->remove([$path]);
                }
                $this->addFlash('successModifDocument','Document bien modifié.');
                return $this->redirectToRoute('app_profil');
                }
        }
        return $this->renderForm('document/editDocument.html.twig',[
            'formAddDocument'=>$formEditDocument,
        ]);
    }
}
