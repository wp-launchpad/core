<?php

use RocketLauncherCore\Deactivation\DeactivationInterface;
use RocketLauncherCore\Tests\Fixtures\inc\Deactivation\Deactivation\classes\DeactivatorServiceProvider;
use RocketLauncherCore\Tests\Fixtures\inc\Deactivation\Deactivation\classes\ServiceProvider;
use RocketLauncherCore\Tests\Fixtures\inc\Deactivation\Deactivation\classes\VisibleServiceProvider;

$deactivator = Mockery::mock(DeactivationInterface::class);

$provider = Mockery::mock(ServiceProvider::class);

$visible_provider = Mockery::mock(VisibleServiceProvider::class);

$deactivator_provider = Mockery::mock(DeactivatorServiceProvider::class);

return [
    'testShouldLoadDeactivator' => [
        'config' => [
            'deactivators' => [
                $deactivator
            ],
            'params' => [],
            'providers' => [
                [
                    'provider' => $provider,
                    'callbacks' => [
                        'get_deactivators' => [],
                    ]
                ],
                [
                    'provider' => $visible_provider,
                    'callbacks' => [
                        'get_deactivators' => [],
                    ]
                ],
                [
                    'provider' => $deactivator_provider,
                    'callbacks' => [
                        'get_deactivators' => [get_class($deactivator)],
                    ]
                ],
            ]
        ],
        'expected' => [
            'providers' => [
                $visible_provider,
                $deactivator_provider
            ],
            'deactivators' => [
                $deactivator
            ]
        ]
    ]
];
