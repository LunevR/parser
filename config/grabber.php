<?php

return [
    'rbc' => [
        'link' => 'https://www.rbc.ru',
        'filters' => [
            'list' => 'body .js-news-feed-list',
            'container' => 'body .article',
            'head' => '.js-slide-title',
            'image' => 'img',
            'text' => '.article__text p',
        ],
        'queue' => 'rbc',
        'log' => 'rbc',
    ]
];
