<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use App\Entity\Nature;
use App\Entity\Qualite;
use App\Entity\Audience;
use App\Entity\Confrere;
use App\Entity\Diligence;
use App\Entity\Personnel;
use App\Entity\QualiteAd;
use App\Entity\TypePerso;
use App\Entity\Adversaire;
use App\Entity\Contentieux;
use App\Entity\Juridiction;
use Doctrine\Persistence\ObjectManager;
use App\Repository\ContentieuxRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $contentieuxRepository;

    public function __construct(UserPasswordHasherInterface $encoder, ContentieuxRepository $contentieuxRepository)
    {
        $this->encoder = $encoder;
        $this->contentieuxRepository = $contentieuxRepository;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Ajout qualite
        $tableQalite = ['DEMANDEUR', 'DEFENDEUR', 'APPELANT', 'INTIME', 'TIERS'];
        for ($q = 0; $q < count($tableQalite); $q++) {
            $qualite = new Qualite();
            $qualite->setTitre($tableQalite[$q]);

            $qualitead = new QualiteAd();
            $qualitead->setTitre($tableQalite[$q]);

            $manager->persist($qualite);
            $manager->persist($qualitead);
        }

        // Ajout Nature
        $tableNature = ['Pénal', 'Civile', 'Administrative', 'Criminelle'];
        for ($n = 0; $n < count($tableNature); $n++) {
            $nature = new Nature();
            $nature->setTitre($tableNature[$n]);
            $manager->persist($nature);
        }
        // Ajout Type de Collaborateur
        $tablePerso = ['Avocat', 'Sécretaire', 'Huissier'];
        for ($typ = 0; $typ < count($tablePerso); $typ++) {
            $typePerso = new TypePerso();
            $typePerso->setNom($tablePerso[$typ]);
            $manager->persist($typePerso);
        }

        // Ajout Juridiction
        $tableJuridiction = [
            [
                'nom' => 'TRIBUNAL DE 1ERE INSTANCE',
                'lieu' => 'Abidjan Yopougon'
            ], [
                'nom' => 'TRIBUNAL DE 1ERE INSTANCE',
                'lieu' => 'Abidjan Plateau'
            ], [
                'nom' => 'TRIBUNAL DE 1ERE INSTANCE',
                'lieu' => 'Abobo'
            ], [
                'nom' => 'COUR D\'APPEL',
                'lieu' => 'Abidjan'
            ], [
                'nom' => 'COUR SUPREME',
                'lieu' => 'Abidjan'
            ]
        ];
        for ($ju = 0; $ju < count($tableJuridiction); $ju++) {
            $juridiction = new Juridiction();
            $juridiction->setTitre($tableJuridiction[$ju]['nom']);
            $juridiction->setLieu($tableJuridiction[$ju]['lieu']);
            $manager->persist($juridiction);
        }

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
                ->setTypePerso($typePerso)
                ->setNom($faker->firstName($genre))
                ->setPrenom($faker->lastName($genre))
                ->setEmail($faker->email)
                ->setTel($faker->mobileNumber)
                ->setCel($faker->mobileNumber)
                ->setRoles(["ROLE_AVOCAT"])
                ->setPassword($this->encoder->hashPassword($user, 'admin'));
            $manager->persist($user);

            $confrere = new Confrere();
            $confrere->setTitre($titre)
                ->setNom($faker->firstName($genre))
                ->setPrenom($faker->lastName($genre))
                ->setEmail($faker->email)
                ->setTel($faker->mobileNumber)
                ->setCel($faker->mobileNumber);
            $manager->persist($confrere);

            $adversaire = new Adversaire();
            $adversaire->setCode("Adv_00$y")
                ->setTitre($titre)
                ->setNom($faker->firstName($genre))
                ->setPrenom($faker->lastName($genre))
                ->setEmail($faker->email)
                ->setTel($faker->mobileNumber)
                ->setCel($faker->mobileNumber);
            $manager->persist($adversaire);

            for ($i = 1; $i < 5; $i++) {

                //Donnée Client
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
                    $contentieux = new Contentieux();

                    //$ContentieuxRepository = $manager->getRepository(Contentieux::class);
                    $dernierContentieux = $this->contentieuxRepository->findAll();
                    $idcont = count($dernierContentieux);
                    $cont = $idcont + $j;
                    $day = new \DateTimeImmutable();

                    $contentieux->setCode("T00$j")
                        ->setClient($client)
                        ->setQualite($qualite)
                        ->setQualiteAd($qualitead)
                        ->setAdversaire($adversaire)
                        ->setCreatedAt($day->modify('+' . $j . ' day')) // DateTime('2003-03-15 02:00:49', 'Africa/Lagos')
                        ->setAvocat($user)
                        ->setConfrere($confrere)
                        ->setCommentaire($faker->sentence(8))
                        ->setJuridiction($juridiction)
                        ->setNature($nature);
                    $manager->persist($contentieux);

                    for ($au = 0; $au < mt_rand(0, 5); $au++) {
                        $audience = new Audience();
                        //$nomPresident = $faker->firstName($genre).' '.$faker->lastName($genre);
                        $audience->setCode("Au00$au")
                            //->setNomPresident($nomPresident)
                            ->setCreatedAt($day->modify('+' . $au . ' day')) // DateTime('2003-03-15 02:00:49', 'Africa/Lagos')
                            ->setAvocat($user)
                            ->setContentieux($contentieux)
                            ->setJuridiction($juridiction);
                        $manager->persist($audience);
                    }

                    for ($dil = 0; $dil < mt_rand(0, 3); $dil++) {
                        $diligence = new Diligence();
                        $diligence->setContentieux($contentieux)
                            //->setNomPresident($nomPresident)
                            ->setCreatedAt($day->modify('+' . $dil . ' day')) // DateTime('2003-03-15 02:00:49', 'Africa/Lagos')
                            ->setMotif($faker->sentence(8))
                            ->setDebutTime(new \DateTime())
                            ->setFinTime(new \DateTime());
                        $manager->persist($diligence);
                    }
                }
            }
        }

        // $product = new Product();
        // $manager->persist($product);


        //Donnée Avocat
        $admin = new Personnel();
        $admin->setTitre('Monsieur')
            ->setTypePerso($typePerso)
            ->setNom('Admin')
            ->setPrenom('Admin')
            ->setEmail('admin@gmail.com')
            ->setTel('0707070707')
            ->setCel('0505050505')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->hashPassword($user, 'admin'));
        $manager->persist($admin);

        $manager->flush();
    }
}
