<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\AdressTemp;
use App\Form\AdressTempType;
use App\Repository\AdressTempRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddAdresseController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('profil/search/adresse', name: 'search_adress')]
    public function index(Request $request, EntityManagerInterface $entityManager, AdressTempRepository $adressTempRepository): Response
    {
        $formSearchAdress=$this->createForm(AdressTempType::class);
        $formSearchAdress->handleRequest($request);

        if($formSearchAdress->isSubmitted() && $formSearchAdress->isValid()) {
            $query=$formSearchAdress->get('adresse')->getData();
            $url='https://api-adresse.data.gouv.fr/search/?q='.htmlentities(urlencode($query));
            $response=$this->client->request(
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
        }
        $adressChoice=$adressTempRepository->findAll();

        return $this->render('add_adresse/index.html.twig', [
            'formAdresse' => $formSearchAdress->createView(),
            'response'=>$adressChoice
        ]);
    }

    #[Route('profil/add/adresse/{id}',name: 'add_adress')]
    public function persistAdress(EntityManagerInterface $entityManager, AdressTemp $adressTemp, AdressTempRepository $adressTempRepository) {
        $userAdress= new Adress();
        $label=$adressTemp->getLabel();
        $housenumber=$adressTemp->getHousenumber();
        $street=$adressTemp->getStreet();
        $postalCode=$adressTemp->getPostalCode();
        $city=$adressTemp->getCity();
        $userAdress->setLabel($label);
        $userAdress->setHousenumber($housenumber);
        $userAdress->setStreet($street);
        $userAdress->setPostalCode($postalCode);
        $userAdress->setCity($city);


        $user= $this->getUser();
        if ($user->getAdresse() === null) {
            $user->setAdresse($userAdress);

            $entityManager->persist($userAdress);
            $entityManager->flush();
            $entityManager->persist($user);
            $entityManager->flush();
        } else {
            $this->addFlash('alreadyAdress',"Vous avez déja une adresse renseignée.");
        }
        $adresseTemporaire=$adressTempRepository->findAll();
        foreach ($adresseTemporaire as $adress) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }
        $this->addFlash('successAdress','Adresse bien enregistrée.');
        return $this->redirectToRoute('search_adress');
    }

    #[Route('profil/edit/adresse/{id}',name: 'edit_adress')]
    public function EditAdress(EntityManagerInterface $entityManager, AdressTemp $adressTemp, AdressTempRepository $adressTempRepository) {
        $user= $this->getUser();
        $userAdress= $user->getAdresse();
        $label=$adressTemp->getLabel();
        $housenumber=$adressTemp->getHousenumber();
        $street=$adressTemp->getStreet();
        $postalCode=$adressTemp->getPostalCode();
        $city=$adressTemp->getCity();
        $userAdress->setLabel($label);
        $userAdress->setHousenumber($housenumber);
        $userAdress->setStreet($street);
        $userAdress->setPostalCode($postalCode);
        $userAdress->setCity($city);


        $entityManager->persist($userAdress);
        $entityManager->flush();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('successAdressModif',"Adresse bien modifiée.");

        $adresseTemporaire=$adressTempRepository->findAll();
        foreach ($adresseTemporaire as $adress) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }
        return $this->redirectToRoute('edit_profil');
    }
}
