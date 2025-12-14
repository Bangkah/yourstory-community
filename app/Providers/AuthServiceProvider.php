<?php

namespace App\Providers;

use App\Models\Story;
use App\Policies\StoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Story::class => StoryPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
