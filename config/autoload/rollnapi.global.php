<?php

 return array(
    'rollnapi' => [
        'api_entities' => [
            'artist' => [
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'service_name' => 'Artist',
                'entity_class' => 'Db\Entity\Artist',
                'page_size_param' => 'limit',
                'route_identifier_name' => 'artist_id',
                'entity_identifier_name' => 'id',
                'route_match' => '/api/artist',
                'by_value' => true,
                'use_generated_hydrator' => true,
            ],
            'album' => [
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'service_name' => 'Album',
                'entity_class' => 'Db\Entity\Album',
                'page_size_param' => 'limit',
                'route_identifier_name' => 'album_id',
                'entity_identifier_name' => 'id',
                'route_match' => '/api/album',
                'by_value' => true,
                'use_generated_hydrator' => true,
            ],
        ],
    ],


    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'path' => __DIR__ . '/../../data/Database/rollnapi.db'
                )
            ),
        )
    ),
);

