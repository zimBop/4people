<?php

namespace App\NewsParser;

use App\Exceptions\InvalidArgumentException;
use App\NewsParser\Interfaces\ParseStrategyInterface;

class ParseStrategyFactory
{
    public function getParseStrategy(string $resource): ParseStrategyInterface
    {
        if ($resource === ParserConstants::RBK_RESOURCE) {
            return new RbkParseStrategy();
        }

        throw new InvalidArgumentException('Unknown news resource.');
    }
}
