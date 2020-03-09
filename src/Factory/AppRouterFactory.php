<?php

declare(strict_types=1);

namespace App\Factory;

use App\Controller\AuthController;
use App\Controller\SiteController;
use App\Controller\TaskController;
use Psr\Container\ContainerInterface;
use Yiisoft\Auth\Middleware\Auth;
use Yiisoft\Router\FastRoute\UrlMatcher;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectorInterface;

class AppRouterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $auth = $container->get(Auth::class);

        $routes = [
            Route::get('/', [SiteController::class, 'index'])
                ->name('site/index'),

            // API
            Group::create('/api', [
                Group::create('/auth', [
                    Route::post('/login', [AuthController::class, 'login'])
                        ->name('auth/login'),

                    Route::post('/register', [AuthController::class, 'register'])
                        ->name('auth/register'),

                    Route::get('/verify', [AuthController::class, 'verify'])
                        ->name('auth/verify')
                        ->addMiddleware($auth)
                ]),
                Group::create('/task', [
                    Route::get('', [TaskController::class, 'index'])
                        ->name('task/index'),

                    Route::get('/{id:\w+}', [TaskController::class, 'view'])
                        ->name('task/view'),

                    Route::post('', [TaskController::class, 'create'])
                        ->name('task/create'),

                    Route::put('/{id:\w+}', [TaskController::class, 'update'])
                        ->name('task/update'),

                    Route::delete('/{id:\w+}', [TaskController::class, 'delete'])
                        ->name('task/delete'),
                ])->addMiddleware($auth),
            ]),

            // redirect all non-existing routes (Vue.js router will handle the rest)
            Route::get('/{path:.+}', [SiteController::class, 'index'])
                ->name('catch-all'),
        ];

        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create(null, $routes)
        );

        return new UrlMatcher(
            new RouteCollection($collector)
        );
    }
}
