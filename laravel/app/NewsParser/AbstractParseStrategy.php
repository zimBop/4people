<?php

namespace App\NewsParser;

use GuzzleHttp\ClientInterface;
use Laravel\Dusk\Browser;
use App\NewsParser\Interfaces\ParseStrategyInterface;

abstract class AbstractParseStrategy implements ParseStrategyInterface
{
    protected Browser $browser;

    private ClientInterface $client;

    public function setBrowser(Browser $browser): void
    {
        $this->browser = $browser;
    }

    public function setHttpClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    protected function removeQueryString(string $uri): string
    {
        return strtok($uri, '?');
    }

    protected function sendHttpRequest(string $uri): string
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

    abstract public function parseNewsLinks(): array;
    abstract public function parseNewsItem(string $uri): array;
}
