<?php

namespace App\NewsParser;

use App\NewsParser\Interfaces\ParseStrategyInterface;
use Symfony\Component\DomCrawler\Crawler;

class RbkParseStrategy implements ParseStrategyInterface
{
    private Crawler $crawler;

    private const INCORRECT_URIS = [
        'http://www.adv.rbc.ru'
    ];

    public function __construct()
    {
        $this->crawler = new Crawler();
    }

    public function parseNewsLinks(string $newsListString): array
    {
        $this->crawler->addHtmlContent($newsListString);

        $selector = config('news_resources.' . ParserConstants::RBK_RESOURCE . '.newsListSelector');
        $linkObjects = $this->crawler->filter($selector)->links();

        $links = [];
        for ($i = 0; $i <= ParserConstants::MAX_NEWS_NUMBER; $i++) {
            if ($i + 1 > count($linkObjects)) {
                break;
            }

            $uri = $linkObjects[$i]->getUri();

            if (in_array($uri, self::INCORRECT_URIS)) {
                continue;
            }

            $links[] = $uri;
        }

        return $links;
    }

    public function parseNewsItem(string $newsItemString): string
    {
        // TODO: Implement parseNewsItem() method.
    }
}
