<?php

namespace App\Providers;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\TicketRepositoryInterface;

use App\Repositories\UserRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
    }
}
