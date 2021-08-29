<?php

namespace App\NewsParser\Interfaces;

interface ParserInterface
{
    public function setParseStrategy(ParseStrategyInterface $parseStrategy): void;
    public function getNewsList(): array;
    public function createNewsItemJobs(array $newsList): void;
    public function getNewsItem(string $uri): array;
}
