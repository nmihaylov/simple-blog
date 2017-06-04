<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mailer = $this->get('mailer');
            $message = new \Swift_Message();
            $message->setFrom('viperatt@gmail.com');
            $message->setTo($user->getUsername());
            $message->setSubject('Hello test');
            $message->setBody('test body');
            $result = $mailer->send($message);

            return $this->redirectToRoute("home");
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
