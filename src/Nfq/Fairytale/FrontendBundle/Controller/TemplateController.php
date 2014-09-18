<?php

namespace Nfq\Fairytale\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TemplateController extends Controller
{
    public function partialAction($template)
    {
        return $this->render("NfqFairytaleFrontendBundle:Template:{$template}.html.twig");
    }

    /**
     * TODO: delete after project is done, temporary for building css
     *
     * @param $template
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blockAction($template)
    {
        return $this->render(
            "NfqFairytaleFrontendBundle:Default:temporary.html.twig",
            ["template" => $template]
        );
    }
}
