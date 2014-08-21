<?php

namespace Nfq\Fairytale\ApiBundle\Features\Context;

use Behat\Behat\Context\Step\Given;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext //MinkContext if you want to test web
    implements KernelAwareInterface
{
    /** @var  KernelInterface */
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

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
        $session = $this->kernel->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken($username, null, $firewall, $roles);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $this->getSession()->setCookie($session->getName(), $session->getId());
    }
}
