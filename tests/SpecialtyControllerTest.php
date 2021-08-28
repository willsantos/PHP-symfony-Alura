<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpecialtyControllerTest extends WebTestCase
{
    public function testEnsuresRequestFailsWithoutAuthentication()
    {
        $client = static::createClient();
        $client->request('GET','/especialidades');
        
        self::assertEquals(401,$client->getResponse()->getStatusCode());
    }

    public function testEnsureSpecialitiesIsListed()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request('GET','/especialidades',[],[],
            ['HTTP_AUTHORIZATION' => "Bearer $token"]
        );

        $response = json_decode($client->getResponse()->getContent());
        self::assertTrue($response->sucess);
    }

    public function testInsertSpecialty()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request(
            'POST',
            '/especialidades',
            [],
            [],
            ['HTTP_AUTHORIZATION'=>"Bearer $token"],
            json_encode(['description'=>'teste'])
        );

        self:self::assertEquals(201,$client->getResponse()->getStatusCode());
    }

    private function login(KernelBrowser $browser): string
    {
        $browser->request(
            'POST',
            '/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode([
                'user'=> 'will',
                'password'=> '123456'
            ])
        );

        return json_decode($browser->getResponse()->getContent())->access_token;
    }

    public function testHtmlSpecialties()
    {
        $client = self::createClient();
        $client->request('GET','/especialidades_html');
        $this->assertSelectorTextContains('h1','Especialidades');
        $this->assertSelectorExists('.especialidade');
    }
}