<?php


namespace App\Mapping;


class ServiceMethodCountUrls
{
    static $methodsList = [
        'ecs' => [
            'url' => '/cloudservers/detail',
            'name' => 'Elastic Cloud Server',
            'countMethod' => 'count'
        ],
        'rdsv3' => [
            'url' => '/instances',
            'countMethod' => 'total_count',
            'name' => 'Relational Database Service'
        ],
        'evs' => [
            'url' => '/cloudvolumes/detail',
            'countMethod' => 'count',
            'name' => 'Elastic Volume Service'
        ]
    ];
}