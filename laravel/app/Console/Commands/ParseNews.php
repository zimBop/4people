<?php

namespace App\Console\Commands;

use App\NewsParser\Parser;
use App\NewsParser\ParseStrategyFactory;
use Illuminate\Console\Command;
use Laravel\Dusk\Browser;

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

    private Browser $browser;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Browser $browser)
    {
        parent::__construct();

        $this->browser = $browser;
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
        $parseStrategy->setBrowser($this->browser);

        $parser = new Parser($resource);
        $parser->setParseStrategy($parseStrategy);

        $parser->createNewsItemJobs(
            $parser->getNewsList()
        );

        return 0;
    }
}
