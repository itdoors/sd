<?php

namespace SD\CalendarBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HolidayControllerTest
 */
class HolidayControllerTest extends WebTestCase
{
    /**
     * testIndex
     * 
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app_dev.php/holiday');

        $this->assertTrue($crawler->filter('html:contains("Праздники")')->count() > 0);
    }
}
