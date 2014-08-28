<?php

namespace Nfq\Fairytale\ApiBundle\Features\Context;

use Behat\Behat\Context\CustomSnippetAcceptingContext;
use Behat\WebApiExtension\Context\WebApiContext;

/**
 * Feature context.
 */
class FeatureContext extends WebApiContext implements CustomSnippetAcceptingContext
{
    /**
     * Returns type of the snippets that this context accepts.
     *
     * Behat implements a couple of types by default: "regex" and "turnip"
     *
     * @return string
     */
    public static function getAcceptedSnippetType()
    {
        return "regex";
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

    private function logIn()
    {
        // TODO: implement this
    }
}
