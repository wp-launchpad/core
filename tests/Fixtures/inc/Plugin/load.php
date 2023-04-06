<?php
require_once __DIR__ . '/classes/AdminSubscriber.php';
require_once __DIR__ . '/classes/CommonSubscriber.php';
require_once __DIR__ . '/classes/FrontSubscriber.php';
require_once __DIR__ . '/classes/InitSubscriber.php';
require_once __DIR__ . '/classes/OptimizedSubscriber.php';

use LaunchpadCore\Container\IsOptimizableServiceProvider;
use LaunchpadCore\Container\ServiceProviderInterface;
use LaunchpadCore\Tests\Fixtures\inc\Plugin\classes\AdminSubscriber;
use LaunchpadCore\Tests\Fixtures\inc\Plugin\classes\CommonSubscriber;
use LaunchpadCore\Tests\Fixtures\inc\Plugin\classes\FrontSubscriber;
use LaunchpadCore\Tests\Fixtures\inc\Plugin\classes\InitSubscriber;
use LaunchpadCore\Tests\Fixtures\inc\Plugin\classes\OptimizedSubscriber;

$admin_subscriber = Mockery::mock(AdminSubscriber::class);
$front_subscriber = Mockery::mock(FrontSubscriber::class);
$init_subscriber = Mockery::mock(InitSubscriber::class);
$common_subscriber = Mockery::mock(CommonSubscriber::class);
$optimized_subscriber = Mockery::mock(OptimizedSubscriber::class);

$front_provider = Mockery::mock(ServiceProviderInterface::class);
$front_provider->allows()->get_init_subscribers()->andReturn([]);
$admin_provider = Mockery::mock(ServiceProviderInterface::class);

$common_provider = Mockery::mock(ServiceProviderInterface::class);

$init_provider = Mockery::mock(ServiceProviderInterface::class);

$optimizable_provider = Mockery::mock(IsOptimizableServiceProvider::class);

return [
    'testShouldLoadRightSubscribersOnFront' => [
        'config' => [
            'params' => [
                'prefix' => 'prefix'
            ],
            'subscribers' => [
                $admin_subscriber,
                $front_subscriber,
                $init_subscriber,
                $common_subscriber,
                $optimized_subscriber,
            ],
            'providers_callback' => [
                  [
                      'provider' => $front_provider,
                      'callbacks' => [
                          'get_front_subscribers' => [get_class($front_subscriber)],
                          'get_admin_subscribers' => [],
                          'get_common_subscribers' => [],
                          'get_init_subscribers' => [],
                      ],
                  ],
                [
                    'provider' => $admin_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [],
                        'get_admin_subscribers' => [get_class($admin_subscriber)],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [],
                    ],
                ],
                [
                    'provider' => $common_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [get_class($common_subscriber)],
                        'get_init_subscribers' => [],
                    ],
                ],
                [
                    'provider' => $init_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [get_class($init_subscriber)],
                    ],
                ],
                [
                    'provider' => $optimizable_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [get_class($optimized_subscriber)],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [],
                    ],
                ],
            ],
            'is_admin' => false,
        ],
        'expected' => [
            'share' => [
                'prefix' => 'prefix'
            ],
            'subscribers' => [
                $front_subscriber,
                $init_subscriber,
                $common_subscriber,
                $optimized_subscriber,
            ],
            'providers' => [
                $front_provider,
                $optimizable_provider,
                $init_provider,
                $common_provider,
                $admin_provider,
            ]
        ]
    ],
    'testShouldLoadRightSubscribersOnAdmin' => [
        'config' => [
            'params' => [
                'prefix' => 'prefix'
            ],
            'subscribers' => [
                $admin_subscriber,
                $front_subscriber,
                $init_subscriber,
                $common_subscriber,
                $optimized_subscriber,
            ],
            'providers_callback' => [
                [
                    'provider' => $front_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [get_class($front_subscriber)],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [],
                    ],
                ],
                [
                    'provider' => $admin_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [],
                        'get_admin_subscribers' => [get_class($admin_subscriber)],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [],
                    ],
                ],
                [
                    'provider' => $common_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [get_class($common_subscriber)],
                        'get_init_subscribers' => [],
                    ],
                ],
                [
                    'provider' => $init_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [get_class($init_subscriber)],
                    ],
                ],
                [
                    'provider' => $optimizable_provider,
                    'callbacks' => [
                        'get_front_subscribers' => [get_class($optimized_subscriber)],
                        'get_admin_subscribers' => [],
                        'get_common_subscribers' => [],
                        'get_init_subscribers' => [],
                    ],
                ],
            ],
            'is_admin' => true,
        ],
        'expected' => [
            'share' => [
                'prefix' => 'prefix'
            ],
            'subscribers' => [
                $admin_subscriber,
                $init_subscriber,
                $common_subscriber,
            ],
            'providers' => [
                $front_provider,
                $init_provider,
                $common_provider,
                $admin_provider,
            ]
        ]
    ],
];
