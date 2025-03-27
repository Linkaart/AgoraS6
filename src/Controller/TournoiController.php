<?php
namespace App\Controller;
require_once __DIR__ . '/modele/class.PdoAgora.inc.php';    

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\TournoiRepository;
use App\Entity\Tournoi;
use App\Entity\CatTournois;
use App\Entity\Categorie;
use App\Entity\Participant;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class TournoiController extends AbstractController {
    #[Route('/tournoi', name: 'app_tournoi')]
    public function index(): Response {
        return $this->render('tournoi/index.html.twig', [
            'controller_name' => 'TournoiController', 'menuActif' => 'Jeux',
        ]);
    }

    //! CREER TOURNOIS
    #[Route('/tournoi/creer', name: 'app_tournoi_creer', methods: ['GET'])]
    public function creerTournoi(EntityManagerInterface $entityManager): Response {

        // créer l'objet
        $tournoi = new Tournoi();
        $tournoi->setLibelle('Tournois retrogame 2024');
        $tournoi->setdate(new \DateTime("2024-07-30 00:00:00"));
        $tournoi->setNbParticipants(32);

        // permet de récupérer la catégorie de tournois dont le libellé est 
        // "Retrogaming" pour l'ajouter lors de l'enregistrement du nouveau tournois
        $categorie = $entityManager->getRepository(CatTournois::class)->findOneBy(['libelle' => 'RetroGaming']);
        $tournoi->setCategorie($categorie);

        // créer un tournoi
        $tournoi = new Tournoi();
        $tournoi->setLibelle('Tournois e-sport 2024');
        $tournoi->setdate(new \DateTime("2024-10-14 00:00:00"));
        $tournoi->setNbParticipants(16);
        $categorie = $entityManager->getRepository(CatTournois::class)->findOneBy(['libelle' => 'e-sports']);
        $tournoi->setCategorie($categorie);
        
        //* Cherche ID -> Marianne
        // l'ajouter à la collection de participants du tournoi
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['prenom' => 'Marianne']);
        $tournoi->addParticipant($participant);

        //* Chercher ID -> Yannick
        // l'ajouter à la collection de participants du tournoi
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['prenom' => 'Yannick']);
        $tournoi->addParticipant($participant);

        //* Chercher ID -> Hamid
        // l'ajouter à la collection de participants du tournoi
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['prenom' => 'Hamid']);
        $tournoi->addParticipant($participant);
        
        // dire à Doctrine que l'objet sera (éventuellement) persisté   
        $entityManager->persist($tournoi);

        // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
        $entityManager->flush();
        return new Response('Nouveau tournoi '. $tournoi->getlibelle() .' enregistré avec '. sizeof($tournoi->getParticipants()) .' participants, son id est : ' . $tournoi->getId());
    }

    //! LIRE TOURNOIS
    #[Route('/tournois/{id}', name: 'app_tournois_lire')]
    public function lire($id, ManagerRegistry $doctrine) {
        // ces 2 exemples retournent le entity manager par défaut
        // ici nous n'utilisons qu'une base de données donc le entity manager par défaut suffit
        $entityManager = $doctrine->getManager();

        // $entityManager = $doctrine->getManager('default');
        // {id} dans la route permet de récupérer $id en argument de la méthode
        // on utilise le Repository de la classe Tournoi
        // il s'agit d'une classe qui est utilisée pour les recherches d'entités (et donc de données dans la base)
        // la classe TournoiRepository a été créée en même temps que l'entité parle make
        $tournois = $entityManager->getRepository(Tournois::class)->find($id);
        if (!$tournois) {
            throw $this->createNotFoundException(
            'Ce tournoi n\'existe pas : ' . $id);
        }
        
        return new Response('Voici le libellé du tournoi : ' . $tournois->getLibelle());
        // on peut bien sûr également rendre un template
    }

    //! LIRE AUTOMATIQUE TOURNOIS
    #[Route('tournoisautomatique/{id}', name: 'app_tournoisautomatique_lire')]
    public function lireautomatique(Tournois $tournois) {
        //grâce au Symfony\Bridge\Doctrine\ArgumentResolver\EntityValueResolver
        // il suffit de donner le tournois en argument 
        //la requête de recherche sera automatique
        // et une page 404 sera générée si le tournoi n'existe pas

        return new Response('Voici le libellé du tournoi lu automatiquement : '
        . $tournois->getLibelle() . ' crée le ' . $tournois->getDateCreation()->format('Y-m-d H:i:s'));
        //on peut bien sûr également rendre un template 
    }
    
    //! MODIFIER TOURNOIS
    #[Route('/tournois/modifier/{id}', name: 'app_tournois_modifier')]
    public function modifier($id, EntityManagerInterface $entityManager) {
        // 1) recherche du tournoi
        $tournois = $entityManager->getRepository(Tournois::class)->find($id);

        // en cas de tournoi inexistant, affichage page 404
        if (!$tournois) {
            throw $this->createNotFoundException('Aucun tournois avec l\'id ' .$id);
        }

        // 2) modification des propriétés
        $tournois->setLibelle('tournoi RetroGaming milésime 2024');
        
        // 3) éxécution de l'update
        $entityManager->flush();
        
        // redirection vers l'affichage du tournoi
        return $this->redirectToRoute('app_tournois_lire', ['id' => $tournois->getId()]);
    }

    //! SUPPRIMER TOURNOIS
    #[Route('/tournois/supprimer/{id}', name: 'app_tournois_supprimer')]
    public function supprimer($id, EntityManagerInterface $entityManager)
    {
        // 1 recherche du tournoi
        $tournois = $entityManager->getRepository(Tournois::class)->find($id);
        // en cas de tournoi inexistant, affichage page 404
        if (!$tournois) {
            throw $this->createNotFoundException(
                'Aucun tournois avec l\'id ' . $id
            );
        }
        // 2 suppression du tournoi
        $entityManager->remove(($tournois));
        // 3 exécution du delete
        $entityManager->flush();
        // affichage réponse
        return new Response('Le tournoi a été supprimé, id : ' . $id);
    }
}
