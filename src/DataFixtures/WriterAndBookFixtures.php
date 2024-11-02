<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Writer;
use App\Entity\Book;
use Faker\Factory;

class WriterAndBookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $writers = [];

        for ($i = 0; $i < 10; $i++) {
            $writer = new Writer();
            $writer->setName($faker->name);
            $manager->persist($writer);
            $writers[] = $writer;
        }

        for ($j = 0; $j < 50; $j++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3));
            $book->setSummary($faker->paragraph(5));
            $book->setWriter($faker->randomElement($writers));

            $manager->persist($book);
        }

        $manager->flush();
    }
}
