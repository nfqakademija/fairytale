<?php

namespace Nfq\Fairytale\ApiBundle\Features\Context;

use Assert\Assertion;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Nfq\Fairytale\CoreBundle\Entity\User;
use SebastianBergmann\Diff\Differ;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Feature context.
 */
class FeatureContext implements SnippetAcceptingContext
{
    use KernelDictionary;

    /** @var  Request */
    protected $request;

    /** @var  Client */
    private $client;

    /** @var  array */
    private $headers;

    /**
     * @Given I am authenticated as :login
     */
    public function iAmAuthenticatedAs($login)
    {
        /** @var User $user */
        $user = $this->getContainer()->get('fos_user.user_manager')->findUserByUsername($login);

        $session = $this->client->getContainer()->get('session');

        $this->client->getCookieJar()->set(new Cookie($session->getName(), true));

        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->getContainer()->get('security.context')->setToken($token);

        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * @BeforeScenario
     */
    public function cleanup()
    {
        $this->client = new Client($this->getKernel());
        $this->headers = [
            'CONTENT_TYPE' => 'application/json'
        ];
    }

    /**
     * @When I send a :method request to :uri
     */
    public function iSendARequestTo($method, $uri)
    {
        $this->client->request($method, $uri, [], [], $this->headers);
    }

    /**
     * @When I send a :method request to :uri with body:
     */
    public function iSendARequestToWithBody($method, $uri, PyStringNode $string)
    {
        $this->client->request($method, $uri, [], [], $this->headers, $string->getRaw());
    }

    /**
     * @Then the response code should be :code
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->client->getResponse()->getStatusCode());

        Assertion::same($expected, $actual);
    }

    /**
     * @Then the response should be json:
     */
    public function theResponseShouldBeJson(PyStringNode $string)
    {
        $expected = json_decode($string->getRaw(), true);
        $actual = json_decode($this->client->getResponse()->getContent(), true);

        Assertion::eq(
            $expected,
            $actual,
            (new Differ())->diff(json_encode($expected, JSON_PRETTY_PRINT), json_encode($actual, JSON_PRETTY_PRINT))
        );
    }

    /**
     * @Then print last response
     */
    public function printLastResponse()
    {
        $response = $this->client->getResponse();
        $request = $this->client->getRequest();
        printf(
            "%s %d %s\n%s",
            $request->getMethod(),
            $response->getStatusCode(),
            $request->getUri(),
            $response->getContent()
        );
    }

    /**
     * @When I set header :header to :value
     */
    public function iSetHeaderTo($header, $value)
    {
        $this->headers[$header] = $value;
    }
}
