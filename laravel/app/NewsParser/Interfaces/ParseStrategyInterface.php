<?php

namespace App\NewsParser\Interfaces;

interface ParseStrategyInterface
{
    public function parseNewsLinks(string $newsListHtml): array;
    public function parseNewsItem(string $newsItemHtml): array;
}
