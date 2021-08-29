<?php

return [

    'rbk' => [
        'url' => 'https://www.rbc.ru/',
        'newsListSelector' => '.js-news-feed-list a',
        'newsItemSelectors' => [
            'header' => 'h1.article__header__title-in',
            'overview' => '.article__text > .article__text__overview',
            'text' => '.article__text > p',
            'image' => '.article__main-image img',
        ],

        // Rbk news feed may contain links to these hosts.
        // But this is not news or just parts of news like on 'pro.rbc.ru'.
        // So we have to skip them.
        'host_blacklist' => [
            'www.adv.rbc.ru',
            'traffic.rbc.ru',
            'pro.rbc.ru',
        ],
    ]

];
