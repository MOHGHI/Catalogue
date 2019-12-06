<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private $bookRepository;

    /**
     * BookController constructor.
     * @param $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }


    /**
     * @Route("/books", name="books")
     */
    public function books()
    {
        $books = $this->bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/books/new",name="new_book")
     */
    public function addBook(Request $request, Slugify $slugify)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->setSlug($slugify->slugify($book->getTitle()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('books');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/books/{slug}",name="book_show")
     */

    public function book(Book $book)
    {
        $authors = $book->getBooksAuthors();
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/books/{slug}/edit", name="book_edit")
     */
    public function edit(Book $book, Request $request, Slugify $slugify)
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setTitle($book->getTitle());
            $book->setSlug($slugify->slugify($book->getTitle()));
            $book->setPagesNumber($book->getPagesNumber());
            $book->setISBN($book->getISBN());
            $book->setPublicationDate($book->getPublicationDate());
            $book->addBooksAuthors($book->getBooksAuthors());
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_show', [
                'slug' => $book->getSlug()
            ]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/books/{slug}/delete", name="book_delete")
     */
    public function delete(Book $book)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('books');
    }


}
