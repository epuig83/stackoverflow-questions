<?php
namespace App\Service;

use App\Exception\StackOverFlowException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Questions
{
    /**
     * @var string
     */
    private string $uri;

    /**
     * @var string
     */
    private string $site;

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $client;

    /**
     * @var Filters
     */
    protected Filters $filters;

    public function __construct(string $uri, string $site, HttpClientInterface $client, Filters $filters)
    {
        $this->uri = $uri;
        $this->site = $site;
        $this->client = $client;
        $this->filters = $filters;
    }

    /**
     * @return Filters|null
     */
    public function getFilters(): ?Filters
    {
        return $this->filters;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'site' => $this->filters->getSite(),
            'tagged' => $this->filters->getTagged(),
            'fromdate' => $this->filters->getFromDate(),
            'todate' => $this->filters->getToDate()
        ];
    }

    /**
     * @param string $tagged
     * @param string|null $fromDate
     * @param string|null $toDate
     */
    public function addFilters(string $tagged, ?string $fromDate, ?string $toDate): void
    {
        $this->filters->setSite($this->site);
        $this->filters->setTagged($tagged);
        $this->filters->setFromDate($fromDate);
        $this->filters->setToDate($toDate);
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws StackOverFlowException
     */
    public function fetchQuestions(): array
    {
        $response = $this->client->request('GET', $this->uri, [
            'query' => $this->getParams()
        ]);

        if (200 !== $response->getStatusCode()) {
            throw StackOverFlowException::badStatusCode();
        }

        return $response->toArray();
    }
}