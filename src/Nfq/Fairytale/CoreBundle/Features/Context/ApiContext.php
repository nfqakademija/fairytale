<?php

namespace Nfq\Fairytale\CoreBundle\Features\Context;

use Assert\Assertion;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use FOS\UserBundle\Model\User;
use Nfq\Fairytale\CoreBundle\Util\Arrays;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Feature context.
 */
class ApiContext implements SnippetAcceptingContext
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
     * @Then I should get JSON response with following format:
     */
    public function iShouldGetJsonResponseWithFollowingFormat(TableNode $table)
    {
        $response = $this->client->getResponse();
        $payload = json_decode($response->getContent(), true);
        $this->checkPayload($table, $payload);
    }

    /**
     * @Then I should get JSON collection with following format:
     */
    public function iShouldGetJsonCollectionWithFollowingFormat(TableNode $table)
    {
        $response = $this->client->getResponse();
        $collection = json_decode($response->getContent(), true);
        foreach ($collection as $payload) {
            $this->checkPayload($table, $payload);
        }
    }

    /**
     * @param TableNode $table
     * @param           $payload
     */
    private function checkPayload(TableNode $table, $payload)
    {
        $checked = [];
        foreach ($table as $row) {
            $checked[] = $row['field'];
            Assertion::same(gettype(Arrays::get($payload, $row['field'])), $row['type']);
        }
        $extraFields = array_diff_key($payload, array_flip($checked));
        Assertion::eq($extraFields, [],
            sprintf('Response had extra fields: %s', join(', ', array_keys($extraFields))));
    }
}
