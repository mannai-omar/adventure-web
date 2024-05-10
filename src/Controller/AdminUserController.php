<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index')]
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        $totalUsers = $userRepository->count([]);
        $verifiedUsersCount = $userRepository->countVerifiedUsers();
        $unverifiedUsersCount = $userRepository->countUnverifiedUsers();

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'form' => $form->createView(),
            'totalUsers' => $totalUsers,
            'verifiedUsersCount' => $verifiedUsersCount,
            'unverifiedUsersCount' => $unverifiedUsersCount,
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword != '') {
                $hashPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashPassword);
            } 
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('admin_user/user/tricroi', name: 'tri', methods: ['GET', 'POST'])]
    public function triCroissant(\App\Repository\UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $users = $userRepository->findAllSorted();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $totalUsers = $userRepository->count([]);
        $verifiedUsersCount = $userRepository->countVerifiedUsers();
        $unverifiedUsersCount = $userRepository->countUnverifiedUsers();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'totalUsers' => $totalUsers,
            'verifiedUsersCount' => $verifiedUsersCount,
            'unverifiedUsersCount' => $unverifiedUsersCount,
        ]);
    }

    #[Route('admin_user/user/tridesc', name: 'trid', methods: ['GET', 'POST'])]
    public function triDescroissant(\App\Repository\UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $users = $userRepository->findAllSorted1();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $totalUsers = $userRepository->count([]);
        $verifiedUsersCount = $userRepository->countVerifiedUsers();
        $unverifiedUsersCount = $userRepository->countUnverifiedUsers();

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'totalUsers' => $totalUsers,
            'verifiedUsersCount' => $verifiedUsersCount,
            'unverifiedUsersCount' => $unverifiedUsersCount,
        ]);
    }

    #[Route('/admin', name: 'admin_stat', methods: ['GET'])]
    public function index2(UserRepository $userRepository): Response
    {
        // Modification ici pour inclure les statistiques


        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),

        ]);
    }
}
