<?php

namespace App\Console\Commands;

use App\NewsParser\Parser;
use App\NewsParser\ParseStrategyFactory;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ParseNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:news {resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse news from resource';

    private Client $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resource = $this->argument('resource');

        $parseStrategyFactory = new ParseStrategyFactory();
        $parseStrategy = $parseStrategyFactory->getParseStrategy($resource);

        $parser = new Parser($resource);
        $parser->setParseStrategy($parseStrategy);
        $parser->setHttpClient($this->client);

        $parser->createNewsItemJobs(
            $parser->getNewsList()
        );

        return 0;
    }
}
