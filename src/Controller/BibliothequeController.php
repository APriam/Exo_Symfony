<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\InscriptionType;
use App\Form\BookType;
use App\Entity\Users;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\SessionCheck;

class BibliothequeController extends AbstractController
{
    /**
     * @Route("/", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {   
        if ($session->has('user_id')) {
            return $this->redirectToRoute('accueil');
        }
        $error_inscription = $request->query->get('error_inscription');
        // Vérifier si le paramètre 'error' existe et n'est pas vide
        if ($error_inscription !== null && $error_inscription !== '') {
            // Ajouter un message flash avec le type 'error'
            $this->addFlash('error_inscription', 'Nom d\'utilisateur déjà utilisé');
        }
        $user = new Users();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $username = $form->get('username')->getData();
            $usercheck = $entityManager->getRepository(Users::class)->findOneBy(['username' => $username]);
            if ($usercheck !== null) {
                $error_inscription="indentifiant";
                return $this->redirectToRoute('inscription', ['error_inscription' => $error_inscription]);
            } else {
                $entityManager ->persist($user);
                $entityManager -> flush();
                $session->set('user_id', $user->getId());
                return $this->redirectToRoute('accueil');
            }
        }

        return $this->render('log/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function connexion(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($session->has('user_id')) {
            return $this->redirectToRoute('accueil');
        }
        $error_connexion = $request->query->get('error_connexion');
        if ($error_connexion !== null && $error_connexion !== '') {
            // Ajouter un message flash avec le type 'error'
            $this->addFlash('error_connexion', 'Nom d\'utilisateur ou Mot de passe incorrect');
        }
        $form = $this->createForm(InscriptionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();
            $user = $entityManager->getRepository(Users::class)->findOneBy(['username' => $username]);
            if ($user !== null && $user->getPassword() == $password) {
                $session->set('user_id', $user->getId());
                return $this->redirectToRoute('accueil');
            } else {
                $error_connexion="false";
                return $this->redirectToRoute('user_connexion', ['error_connexion' => $error_connexion]);
            }
        }

        return $this->render('log/connexion.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function deconnexion(SessionInterface $session): RedirectResponse{
        $session->remove('user_id');
        return $this->redirectToRoute('user_connexion');
    }

    /**
     * @Route("/book/ajouter_livre", name="ajouter_livre")
     */
    public function book_add(Request $request, EntityManagerInterface $entityManager, SessionInterface $session) : Response {
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('user_connexion');
        }
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager ->persist($book);
            $entityManager -> flush();
        }
        return $this->render('book/book_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/book/edit/{id}", name="book_edit")
     */
    public function book_edit($id, Request $request, EntityManagerInterface $entityManager,  SessionInterface $session){
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('user_connexion');
        }
        $book = $entityManager->getRepository(Book::class)->find($id);
        if(!$book){
            return $this->redirectToRoute('book_add');
        }
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager -> flush();
            return $this->redirectToRoute('book_list');
        }
        return $this->render('book/book_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function book_list(EntityManagerInterface $entityManager, SessionInterface $session, Request $request): Response {
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('user_connexion');
        }
        $tri = $request->query->get('tri', 'id_asc');
        if ($tri === 'title_asc') {
            $inverseTri = 'title_desc';
        } elseif ($tri === 'title_desc') {
            $inverseTri = 'title_asc';
        } elseif ($tri === 'author_asc') {
            $inverseTri = 'author_desc';
        } elseif ($tri === 'author_desc') {
            $inverseTri = 'author_asc';
        } elseif ($tri === 'date_asc') {
            $inverseTri = 'date_desc';
        } elseif ($tri === 'date_desc') {
            $inverseTri = 'date_asc';
        } else {
            $inverseTri = 'title_asc'; // Tri par défaut
        }
        $orderBy = [];
        switch ($tri) {
            case 'id_asc':
                $orderBy = ['id' => 'ASC'];
                break;
            case 'title_asc':
                $orderBy = ['title' => 'ASC'];
                break;
            case 'title_desc':
                $orderBy = ['title' => 'DESC'];
                break;
            case 'author_asc':
                $orderBy = ['author' => 'ASC'];
                break;
            case 'author_desc':
                $orderBy = ['author' => 'DESC'];
                break;
                case 'date_asc':
                    $orderBy = ['date' => 'ASC'];
                    break;
                case 'date_desc':
                    $orderBy = ['date' => 'DESC'];
                    break;
            default:
                $orderBy = ['id' => 'ASC'];
                break;
        }
        $books = $entityManager->getRepository(Book::class)->findBy([], $orderBy);
        

        return $this->render('book/book_list.html.twig', [
            'books' => $books,
            'inverseTri' => $inverseTri,
        ]);
    }
    public function book_delete($id, Request $request, EntityManagerInterface $entityManager){
        $book = $entityManager->getRepository(Book::class)->find($id);
        if(!$book){
            return $this->redirectToRoute('book_list');
        }
        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirectToRoute('book_list');
    }
    public function user(Request $request, EntityManagerInterface $entityManager, SessionInterface $session){
        $error_user = $request->query->get('error_user');
        if ($error_user !== null && $error_user !== '') {
            // Ajouter un message flash avec le type 'error'
            $this->addFlash('error_user', 'Nom d\'utilisateur déjà utilisé');
        }
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('user_connexion');
        }
        $userId = $session->get('user_id');
        $user = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
        if(!$user){
            return $this->redirectToRoute('inscription');
        }
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $username = $form->get('username')->getData();
            $usercheck = $entityManager->getRepository(Users::class)->findOneBy(['username' => $username]);
        if ($usercheck !== null) {
            $error_user="indentifiant";
            return $this->redirectToRoute('user', ['error_user' => $error_user]);
        } else {
            $entityManager -> flush();
            return $this->redirectToRoute('book_list');
        }
        }
        return $this->render('user/user_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function user_delete(Request $request, EntityManagerInterface $entityManager, SessionInterface $session){
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('user_connexion');
        }
        $userId = $session->get('user_id');
        $user = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
        if(!$user){
            return $this->redirectToRoute('inscription');
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('user_deconnexion');
    }
    public function accueil(){
        return $this->render('home/accueil.html.twig', [
        ]);
    }
}

