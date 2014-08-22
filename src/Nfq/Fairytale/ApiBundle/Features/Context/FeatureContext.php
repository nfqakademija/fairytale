<?php

namespace Nfq\Fairytale\ApiBundle\Features\Context;

use Behat\Mink\Exception\ExpectationException;
use Behat\WebApiExtension\Context\WebApiContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Feature context.
 */
class FeatureContext extends WebApiContext //MinkContext if you want to test web
{
    /**
     * @Given /^I have "([^"]*)" access token$/
     */
    public function iHaveAccessToken($type)
    {
        switch ($type) {
            case 'no':
                break;
            case 'user':
                $this->logIn('user', ['ROLE_USER']);
                break;
            case 'admin':
                $this->logIn('admin', ['ROLE_ADMIN', 'ROLE_USER']);
                break;
        }
    }

    private function logIn($username, $roles)
    {
//        $session = $this->kernel->getContainer()->get('session');
//
//        $firewall = 'main';
//        $token = new UsernamePasswordToken($username, null, $firewall, $roles);
//        $session->set('_security_' . $firewall, serialize($token));
//        $session->save();
//
//        $this->getSession()->setCookie($session->getName(), $session->getId());
    }
}
