<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MotorbikesControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '');
//        $this->assertEquals(500, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());
        $crawler = $client->followRedirect();

        $crawler = $client->click($crawler->selectLink('Signup')->link());

        $testEmail = 'test'.rand(100000,999999).'@gmail.com';
        // register
        $form = $crawler->selectButton('Signup')->form(array(
            'userbundle_user[name]'  => 'tomy',
            'userbundle_user[family]'  => 'tester',
            'userbundle_user[email]'  => $testEmail,
            'userbundle_user[passwd][password]'  => '1234',
            'userbundle_user[passwd][confirm]'  => '1234',
            'userbundle_user[mobile]'  => '09193049624',
        ));
        $client->submit($form);

        $crawler = $client->click($crawler->selectLink('Back')->link());

        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());
        $crawler = $client->followRedirect();

        // loggin
        $form = $crawler->selectButton('Login')->form(array(
            '_username'  => $testEmail,
            '_password'  => '1234',
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();


        // echo  $client->getResponse()->getContent();
        $photo = new UploadedFile(
            'test.jpeg',
            'test.jpg',
            'image/jpeg',
            123
        );

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_motorbikes[model]'  => 'Test model',
            'appbundle_motorbikes[cc]'  => 'Test cc',
            'appbundle_motorbikes[color]'  => 'red',
            'appbundle_motorbikes[weight]'  => '200',
            'appbundle_motorbikes[price]'  => '5000',
            'appbundle_motorbikes[image]'  => $photo,
        ));


        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test model")')->count(), 'Missing element td:contains("Test model")');




        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());
        $myrandom = 'Test model editted'.rand(100000,999999);
        $form = $crawler->selectButton('Update')->form(array(
            'appbundle_motorbikes[model]'  => $myrandom,
            'appbundle_motorbikes[cc]'  => 'Test cc',
            'appbundle_motorbikes[color]'  => 'green',
            'appbundle_motorbikes[weight]'  => '300',
            'appbundle_motorbikes[price]'  => '6000',
            'appbundle_motorbikes[image]'  => $photo,
        ));

        $client->submit($form);
        //echo  $client->getResponse()->getContent();
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        //$this->assertGreaterThan(0, $crawler->filter('[value="'.$myrandom.'"]')->count(), 'Missing element [value="'.$myrandom.'"]');
        $this->assertGreaterThan(0, $crawler->filter('td:contains("'.$myrandom.'")')->count(), 'Missing element td:contains("'.$myrandom.'")');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/'.$myrandom.'/', $client->getResponse()->getContent());
    }
	
}
