<?php

require_once __DIR__ . '/src/MediaHub.php';

Kirby::plugin(
    name: 'my-company/media-block',
    extends: [
        
        'blueprints' => [
            'blocks/media' => __DIR__ . '/blueprints/blocks/media.yml',
        ],
        'snippets' => [
            'blocks/media' => __DIR__ . '/snippets/blocks/media.php',
        ],
        'areas' => [    
            'media-hub' => function () {
                return [
                    'icon'  => 'image',
                    'label' => 'Media Hub',
                    'menu'  => true,
                    'link'  => 'media-hub',
                    'views' => [
                        [
                            'pattern' => 'media-hub',
                            'action'  => function(){
                                return [
                                    'component' => 'k-media-hub-view',
                                    'title'     => 'Media Hub',
                                    'props'     => MediaHub::payload(kirby()),
                                ];
                            },
                        ],
                    ],
                ];
            },
        ],
    ],
    info: [
        'description' => 'Media Hub panel area and layout media block',
        'authors' => [
            ['name' => 'My Company'],
        ],
    ],
    version: '1.0.0',
    license: 'MIT',
);
