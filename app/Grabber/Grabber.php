<?php

namespace App\Grabber;

use App\Article;
use App\Jobs\ParseArticle;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class Grabber
{
    /**
     * Method get information from source and move it to queue for parsing.
     * @var string $type Type of grabber and job
     *
     * @return bool
     */
    public static function start(string $type): bool
    {
        $config = config('grabber.' . $type);

        if (!$config) {
            Log::channel('grabber')->error('Incorrect type ' . $type . ' for grabber');

            return false;
        }

        $client = new Client();
        $response = $client->request('GET', $config['link']);
        $body = $response->getBody();

        $crawler = new Crawler(null, $config['link']);
        $crawler->addHtmlContent($body, 'UTF-8');
        $crawler = $crawler->filter($config['filters']['list'])->children('a');

        foreach ($crawler as $domElement) {
            $link = $domElement->attributes['href']->value;
            $id = $domElement->getAttribute('id');

            if (Article::checkByOriginalId($id)) {
                ParseArticle::dispatch($type, $link, $id)->onQueue($config['queue']);
            }
        }

        return true;
    }
}
