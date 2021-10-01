<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuestionApiControllerTest extends WebTestCase
{
    public function testQuestions()
    {
        $tagged = 'php';
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/stackoverflow/questions/' . $tagged,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}