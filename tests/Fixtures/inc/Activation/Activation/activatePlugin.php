<?php

use LaunchpadCore\Activation\ActivationInterface;
use LaunchpadCore\Tests\Fixtures\inc\Activation\Activation\classes\ActivatorServiceProvider;
use LaunchpadCore\Tests\Fixtures\inc\Activation\Activation\classes\ServiceProvider;
use LaunchpadCore\Tests\Fixtures\inc\Activation\Activation\classes\VisibleServiceProvider;


$activator = Mockery::mock(ActivationInterface::class);

$provider = Mockery::mock(ServiceProvider::class);

$visible_provider = Mockery::mock(VisibleServiceProvider::class);

$activator_provider = Mockery::mock(ActivatorServiceProvider::class);

return [
    'testShouldLoadActivator' => [
        'config' => [
            'activators' => [
                $activator
            ],
            'params' => [],
            'providers' => [
                [
                    'provider' => $provider,
                    'callbacks' => [
                        'get_activators' => [],
                    ]
                ],
                [
                    'provider' => $visible_provider,
                    'callbacks' => [
                        'get_activators' => [],
                    ]
                ],
                [
                    'provider' => $activator_provider,
                    'callbacks' => [
                        'get_activators' => [get_class($activator)],
                    ]
                ],
            ]
        ],
        'expected' => [
            'providers' => [
                $visible_provider,
                $activator_provider
            ],
            'activators' => [
                $activator
            ]
        ]
    ]
];
