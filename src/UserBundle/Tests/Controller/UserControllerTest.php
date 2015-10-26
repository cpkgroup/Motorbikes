<?php

namespace UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $this->signupAndLoginTest($client, $user_name, $user_pass);

        // edit user
        $crawler = $client->click($crawler->selectLink('alex tester')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'userbundle_user[name]' => 'tomy',
            'userbundle_user[family]' => 'tester',
            'userbundle_user[email]' => $user_name,
            'userbundle_user[mobile]' => '09193049624',
        ));
        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('td:contains("tomy")')->count(), 'User edit failed!');


        // logout
        $crawler = $client->click($crawler->selectLink('Logout')->link());
        $this->assertEquals(0, $crawler->filter('a:contains("Logout"),a:contains("tomy tester")')->count(),
            'users logout failed!');

    }


    public function signupAndLoginTest($client, &$user_name = '', &$user_pass = '')
    {
        // Create a new entry in the database
        $crawler = $client->request('GET', '/user/signup');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET //user/signup");

        // Fill in the form and submit it
        $testEmail = 'test' . rand(1, 99999999) . '@gmail.com';
        $user_name = $testEmail;
        $user_pass = '1234';
        // signup
        $form = $crawler->selectButton('Signup')->form(array(
            'userbundle_user[name]' => 'alex',
            'userbundle_user[family]' => 'tester',
            'userbundle_user[email]' => $testEmail,
            'userbundle_user[passwd][password]' => $user_pass,
            'userbundle_user[passwd][confirm]' => $user_pass,
            'userbundle_user[mobile]' => '09193049624',
        ));

        $crawler = $client->submit($form);

        // Check signup result
        $this->assertGreaterThan(0, $crawler->filter('div:contains("You are signed up successfully")')->count(),
            'users signup failed!');

        // login
        $form = $crawler->selectButton('Login')->form(array(
            '_username' => $testEmail,
            '_password' => $user_pass,
        ));


        $crawler = $client->submit($form);

        // Check login result
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Logout"),a:contains("alex tester")')->count(),
            'users login failed!');

        return $crawler;
    }


}
