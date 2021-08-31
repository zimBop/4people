<?php

namespace App\NewsParser;

use Symfony\Component\DomCrawler\Crawler;

class RbkParseStrategy extends AbstractParseStrategy
{
    private Crawler $crawler;

    private array $config;

    public function __construct()
    {
        $this->config = config('news_resources.' . ParserConstants::RBK_RESOURCE);

        $this->crawler = new Crawler('', $this->config['url']);
    }

    public function parseNewsLinks(): array
    {
        $newsListHtml = $this->getNewsListHtml();

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

    private function getNewsListHtml(): string
    {
        $this->browser
            ->visit($this->config['url'])
            // Scroll down page to send 'get-news-feed' ajax request and receive full news list
            ->driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');

        return $this->browser
            ->pause(100)
            // $.active - counter for active jQuery ajax requests
            // When response received $.active will be == 0
            ->waitUntil('!$.active')
            ->driver->getPageSource();
    }

    public function parseNewsItem(string $uri): array
    {
        $result = [
            'original_uri' => $uri,
        ];

        $newsItemHtml = $this->sendHttpRequest($uri);

        $this->crawler->addHtmlContent($newsItemHtml);
        $selectors = $this->config['newsItemSelectors'];

        $imageNodes = $this->crawler->filter($selectors['image']);
        $result['image'] = $imageNodes->count() ? $imageNodes->image()->getUri() : '';

        $headerNode = $this->crawler->filter($selectors['header']);
        $result['header'] = $headerNode->count() ? $headerNode->text() : '';

        $overviewNode = $this->crawler->filter($selectors['overview']);
        $result['overview'] = $overviewNode->count() ? $overviewNode->text() : '';

        $textNodes = $this->crawler->filter($selectors['text']);
        $result['text'] = '';
        $textNodes->each(function ($paragraph) use (&$result) {
            $result['text'] .= '<p>' . $paragraph->text() . '</p>>';
        });

        return $result;
    }
}
