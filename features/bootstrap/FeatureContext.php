<?php

/*
 * This file is part of the CCDNMessage MessageBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNMessage\MessageBundle\features\bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CCDNMessage\MessageBundle\features\bootstrap\WebUser;

/**
 *
 * Features context.
 *
 * @category CCDNMessage
 * @package  MessageBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNMessageMessageBundle
 *
 */
class FeatureContext extends RawMinkContext implements KernelAwareInterface
{
    /**
     *
     * Kernel.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     *
     * Parameters.
     *
     * @var array
     */
    private $parameters;

    /**
     *
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->parameters = $parameters;

        // Web user context.
        $this->useContext('web-user', new WebUser());
    }

    /**
     *
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     *
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $entityManager = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($entityManager);
        $purger->purge();
    }

    private function getPage()
    {
        return $this->getMainContext()->getSession()->getPage();
    }

    /**
     *
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs($user)
    {
        $session = $this->getMainContext()->getSession();
        $session->setBasicAuth($user . '@foo.com', 'root');
    }

    /**
     * @Given /^I should see envelope "([^"]*)"$/
     */
    public function iShouldSeeEnvelope($messageSubject)
    {
	    $this->iShouldSeeForTheQuery($messageSubject, 'table > tbody > tr > td');
    }

    /**
     * @Given /^I should not see envelope "([^"]*)"$/
     */
    public function iShouldNotSeeEnvelope($messageSubject)
    {
	    $this->iShouldNotSeeForTheQuery($messageSubject, 'table > tbody > tr > td');
    }

    /**
     * @Given /^I should see envelope "([^"]*)" is read$/
     */
    public function iShouldSeeEnvelopeIsRead($messageSubject)
    {
        $elements = $this->getPage()->findAll('css', 'table > tbody > tr');

        $didFindSubject = false;
		$didFindIcon = false;
        $textLower = strtolower($messageSubject);
        foreach ($elements as $element) {
            if (strpos(strtolower($element->getText()), $textLower) !== false) {
                $didFindSubject = true;
				
				if ($element->has('css', 'i.glyphicon-envelope')) {
					$didFindIcon = true;
				}
            }
        }

        WebTestCase::assertTrue($didFindSubject, "$messageSubject was not found.");
        WebTestCase::assertFalse($didFindIcon, "$messageSubject is unread but should be read.");
    }

    /**
     * @Given /^I should see envelope "([^"]*)" is unread$/
     */
    public function iShouldSeeEnvelopeIsUnread($messageSubject)
    {
        $elements = $this->getPage()->findAll('css', 'table > tbody > tr');

        $didFindSubject = false;
		$didFindIcon = false;
        $textLower = strtolower($messageSubject);
        foreach ($elements as $element) {
            if (strpos(strtolower($element->getText()), $textLower) !== false) {
                $didFindSubject = true;
				
				if ($element->has('css', 'i.glyphicon-envelope')) {
					$didFindIcon = true;
				}
            }
        }
        
        WebTestCase::assertTrue($didFindSubject, "$messageSubject was not found.");
        WebTestCase::assertTrue($didFindIcon, "$messageSubject is read but should be unread.");
    }

    /**
     * @Given /^I check envelope "([^"]*)"$/
     */
    public function iCheckEnvelope($messageSubject)
    {
        $elements = $this->getPage()->findAll('css', 'table > tbody > tr');

        $textLower = strtolower($messageSubject);
        $didFindSubject = false;
		$didFindCheckbox = false;
		$checkbox = null;
        foreach ($elements as $element) {
            if (strpos(strtolower($element->getText()), $textLower) !== false) {
                $didFindSubject = true;
				
				if ($element->has('css', 'input[type="checkbox"]')) {
					$didFindCheckbox = true;
					$checkbox = $element->find('css', 'input[type="checkbox"]');
				}
            }
        }
        
        WebTestCase::assertTrue($didFindSubject, "$messageSubject was not found.");
        WebTestCase::assertTrue($didFindCheckbox, "$messageSubject was not found and could not be checked.");

		$checkbox->check();
    }

    /**
     * @Given /^I should see message preview "([^"]*)"$/
     */
    public function iShouldSeeMessagePreview($messageSubject)
    {
	    $this->iShouldSeeForTheQuery($messageSubject, 'table.message-preview > tbody > tr > td');
    }

    /**
     * @Given /^I should see message "([^"]*)"$/
     */
    public function iShouldSeeMessage($messageBody)
    {
	    $this->iShouldSeeForTheQuery($messageBody, 'table.message > tbody > tr > td > article');
    }

    /**
     *
     * @Given /^I should see "([^"]*)" for the query "([^"]*)"$/
     */
    public function iShouldSeeForTheQuery($text, $cssQuery)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function($element) { return strtolower($element->getText()); },
            $this->getPage()->findAll('css', $cssQuery)
        );
    
        $didFindIt = false;
        $textLower = strtolower($text);
        foreach ($items as $item) {
            if (strpos($item, $textLower) !== false) {
                $didFindIt = true;
                break;
            }
        }
    
        WebTestCase::assertTrue($didFindIt, "$text was not found.");
    }

    /**
     *
     * @Given /^I should not see "([^"]*)" for the query "([^"]*)"$/
     */
    public function iShouldNotSeeForTheQuery($text, $cssQuery)
    {
        // http://neverstopbuilding.net/simple-method-for-checking-for-order-with-behat/
        $items = array_map(
            function ($element) { return strtolower($element->getText()); },
            $this->getPage()->findAll('css', $cssQuery)
        );
    
        $didFindIt = false;
        $textLower = strtolower($text);
        foreach ($items as $item) {
            if (strpos($item, $textLower) !== false) {
                $didFindIt = true;
                break;
            }
        }
    
        WebTestCase::assertFalse($didFindIt, "$text was found but should not.");
    }
}
