<?php

namespace MainBundle\Controller;

use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use MainBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
            Post::NUM_ITEMS/*limit per page*/,
            [
                'defaultSortFieldName' => 'post.publishedAt',
                'defaultSortDirection' => 'desc',
            ]
        );


        return $this->render('default/index.html.twig', [
            'posts' => $posts,
            'pagination' => $postsPaginated
        ]);
    }

    /**
     * @Route("/post/{slug}", name="view_post")
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
