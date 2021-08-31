<?php

namespace App\NewsParser;

use App\Jobs\GetNewsItemJob;
use GuzzleHttp\ClientInterface;
use App\NewsParser\Interfaces\ParserInterface;
use App\NewsParser\Interfaces\ParseStrategyInterface;

class Parser implements ParserInterface
{
    private ParseStrategyInterface $parseStrategy;

    private string $resource;

    public function __construct(string $resource)
    {
        $this->resource = $resource;
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
        return $this->parseStrategy->parseNewsLinks();
    }

    public function createNewsItemJobs(array $newsList): void
    {
        foreach ($newsList as $newsUri) {
            GetNewsItemJob::dispatch($this->resource, $newsUri);
        }
    }

    public function getNewsItem(string $uri): array
    {
        return $this->parseStrategy->parseNewsItem($uri);
    }
}
