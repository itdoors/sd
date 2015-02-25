<?php

namespace Lists\ProjectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectBaseControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router');
        $client->request('GET', $route->generate('lists_project_simple_create'));
        $this->assertTrue($client->getResponse()->isRedirect());
        $pageLogin = $client->followRedirect();
        $this->assertTrue($pageLogin->filter('button#_submit')->count() > 0);
        $form = $pageLogin->filter('#_submit')->form();
        $form['_username'] = 'admin';
        $form['_password'] = '23er2fq2WEdallas23er2fq2WE';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $pageProject = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($pageProject->filter('html:contains("Проект")')->count() > 0);
    }
}
