<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UserBundle\Tests\Controller\UserControllerTest;

class MotorbikesControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $client->followRedirects();

        $userTest = new UserControllerTest();
        $crawler = $userTest->signupAndLoginTest($client, $user_name, $user_pass);


        // sort desc test
        $crawler = $client->request('GET', '/motorbikes/sort/price/sort_type/desc/page/1');
        $this->assertEquals(0, $crawler->filter('[href="/motorbikes/sort/price/sort_type/desc/page/1"]')->count(), 'sort desc failed!');


        // sort asc test
        $crawler = $client->request('GET', '/motorbikes/sort/price/sort_type/asc/page/1');
        $this->assertEquals(0, $crawler->filter('[href="/motorbikes/sort/price/sort_type/asc/page/1"]')->count(), 'sort asc failed!');


        $photo = new UploadedFile(
            'test.jpeg',
            'test.jpg',
            'image/jpeg',
            123
        );
        $this->addNewMotorbike($client, $crawler, $photo);


        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());
        $myrandom = 'Test model editted' . rand(100000, 999999);
        $form = $crawler->selectButton('Update')->form(array(
            'appbundle_motorbikes[model]' => $myrandom,
            'appbundle_motorbikes[cc]' => 'Test cc',
            'appbundle_motorbikes[color]' => 'green',
            'appbundle_motorbikes[weight]' => '300',
            'appbundle_motorbikes[price]' => '6000',
            'appbundle_motorbikes[image]' => $photo,
        ));

        $crawler = $client->submit($form);

        //check edit result
        $this->assertGreaterThan(0, $crawler->filter('td:contains("' . $myrandom . '")')->count(), 'Missing element td:contains("' . $myrandom . '")');

        // Delete the entity
        $crawler = $client->submit($crawler->selectButton('Delete')->form());

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/' . $myrandom . '/', $client->getResponse()->getContent());


        // pagination test
        for ($i = 0; $i < 20; $i++) {
            $photo = new UploadedFile(
                'test.jpeg',
                'test.jpg',
                'image/jpeg',
                123
            );
            $this->addNewMotorbike($client, $crawler, $photo, 'Test model' . $i, 'cc', 'red', 1000 * rand(1, 100), 1000 * rand(1, 100));
            $crawler = $client->click($crawler->selectLink('Back to the list')->link());
        }
        $this->assertGreaterThan(0, $crawler->filter('ul.pagination')->count(), 'pagination error!")');

    }


    private function addNewMotorbike(&$client, &$crawler, $photo, $model = 'Test model', $cc = 'Test cc', $color = 'red'
        , $weight = 2000, $price = 6000)
    {

        //add new record
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_motorbikes[model]' => $model,
            'appbundle_motorbikes[cc]' => $cc,
            'appbundle_motorbikes[color]' => $color,
            'appbundle_motorbikes[weight]' => $weight,
            'appbundle_motorbikes[price]' => $price,
            'appbundle_motorbikes[image]' => $photo,
        ));

        $crawler = $client->submit($form);

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("' . $model . '")')->count(), 'Missing element td:contains("' . $model . '")');
    }

}
