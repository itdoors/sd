<?php

namespace ITDoors\OperBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * DefaultControllerTest class
 *
 * Testing class
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * testAction
     *
     */

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
}
