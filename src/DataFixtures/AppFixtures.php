<?php

namespace App\DataFixtures;

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
        $user = new Personnel();
        $user->setTitre("Monsieur")
             ->setNom("KONAN")
             ->setPrenom("Sidoine")
             ->setEmail("admin@gmail.com")
             ->setTel("0708052025")
             ->setPassword($this->encoder->hashPassword($user,'admin'));

        $manager->persist($user);
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
