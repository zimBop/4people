<?php

namespace App\NewsParser\Interfaces;

interface ParseStrategyInterface
{
    public function parseNewsLinks(string $newsListString): array;
    public function parseNewsItem(string $newsItemString): string;
}
