<?php

return [

    'rbk' => [
        'url' => 'https://www.rbc.ru/',
        'newsListSelector' => '.js-news-feed-list a',
        'newsItemSelectors' => [
            'header' => 'h1.article__header__title-in, h1.article__header, .article__header h1',
            'overview' => '.article__text > .article__text__overview, .article__overview > .article__subtitle, .article__header__anons',
            'text' => '.article__text > p, .article__text .l-base__col__main > p',
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
