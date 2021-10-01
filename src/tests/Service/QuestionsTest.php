<?php

namespace App\Tests\Service;

use App\Exception\StackOverFlowException;
use App\Service\Filters;
use App\Service\Questions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class QuestionsTest extends TestCase
{
    public function testGetParams(): void
    {
        $uri = 'https://api.stackexchange.com/2.3/questions';
        $site = 'stackoverflow';

        $filters = new Filters();
        $filters->setSite('stackoverflow');
        $filters->setTagged('php');
        $filters->setFromDate('2021-01-01');
        $filters->setToDate('2021-09-30');

        $expectedFilters = [
            'site' => $filters->getSite(),
            'tagged' => $filters->getTagged(),
            'fromdate' => $filters->getFromDate(),
            'todate' => $filters->getToDate()
        ];

        $expectedResponseData = [
            'items' => [],
            'has_more' => true,
            'quota_max' => 300,
            'quota_remaining' => 223
        ];
        $mockResponseJson = json_encode([], JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse, $site);

        $questions = new Questions($uri, $site, $httpClient, $filters);
        $responseData = $questions->getParams();

        self::assertEquals($responseData, $expectedFilters);
    }

    /**
     * @throws StackOverFlowException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testFetchQuestions(): void
    {
        $filters = new Filters();
        $filters->setSite('stackoverflow');
        $filters->setTagged('php');
        $filters->setFromDate('2021-01-01');
        $filters->setToDate('2021-09-30');

        $expectedResponseData = [
            'success' => true,
            'data' => [
                'items' => [],
                'has_more' => true,
                'quota_max' => 300,
                'quota_remaining' => 223,
            ]
        ];

        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $uri = 'https://api.stackexchange.com/2.3/questions';

        $site = 'stackoverflow';
        $httpClient = new MockHttpClient($mockResponse, $uri);

        $questions = new Questions($uri, $site, $httpClient, $filters);
        $responseData = $questions->fetchQuestions();
        $url = 'https://api.stackexchange.com/2.3/questions?site=stackoverflow&tagged=php&fromdate=2021-01-01&todate=2021-09-30';
        self::assertSame('GET', $mockResponse->getRequestMethod());
        self::assertSame($url, $mockResponse->getRequestUrl());
        self::assertEquals($responseData, $expectedResponseData);
    }

    /**
     * @throws StackOverFlowException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testFetchQuestions__throwsStackOverFlowBadStatusException(): void
    {
        $filters = new Filters();
        $filters->setSite('stackoverflow');
        $filters->setTagged('php');
        $filters->setFromDate('2021-01-01');
        $filters->setToDate('2021-09-30');

        $mockResponseJson = json_encode([], JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 404,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $uri = 'https://api.stackexchange.com/2.3/questions';
        $site = 'stackoverflow';
        $httpClient = new MockHttpClient($mockResponse, $uri);
        $questions = new Questions($uri, $site, $httpClient, $filters);

        self::expectException(StackOverFlowException::class);
        $questions->fetchQuestions();
    }

    public function testAddFilters(): void
    {
        $filters = new Filters();
        $filters->setSite('stackoverflow');
        $filters->setTagged('php');
        $filters->setFromDate('2021-01-01');
        $filters->setToDate('2021-09-30');

        $mockResponseJson = json_encode([], JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 404,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $uri = 'https://api.stackexchange.com/2.3/questions';
        $site = 'stackoverflow';
        $httpClient = new MockHttpClient($mockResponse, $uri);
        $questions = new Questions($uri, $site, $httpClient, $filters);

        $questions->addFilters('php', '2021-01-01', '2021-09-30');

        self::assertEquals($filters, $questions->getFilters());
    }
}