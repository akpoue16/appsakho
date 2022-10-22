<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use App\Entity\Dossier;
use App\Entity\Personnel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $genres = ['male', 'female'];

        $genre = $faker->randomElements($genres);

        $dossier = new Dossier();
        $user = new Personnel();
        $client = new Client();


        if ($genre == "male") {
            $titre = "Monsieur";
        } else {
            $titre = "Madame";
        }
        for($y = 1; $y < 5; $y++) {
        $user->setTitre($titre)
            ->setNom($faker->firstName($genre))
            ->setPrenom($faker->lastName($genre))
            ->setEmail($faker->email)
            ->setTel($faker->mobileNumber)
            ->setRoles(["ROLE_AVOCAT"])
            ->setPassword($this->encoder->hashPassword($user, 'admin'));
        }

        for($i = 1; $i < 10; $i++) {
            $client->setTitre($titre)
                ->setNom($faker->firstName($genre))
                ->setPrenom($faker->lastName($genre))
                ->setEmail($faker->email)
                ->setTel($faker->mobileNumber)
                ->setRoles(["ROLE_CLIENT"])
                ->setType("Particulier")
                ->setPassword($this->encoder->hashPassword($user, 'password'));
        }

        for($j=1; $j < 5; $j++){
            $dossier->setCode("Clt_00". $j)
                ->setClient($faker->numberBetween(0, $i))
                ->setPersonnel($faker->numberBetween(0, $y))
                ->setCommentaire($faker->sentence(15))
                ->setNom($faker->sentence(6));
        }

        $manager->persist($user);
        $manager->persist($client);
        $manager->persist($dossier);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
