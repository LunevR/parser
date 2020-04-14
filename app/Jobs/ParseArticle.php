<?php

namespace App\Jobs;

use App\Article;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use \Exception;

class ParseArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array */
    private $config;

    /** @var string */
    private $link;

    /** @var string */
    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $type, string $link, string $id)
    {
        $this->config = config('grabber.' . $type);

        if (!$this->config) {
            Log::channel('grabber')->error('Incorrect type ' . $type . ' for grabber');
        }

        $this->link = $link;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $this->link);
            $body = $response->getBody();

            $content = $this->getContent($body);

            Article::create($content);
        } catch (Exception $e) {
            Log::channel('grabber')->warning($e->getMessage());
        }
    }

    /**
     * Method get content from stream and return data for articles.
     * @var Stream $body
     *
     * @throws Exception
     *
     * @return array
     */
    private function getContent(Stream $body): array
    {
        $crawler = new Crawler(null, $this->link);
        $crawler->addHtmlContent($body, 'UTF-8');
        $crawler = $crawler->filter($this->config['filters']['container']);

        if ($crawler->count() === 0) {
            throw new Exception ('Can\'t parse container for url ' . $this->link);
        }

        $headNode = $crawler->filter($this->config['filters']['head'])->first();
        $imageNode = $crawler->filter($this->config['filters']['image'])->first();
        $textList = $crawler->filter($this->config['filters']['text'])
            ->each(function (Crawler $node) {
                return trim($node->text());
            });

        if ($headNode->count() === 0 || empty($textList)) {
            throw new Exception ('Can\'t parse content for url ' . $this->link);
        }

        return [
            'original_id' => $this->id,
            'title' => $headNode->text(),
            'image' => $imageNode->count() ? $imageNode->image()->getUri() : null,
            'body' => implode(PHP_EOL, $textList),
        ];
    }
}
