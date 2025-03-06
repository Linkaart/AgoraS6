<?php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Membre;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreFixtures extends Fixture {
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $unPasswordHasher) {
        $this->passwordHasher = $unPasswordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void {
    // créer 10 membres
        for ($i = 0; $i < 10; $i++) {
            $membre = new Membre();
            $membre->setUsername($this->faker->lastName);
            $membre->setNomMembre($this->faker->lastName);
            $membre->setPrenomMembre($this->faker->firstName);
            $membre->setTelMembre(substr($this->faker->e164PhoneNumber, 2, 10));
            $membre->setMailMembre(sprintf('userdemo%d@exemple.com', $i));
            $membre->setPassword($this->passwordHasher->hashPassword($membre, 'userdemo'));
            $membre->setRueMembre($this->faker->streetAddress);
            $membre->setVilleMembre($this->faker->city);
            $membre->setCpMembre($this->faker->numberBetween(111111, 999999));
            $manager->persist($membre); 
        }
        $manager->flush();
    }
}
?>