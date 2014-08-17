<?php

namespace Nfq\Fairytale\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NfqFairytaleFrontendBundle:Default:index.html.twig');
    }
}
