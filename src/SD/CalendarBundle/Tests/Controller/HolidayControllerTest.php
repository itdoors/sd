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
        $crawler = $client->request('GET', '/holiday');
        $this->assertTrue($client->getResponse()->isRedirect());
        $pageLogin = $client->followRedirect();
        $this->assertTrue($pageLogin->filter('button#_submit')->count() > 0);
        $form = $pageLogin->filter('#_submit')->form();
        $form['_username'] = 'admin';
        $form['_password'] = '23er2fq2WEdallas23er2fq2WE';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $pageHoliday = $client->followRedirect();
        $this->assertTrue($pageHoliday->filter('html:contains("Праздники")')->count() > 0);
    }
}
