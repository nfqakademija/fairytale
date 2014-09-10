<?php

namespace Nfq\Fairytale\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TemplateController extends Controller
{
    public function partialAction($template)
    {
        return $this->render("NfqFairytaleFrontendBundle:Template:{$template}.html.twig");
    }
}
