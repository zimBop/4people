<?php

namespace App\NewsParser;

use App\NewsParser\Interfaces\ParseStrategyInterface;
use Symfony\Component\DomCrawler\Crawler;

class RbkParseStrategy implements ParseStrategyInterface
{
    private Crawler $crawler;

    private array $config;

    public function __construct()
    {
        $this->crawler = new Crawler();

        $this->config = config('news_resources.' . ParserConstants::RBK_RESOURCE);
    }

    public function parseNewsLinks(string $newsListHtml): array
    {
        $this->crawler->addHtmlContent($newsListHtml);

        $selector = $this->config['newsListSelector'];
        $linkObjects = $this->crawler->filter($selector)->links();

        $links = [];
        $i = 0;
        while (isset($linkObjects[$i]) && count($links) < ParserConstants::MAX_NEWS_NUMBER) {
            $uri = $linkObjects[$i]->getUri();
            $host = parse_url($uri)['host'];

            // Rbk news feed may contain links to these hosts.
            // But this is not news, so we have to skip them.
            if (in_array($host, $this->config['host_blacklist'])) {
                $i++;
                continue;
            }

            $links[] = $this->removeQueryString($uri);
            $i++;
        }

        return $links;
    }

    public function parseNewsItem(string $newsItemHtml): array
    {
        $result = [];

        $this->crawler->addHtmlContent($newsItemHtml);
        $selectors = $this->config['newsItemSelectors'];

        $imageNodes = $this->crawler->filter($selectors['image']);
        $result['image'] = $imageNodes->count() ? $imageNodes->image()->getUri() : '';

        $headerNode = $this->crawler->filter($selectors['header']);
        $result['header'] = $headerNode->count() ? $headerNode->text() : '';

        $overviewNode = $this->crawler->filter($selectors['overview']);
        $result['text'] = $overviewNode->count() ? $overviewNode->outerHtml() : '';

        $textNodes = $this->crawler->filter($selectors['text']);
        $textNodes->each(function ($paragraph) use (&$result) {
            $result['text'] .= $paragraph->outerHtml();
        });

        return $result;
    }

    private function removeQueryString(string $uri): string
    {
        return strtok($uri, '?');
    }
}
