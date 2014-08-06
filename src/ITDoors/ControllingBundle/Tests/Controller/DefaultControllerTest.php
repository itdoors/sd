<?php

namespace ITDoors\ControllingBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * DefaultControllerTest
 * 
 */
class DefaultControllerTest extends WebTestCase
{

    /**
     * testIndex
     * 
     */
    public function test1Index()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app_dev.php/controlling/invoice');

        $this->assertTrue($crawler->filter('html:contains("Фильтры")')->count() > 0);
    }
    /**
     * testIndex
     * 
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app_dev.php/controlling/invoice/489');

        $this->assertTrue($crawler->filter('html:contains("Акт")')->count() > 0);
    }
}
