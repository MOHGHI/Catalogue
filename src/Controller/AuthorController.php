<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authorRepository;

    /**
     * AuthorController constructor.
     * @param $authorRepository
     */
    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }


    /**
     * @Route("/authors", name="authors")
     */
    public function authors()
    {
        $authors = $this->authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/authors/new",name="new_author")
     */
    public function addAuthor(Request $request, Slugify $slugify)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author->setSlug($slugify->slugify(($author->getName().'.'.$author->getMiddleName().'.'.$author->getLastName())));
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('authors');
        }

        return $this->render('author/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/authors/{slug}",name="author_show")
     */

    public function author(Author $author)
    {
        return $this->render('author/show.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/authors/{slug}/edit", name="author_edit")
     */
    public function edit(Author $author, Request $request, Slugify $slugify)
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author->setSlug($slugify->slugify(($author->getName().'.'.$author->getMiddleName().'.'.$author->getLastName())));
            $author->setName($author->getName());
            $author->setMiddleName($author->getMiddleName());
            $author->setLastName($author->getMiddleName());
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('author_show', [
                'slug' => $author->getSlug()
            ]);
        }

        return $this->render('author/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/authors/{slug}/delete", name="author_delete")
     */
    public function delete(Author $author)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('authors');
    }
}
