<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    private $faker;
    private $slug;

    public function __construct(Slugify $slugify)
    {
        $this->faker = Factory::create();
        $this->slug = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadAuthors($manager);
        $this->loadBooks($manager);
    }

    public function loadBooks(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($this->faker->text(100));
            $book->setISBN($this->faker->text(100));
            $book->setPagesNumber($this->faker->numberBetween(1,100));
            $book->setPublicationDate($this->faker->dateTime);
            $book->setImage('book.png');
            $book->setSlug($this->slug->slugify($book->getTitle()));

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function loadAuthors(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $author = new Author();
            $author->setName($this->faker->text(10));
            $author->setMiddleName($this->faker->text(10));
            $author->setLastName($this->faker->text(10));
            $author->setSlug($this->slug->slugify($author->getName().$author->getMiddleName().$author->getLastName()));

            $manager->persist($author);
        }

        $manager->flush();
    }

    public function loadAuthorsBooks(ObjectManager $manager)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->createQuery("SELECT a.id FROM Auction a")->getScalarResult();
        $ids = array_map('current', $result);

    }
}
