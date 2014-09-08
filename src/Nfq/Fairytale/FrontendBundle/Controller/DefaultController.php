<?php

namespace Nfq\Fairytale\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $activeUser = ['id' => $this->get('security.context')->getToken()->getUser()->getId()];
        return $this->render(
            'NfqFairytaleFrontendBundle:Default:index.html.twig',
            ['activeUser' => $activeUser]
        );
    }
}
