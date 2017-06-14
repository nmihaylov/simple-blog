<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/add", name="add_post")
     */
    public function add(Request $request){
        $post = new Post();
        $user = $this->getUser();
        $post->setAuthor($user);
        $now = new \DateTime('now');
        $post->setPublishedAt($now);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isValid()){
            $post->setSlug(preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($post->getTitle())), 'UTF-8')));
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post added successfully');

            return $this->redirectToRoute('home');
        }

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

}
