<?php

namespace App\Jobs;

use App\NewsParser\Parser;
use App\NewsParser\ParseStrategyFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Models\Image;
use App\Models\News;

class GetNewsItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $resource;

    private string $uri;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $resource, string $uri)
    {
        $this->resource = $resource;
        $this->uri = $uri;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client)
    {
        $parseStrategyFactory = new ParseStrategyFactory();
        $parseStrategy = $parseStrategyFactory->getParseStrategy($this->resource);

        $parser = new Parser($this->resource);
        $parser->setParseStrategy($parseStrategy);
        $parser->setHttpClient($client);

        $newsData = $parser->getNewsItem($this->uri);

        if (empty($newsData['header']) || empty($newsData['text'])) {
            return;
        }

        $newsModel = News::create($newsData);

        if ($newsData['image']) {
            $image = Image::create([
                'src' => $newsData['image']
            ]);
            $newsModel->image()->associate($image);
            $newsModel->save();
        }
    }
}
