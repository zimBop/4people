<?php

namespace App\NewsParser;

use App\Jobs\GetNewsItemJob;
use GuzzleHttp\ClientInterface;
use App\NewsParser\Interfaces\ParserInterface;
use App\NewsParser\Interfaces\ParseStrategyInterface;

class Parser implements ParserInterface
{
    private ClientInterface $client;

    private ParseStrategyInterface $parseStrategy;

    private string $resource;

    public function __construct(string $resource)
    {
        $this->resource = $resource;
    }

    private function getResourceUrl(): string
    {
        return config('news_resources.' . $this->resource . '.url');
    }

    public function setParseStrategy(ParseStrategyInterface $parseStrategy): void
    {
        $this->parseStrategy = $parseStrategy;
    }

    public function setHttpClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    public function getNewsList(): array
    {
        $data = $this->sendRequest($this->getResourceUrl());

        return $this->parseStrategy->parseNewsLinks($data);
    }

    public function createNewsItemJobs(array $newsList): void
    {
        foreach ($newsList as $newsUri) {
            GetNewsItemJob::dispatch($this->resource, $newsUri);
        }
    }

    public function getNewsItem(string $uri): array
    {
        return $this->parseStrategy->parseNewsItem(
            $this->sendRequest($uri)
        );
    }

    private function sendRequest(string $uri): string
    {
        try {
            $response = $this->client->request('GET', $uri);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception();
            }

            return $response->getBody()->getContents();

        } catch (\Exception $e) {
            // TODO handle exception
            return '';
        }
    }
}
