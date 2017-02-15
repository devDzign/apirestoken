<?php

namespace France\TvBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ArticlesControllerTest extends WebTestCase
{

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'api', $password = 'apidoc')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            array(
                '_username' => $username,
                '_password' => $password,
            )
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
    
    public function testGetArticles()
    {
//        $client   = static::createClient();
        $client   = $this->createAuthenticatedClient();

        $crawler  = $client->request('GET', '/api/v1/articles/');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testNotGetArticles()
    {
        $client   = $this->createAuthenticatedClient();
        $crawler  = $client->request('GET', '/api/v1/articles');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_MOVED_PERMANENTLY, $response->getStatusCode());

       return $client->getResponse()->getContent();
    }

    public function testGetArticle()
    {
        $client   = $this->createAuthenticatedClient();
        $crawler  = $client->request('GET', '/api/v1/articles/1');
        $response = $client->getResponse();
        $data     = $client->getResponse()->getContent();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(count($data), 1);
    }


//    public function testCpostArticlesCreate()
//    {
//        $client   = $this->createAuthenticatedClient();
//        $crawler      = $client->request('POST', '/api/v1/articles/',
//            [
//                "title" => "test mourad",
//                "body" => "test mourad",
//                "leading" => "test mourad"
//            ]);
//        $response     = $client->getResponse();
//        $data         = $client->getResponse()->getContent();
//        $finishedData = json_decode($data, true);
//
//        $this->assertArrayHasKey('title', $finishedData);
//        $this->assertArrayHasKey('body', $finishedData);
//        $this->assertArrayHasKey('leading', $finishedData);
//        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
//        $this->assertEquals(count($data), 1);
//    }


//    public function testCpostArticlesCreateBadRequest()
//    {
//        $client   = $this->createAuthenticatedClient();
//        $crawler  = $client->request('POST', '/api/v1/articles/', []);
//        $response = $client->getResponse();
//        $data     = $client->getResponse()->getContent();
//
//        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
//        $this->assertEquals(count($data), 0);
//    }
    
}
