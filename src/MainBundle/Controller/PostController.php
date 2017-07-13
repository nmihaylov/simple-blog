<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class PostController extends Controller
{
    /**
     *
     * @Route("/add", name="add_post")
     */
    public function addAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user = $this->getUser();
            $post->setAuthor($user);
            $now = new \DateTime('now');
            $post->setPublishedAt($now);
            $post->setSlug(preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($post->getTitle())), 'UTF-8')));
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post added successfully!');

            return $this->redirectToRoute('home');
        }

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/posts/{slug}", name="view_post")
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Post $post){

//        if (!$post){
////            throw $this->createNotFoundException('The post does not exist');
//            throw $this->createNotFoundException('The product does not exist');
//            throw new NotFoundHttpException('Sorry not existing!');
//        }

        return $this->render('posts/post.html.twig', [
            'post' => $post
        ]);
    }
}
