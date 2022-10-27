<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use App\Entity\Dossier;
use App\Entity\Juridiction;
use App\Entity\Nature;
use App\Entity\Qualite;
use App\Entity\Personnel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;

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
        for ($y = 1; $y < 5; $y++) {
            $user = new Personnel();
            $genres = ['male', 'female'];

            $genre = $faker->randomElements($genres);
            if ($genre[0] == "male") {
                $titre = "Monsieur";
            } else {
                $titre = "Madame";
            }
            $user->setTitre($titre)
                ->setNom($faker->firstName($genre))
                ->setPrenom($faker->lastName($genre))
                ->setEmail($faker->email)
                ->setTel($faker->mobileNumber)
                ->setCel($faker->mobileNumber)
                ->setRoles(["ROLE_AVOCAT"])
                ->setPassword($this->encoder->hashPassword($user, 'admin'));
            $manager->persist($user);


            for ($i = 1; $i < 10; $i++) {
                $genres = ['male', 'female'];

                $genre = $faker->randomElements($genres);
                if ($genre[0] == "male") {
                    $titre = "Monsieur";
                } else {
                    $titre = "Madame";
                }
                $client = new Client();
                $client->setCode("Clt_00$i")
                    ->setTitre($titre)
                    ->setNom($faker->firstName($genre))
                    ->setPrenom($faker->lastName($genre))
                    ->setEmail($faker->email)
                    ->setTel($faker->mobileNumber)
                    ->setCel($faker->mobileNumber)
                    ->setRoles(["ROLE_CLIENT"])
                    ->setType("Particulier")
                    ->setPassword($this->encoder->hashPassword($user, 'password'));
                $manager->persist($client);

                for ($j = 1; $j < mt_rand(0, 5); $j++) {
                    $dossier = new Dossier();
                    $dossier->setCode("Dos_00$j")
                        ->setClient($client)
                        ->setPersonnel($user)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setCommentaire($faker->sentence(8))
                        ->setNom($faker->sentence(6));
                    $manager->persist($dossier);
                }
            }
        }
        // Ajout qualite
        $tableQalite = ['DEMANDEUR', 'DEFENDEUR', 'APPELANT', 'INTIME', 'TIERS'];
        for($q=0; $q < count($tableQalite); $q++){
            $qualite = new Qualite();
            $qualite->setTitre($tableQalite[$q]);
            $manager->persist($qualite);
        }
        
        // Ajout Juridiction
        $tableJuridiction = ['TRIBUNAL DE 1ERE INSTANCE', 'COUR D\'APPEL', 'COUR SUPREME'];
        for($ju=0; $ju < count($tableJuridiction); $ju++){
            $juridiction = new Juridiction();
            $juridiction->setTitre($tableJuridiction[$ju]);
            $manager->persist($juridiction);
        }

        // Ajout Nature
        $tableNature = ['PENALE', 'CIVILE', 'ADMINISTRATIVE', 'CRIMINELLE'];
        for($n=0; $n < count($tableNature); $n++){
            $nature = new Nature();
            $nature->setTitre($tableNature[$n]);
            $manager->persist($nature);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
