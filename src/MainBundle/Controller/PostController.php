<?php

namespace MainBundle\Controller;

use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @Route("/admin")
 */
class PostController extends Controller
{

    /**
     * @Route("/", name="admin_page")
     */
    public function indexAction(Request $request)
    {
//        $this->addFlash('danger', 'This is danger!');
//        $this->addFlash('warning', 'This is warning!');
//        $this->addFlash('success', 'This is success!');
//        $this->addFlash('info', 'This is info!');

        $posts = $this->getDoctrine()->getRepository("MainBundle:Post")->findAll();
        $paginator = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository('MainBundle:Post')
            ->createQueryBuilder('post')
            ->select('post')->innerJoin('post.author', 'author');

        /** @var KnpPaginatorBundle $postsPaginated */
        $postsPaginated = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            40 /*limit per page*/,
            [
                'defaultSortFieldName' => 'post.publishedAt',
                'defaultSortDirection' => 'desc',
            ]
        );


        return $this->render('admin/index.html.twig', [
            'posts' => $posts,
            'pagination' => $postsPaginated
        ]);
    }

    /**
     *
     * @Route("/add", name="add_post")
     */
    public function addAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post->setAuthor($user);
            $now = new \DateTime('now');
            $post->setPublishedAt($now);
            $post->setSlug(preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($post->getTitle())), 'UTF-8')));
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post added successfully!');

            return $this->redirectToRoute('admin_page');
        }

        return $this->render('admin/posts/new.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="edit_post")
     *
     * @param Post $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Post $post, Request $request)
    {

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post->setAuthor($user);
            $now = new \DateTime('now');
            $post->setPublishedAt($now);
            $post->setSlug(preg_replace(
                '/\s+/', '-', mb_strtolower(trim(strip_tags($post->getTitle())), 'UTF-8')
            ));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post edited successfully!');
            return $this->redirectToRoute('admin_page');
        }

        return $this->render('admin/posts/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="delete_post")
     *
     * @param Post $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Post removed successfully!');
        return $this->redirectToRoute('admin_page');
    }

}
