<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Policies\V1\ProductPolicy;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Product models are protected by ProductPolicy
        Gate::policy(Product::class, ProductPolicy::class);
        // User models are protected by UserPolicy
        Gate::policy(User::class, UserPolicy::class);
    }
}
