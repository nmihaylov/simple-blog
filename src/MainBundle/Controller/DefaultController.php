<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
//        $this->addFlash('danger', 'This is danger!');
//        $this->addFlash('warning', 'This is warning!');
//        $this->addFlash('success', 'This is success!');
//        $this->addFlash('info', 'This is info!');

       $posts = $this->getDoctrine()->getRepository("MainBundle:Post")->findAll();
        return $this->render('default/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
