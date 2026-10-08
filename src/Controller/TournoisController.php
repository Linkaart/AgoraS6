<?php
namespace App\Controller;
require_once __DIR__ . '/modele/class.PdoAgora.inc.php';    

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TournoisRepository;
use App\Entity\Tournois;
use App\Entity\CatTournois;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class TournoisController extends AbstractController {
    #[Route('/tournois', name: 'app_tournois')]
    public function index(): Response {
        return $this->render('tournois/index.html.twig', [
            'controller_name' => 'TournoisController', 'menuActif' => 'Jeux',
        ]);
    }

    //! CREER TOURNOIS
    #[Route('/tournois/creer', name: 'app_tournois_creer', methods: ['GET'])]
    public function creerTournois(EntityManagerInterface $entityManager): Response {
        // : Response type de retour de la méthode creerTournoi
        // pour récupérer le EntityManager (manager d'entités, d'objets)
        // on peut ajouter l'argument à la méthode comme ici  creerTournois(EntityManagerInterface $entityManager)
        // ou on peut récupérer le EntityManager comme dans la méthode suivante

        // créer l'objet
        $tournois = new Tournois();
        $tournois->setLibelle('tournois retrogame 2024');
        $tournois->setdate(new \DateTime("2024-07-30 00:00:00"));
        $tournois->setCategorie();

        // dire à Doctrine que l'objet sera (éventuellement) persisté
        $entityManager->persist($tournois);

        // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
        $entityManager->flush();
        return new Response('Nouveau tournois enregistré, son id est : ' .$tournois->getId());
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
    
    //! CREER TOURNOIS COMPLET
    #[Route('/tournois/complet/creer', name: 'app_tournois_complet_creer')]
    public function creerTournoisComplet(EntityManagerInterface $entityManager){
    
        // créer l'objet
        $categorie = new CatTournois();
        $categorie->setLibelle('Tournois Retrogaming'); 

        $tournois = new Tournois();
        $tournois->setLibelle('tournois retrogame 2024');
        $tournois->setdate(new \DateTime("2024-07-30 00:00:00"));
        $tournois->setCategorie();

        // dire à Doctrine que l'objet sera (éventuellement) persisté
        $entityManager->persist($categorie);
        $entityManager->persist($tournois);

        // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
        $entityManager->flush();
        return new Response('Nouveau tournois enregistré, son id est : ' .$tournois->getId() 
        .'et nouvelle catégorie de tournois enregistrée avec id:'.$categorie->getID());
    }
}
