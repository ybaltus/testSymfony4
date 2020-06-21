<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends WebTestCase
{
    public function testSuccessResponses()
    {
        $client = $this->createClient();
        $client->request('GET', '/contact');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1Page()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');
    }

    public function testSendContactWithInvalidEmail(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('Sauvegarder')->form([
           'contact[firstname]' => 'yannis',
            'contact[lastname]' => 'baltus',
            'contact[mail]' => 'ninours971@gmail.com',
            'contact[address]' => 'Je vis en Guadeloupe !',
        ]);

        $client->submit($form);
        $this->assertSelectorExists('.invalid-feedback');
    }

    public function testMailSendContact()
    {
        $client=static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('Sauvegarder')->form([
            'contact[firstname]' => 'yannis',
            'contact[lastname]' => 'baltus',
            'contact[mail]' => 'yannistest3@gmail.com',
            'contact[address]' => 'Je vis en Guadeloupe !',
        ]);
        $client->submit($form);
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');
    }
}