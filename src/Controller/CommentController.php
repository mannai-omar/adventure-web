<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use Doctrine\ORM\EntityManager;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/submit-comment', name: 'submit_comment')]
    public function submitComment(Request $request,EntityManager $em): Response
    {
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $message = $request->request->get('message');
        $activityId = $request->request->get('activity_id');
        $rating = $request->request->get('rating');

        $activity = $em->getRepository(Activity::class)->find($activityId);

        // Create and persist the Comment entity
        $comment = new Comment();
        $comment->setEmail($email);
        $comment->setName($name);
        $comment->setText($message);
        $comment->setActivity($activity);
        $comment->setCreatedAtValue();
        $comment->setRating($rating);

        $em->persist($comment);
        $em->flush();

        //$this->addFlash('success', 'Your comment has been successfully submitted!');

        return $this->redirectToRoute('activityController_details', ['id' => $activityId]);
    }


    #[Route('/admin/activities/comments', name: 'admin_activities_comments')]
    public function showAllAdmin(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        return $this->render('activity_admin/comments_activity_admin.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/comment/{id}/show', name: 'activity_comment_show',)]
    public function show(Comment $comment): Response
    {
        return $this->render('activity_admin/show_comment.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/admin/commentInActivity/{activity}/show', name: 'comment_in_activity_show')]
    public function showInActivity(EntityManagerInterface $entityManager, $activity): Response
    {
        $comments = $entityManager->getRepository(Comment::class)->findBy(['activity' => $activity]);
    
        return $this->render('activity_admin/commentInActivity.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/comment/{id}/delete', name: 'activity_comment_delete')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Comment deleted successfully!');
        return $this->redirectToRoute('admin_activities_comments');
    } 
}
