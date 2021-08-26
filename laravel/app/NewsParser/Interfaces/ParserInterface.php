<?php

namespace App\NewsParser\Interfaces;

interface ParserInterface
{
    public function setParseStrategy(ParseStrategyInterface $parseStrategy): void;
    public function getNewsList(): string;
    public function getNewsItem(): string;
}
