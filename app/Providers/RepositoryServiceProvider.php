<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->bind(
            VendorRepositoryInterface::class,
            VendorRepository::class,
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class,
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class,
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
