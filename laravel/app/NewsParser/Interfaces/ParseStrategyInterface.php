<?php

namespace App\NewsParser\Interfaces;

use Laravel\Dusk\Browser;
use GuzzleHttp\ClientInterface;

interface ParseStrategyInterface
{
    public function setBrowser(Browser $browser): void;
    public function setHttpClient(ClientInterface $client): void;
    public function parseNewsLinks(): array;
    public function parseNewsItem(string $uri): array;
}
