<?php

namespace App\NewsParser;

use App\Jobs\GetNewsItem;
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

    public function getNewsList(): string
    {
        try {
            $response = $this->client->request('GET', $this->getResourceUrl());

            if ($response->getStatusCode() !== 200) {
                throw new \Exception();
            }

            $data = $response->getBody()->getContents();

        } catch (\Exception $e) {
            // TODO handle exception
        }

        $newsList = $this->parseStrategy->parseNewsLinks($data);

        foreach ($newsList as $newsUri) {
            GetNewsItem::dispatch($newsUri);
        }
    }

    public function getNewsItem(): string
    {
        $this->parseStrategy->parseNewsItem();
    }
}
