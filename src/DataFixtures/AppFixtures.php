<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de l'utilisateur admin
        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'admin'));
        $manager->persist($adminUser);

        // Création de 5 utilisateurs réguliers
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }

        // Création de 50 articles
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence(6, true));
            $article->setContent($faker->paragraphs(3, true));
            $article->setCreator($faker->randomElement($users));
            $article->setPremium($faker->boolean(20)); // 20% de chance d'être premium

            $manager->persist($article);
        }

        $manager->flush();
    }
}