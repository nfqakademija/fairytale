<?php

namespace Nfq\Fairytale\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TemplateControllerTest extends WebTestCase
{
    public function testPartial()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/partial');
    }

}
