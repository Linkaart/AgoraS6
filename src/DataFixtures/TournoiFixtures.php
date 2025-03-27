<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tournoi;
use App\Entity\CatTournois;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class TournoiFixtures extends Fixture
{
    // public function load(ObjectManager $manager, EntityManagerInterface $entityManager): void
    public function load(ObjectManager $manager): void
    {
        $tbDataTournois = [
            [
                'cattournoi' => 'retrogaming',
                'orga-tournoi' => [
                    [
                        'tournoi' => [
                            'libelle' => 'Tournoi Retrogame 2023',
                            'date' => new \DateTime("2023-06-30 10:00:00"),
                            'date_creation' => new \DateTime("2023-04-10 12:42:10"),
                            'categorie_id' => 1,
                            'nb_participants' => 34
                        ],
                        'participant' => [
                            ['prenom' => 'Yannick', 'nom' => 'Barney', 'telephone' => '0383123456', 'email' => 'ybarney@gmail.com'],
                            ['prenom' => 'Magali', 'nom' => 'Andreu', 'telephone' => '0387845126', 'email' => 'magali.andreu@yahoo.fr'],
                            ['prenom' => 'Hamid', 'nom' => 'Bourleki', 'telephone' => '0387123456', 'email' => 'hamid.bourleki@outlook.fr'],
                        ],
                    ],
                    [
                        'tournoi' => [
                            'libelle' => 'Tournoi Retrogame 2024',
                            'date' => new \DateTime("2024-08-14 10:00:00"),
                            'date_creation' => new \DateTime("2024-05-01 22:31:10"),
                            'categorie_id' => 1,
                            'nb_participants' => 64
                        ],
                        'participant' => [
                            ['prenom' => 'Groot', 'nom' => 'Moisappelle', 'telephone' => '0812123456', 'email' => 'groot@gardiens.com'],
                            ['prenom' => 'Star', 'nom' => 'Lord', 'telephone' => '0723123456', 'email' => 'starlord@gardiens.com'],
                        ],
                    ],
                ],
            ],
            [
                'cattournoi' => 'e-sports',
                'orga-tournois' => [
                    [
                        'tournois' => [
                            'libelle' => 'Tournois e-sport 2024',
                            'date' => new \DateTime("2024-10-14 10:00:00"),
                            'date_creation' => new \DateTime("2024-05-01 22:52:10"),
                            'categorie_id' => 2,
                            'nb_participants' => 64
                        ],
                        'participants' => [
                            ['prenom' => 'Sophie', 'nom' => 'Statham', 'telephone' => '0387951575', 'email' => 'sstatham@holywood.com'],
                            ['prenom' => 'Marianne', 'nom' => 'Deuxlions', 'telephone' => '0387456585', 'email' => 'marianne@zoodeslions.fr'],
                        ],
                    ],
                    [
                        'tournois' => [
                            'libelle' => 'Tournois e-sport 2023',
                            'date' => new \DateTime("2023-08-22 10:00:00"),
                            'date_creation' => new \DateTime("2023-05-01 12:32:10"),
                            'categorie_id' => 2,
                            'nb_participants' => 32
                        ],
                        'participants' => [
                            ['prenom' => 'Mickey', 'nom' => 'Mouse', 'telephone' => '0787456585', 'email' => 'mickey@dysney.com'],
                            ['prenom' => 'Labelle', 'nom' => 'auboisDormant', 'telephone' => '0788456585', 'email' => 'labelleauboisdormant@dysney.com'],
                        ],
                    ],
                    [
                        'tournois' => [
                            'libelle' => 'Tournois e-sport 2022',
                            'date' => new \DateTime("2022-07-30 10:00:00"),
                            'date_creation' => new \DateTime("2022-05-12 14:22:10"),
                            'categorie_id' => 2,
                            'nb_participants' => 32
                        ],
                        'participants' => [
                            ['prenom' => 'Alfred', 'nom' => 'Einstein', 'telephone' => '0187456585', 'email' => 'einstein@inventeur.com'],
                            ['prenom' => 'Louis', 'nom' => 'Pasteur', 'telephone' => '0383456585', 'email' => 'lpasteur@inventeur.com'],
                        ],
                    ],
                ],
            ],
        ];

        for ($i = 0; $i < count($tbDataTournois); ++$i) {
            // créer une catégorie de tournois
            $categorie = new CatTournois();
            $categorie->setLibelle($tbDataTournois[$i]['cattournoi']);
            $manager->persist($categorie);

            // créer les tournois de la catégorie
            foreach ($tbDataTournois[$i]['orga-tournois'] as $unTypeTournoi) {
                $tournoi = new Tournoi();
                $tournoi->setLibelle($unTypeTournoi['tournois']['libelle']);
                $tournoi->setDate($unTypeTournoi['tournois']['date']);
                $tournoi->setDateCreation($unTypeTournoi['tournois']['date_creation']);
                $tournoi->setNBParticipants($unTypeTournoi['tournois']['nb_participants']);
                
                // mettre en relation le tournoi avec la catégorie
                $tournoi->setCategorie($categorie);
                $manager->persist($tournoi);

                // parcours chacun des tournois
                for ($j = 0; $j < count($unTypeTournoi['participants']); ++$j) {
                    $participant = new Participant();
                    $participant->setPrenom($unTypeTournoi['participants'][$j]['prenom']);
                    $participant->setNom($unTypeTournoi['participants'][$j]['nom']);
                    $participant->setTelephone($unTypeTournoi['participants'][$j]['telephone']);
                    $participant->setEmail($unTypeTournoi['participants'][$j]['email']);
                    
                    // mettre en relation le participant avec le tournoi
                    $participant->addTournoi($tournoi);
                    $manager->persist($participant);
                }
            }
        }

        // exécuter les mises à jour de la base de données
        $manager->flush();
    }
}