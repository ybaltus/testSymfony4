<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PageController extends AbstractController
{
    /**
     * @Route("/hello", name="page")
     */
    public function index()
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/auth", name="auth")
     * @IsGranted("ROLE_USER", message="No access!")
     */
    public function auth()
    {
        return $this->render("page/index.html.twig");
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN", message="No access!")
     */
    public function admin()
    {
        return $this->render("page/index.html.twig");
    }

    /**
     * @Route("/mail", name="mail")
     */
    public function mail(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message("Hello", "Hello"))
            ->setFrom("noreply@domain.fr")
            ->setTo("contact@domain.fr");
        $mailer->send($message);

        return new Response("Hello");

    }
}
