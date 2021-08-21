<?php

namespace App\Controller;

use App\Entity\AdressTemp;
use App\Form\AdressTempType;
use App\Form\PhotoType;
use App\Form\SearchCollabType;
use App\Form\UserModifType;
use App\Kernel;
use App\Repository\AdressRepository;
use App\Repository\AdressTempRepository;
use App\Repository\PhotoProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    #[IsGranted('ROLE_IS_AUTHENTICATED_FULLY')]
    public function index(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
    {

        $user=$this->getUser();
        $userRole=$user->getRoles();
        $candidat='ROLE_CANDIDAT';

        if($user->getIsAccepted()===true && in_array($candidat,$userRole)) {
            $role=array_splice($userRole,array_search($candidat,$userRole),1);
            $userRole='ROLE_COLLABORATEUR';
            $user->setRoles($userRole);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $userTel=$user->getTelephone();
        $userTelLength=count(str_split($userTel));
        if($userTelLength%2===0){
            $userTel=wordwrap($userTel,2,' ',true);
        }else{
            $userTel=wordwrap($userTel,3,' ',true);
        }

        $searchCollab=$this->createForm(SearchCollabType::class);
        $searchCollab->handleRequest($request);
        $result=[];
        if ($searchCollab->isSubmitted() && $searchCollab->isValid()) {
            $query=$searchCollab->get('query')->getData();
            $result=$userRepository->findCollab($query);
        }

        return $this->render('profil/index.html.twig', [
            'telephone' => $userTel,
            'form'=>$searchCollab->createView(),
            'result'=>$result
        ]);
    }
    #[Route('/profil/edit/profil', name: 'edit_profil', methods: ['GET', 'POST'])]
    public function editUser(Request $request,EntityManagerInterface $entityManager, PhotoProfilRepository $photoProfilRepository, Filesystem $filesystem, Kernel $kernel, AdressRepository $adressRepository,AdressTempRepository $adressTempRepository ,HttpClientInterface $client):Response
    {
        //User info modification
        $user=$this->getUser();
        $formEditUser=$this->createForm(UserModifType::class,$user);
        $formEditUser->handleRequest($request);

        if ($formEditUser->isSubmitted() && $formEditUser->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }
        
        //Photo modification
        $photo= $user->getFilename()->getId();

        $oldPhoto=$photoProfilRepository->findOneBy(['id'=>$photo]);
        $oldName=$oldPhoto->getFilename();

        $formEditPhoto=$this->createForm(PhotoType::class,$oldPhoto);
        $formEditPhoto->handleRequest($request);

        if ($formEditPhoto->isSubmitted() && $formEditPhoto->isValid()) {
            $photo=$formEditPhoto->get('imageName')->getData();
            $fileImage = md5(uniqid()).'.'.$photo->guessExtension();

            $photo->move(
                $this->getParameter('photo_directory'),
                $fileImage
            );
            $oldPhoto->setFilename($fileImage);

            $entityManager->flush();
            //delete photo from public folder
            $path=$kernel->getProjectDir() .'/public/uploads/photo/'.$oldName;
            $filesystem->remove([$path]);

            return $this->redirectToRoute('edit_profil', [], Response::HTTP_SEE_OTHER);
        }
        
        //Adress modification
        $adress= $user->getAdresse()->getId();
        $oldAdress=$adressRepository->findOneBy(['id'=>$adress]);

        $formEditAdress=$this->createForm(AdressTempType::class);
        $formEditAdress->handleRequest($request);

        if($formEditAdress->isSubmitted() && $formEditAdress->isValid()) {
            $query=$formEditAdress->get('adresse')->getData();
            $url='https://api-adresse.data.gouv.fr/search/?q='.htmlentities(urlencode($query));
            $response=$client->request(
                'GET',
                'https://api-adresse.data.gouv.fr/search/?q='.$url
            );
            $content=$response->toArray();
            $arrayContent=$content['features'];
            foreach ($arrayContent as $adress) {
                $label=$adress['properties']['label'];
                if(isset($adress['properties']['housenumber'])) {
                    $housenumber = $adress['properties']['housenumber'];
                } else {
                    $housenumber = 'N/A';
                }
                if(isset($adress['properties']['street'])) {
                    $street = $adress['properties']['street'];
                } else {
                    $street = 'N/A';
                }
                $postalCode = $adress['properties']['postcode'];
                $city = $adress['properties']['city'];
                $adress= new AdressTemp();
                $adress->setLabel($label);
                $adress->setHousenumber($housenumber);
                $adress->setStreet($street);
                $adress->setPostalCode($postalCode);
                $adress->setCity($city);
                $entityManager->persist($adress);
                $entityManager->flush();
            }

            return $this->redirectToRoute('edit_profil', [], Response::HTTP_SEE_OTHER);
        }
        $adressChoice=$adressTempRepository->findAll();

        return $this->renderForm('profil/edit.html.twig',[
            'form'=>$formEditUser,
            'formPhoto'=>$formEditPhoto,
            'formAdresse'=>$formEditAdress,
            'response'=>$adressChoice

        ]);
    }
}
