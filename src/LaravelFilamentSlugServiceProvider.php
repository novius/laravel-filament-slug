<?php

namespace Novius\LaravelFilamentSlug;

use Illuminate\Support\ServiceProvider;

class LaravelFilamentSlugServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $packageDir = dirname(__DIR__);

        $this->loadTranslationsFrom($packageDir.'/lang', 'laravel-filament-slug');
        $this->publishes([__DIR__.'/../lang' => lang_path('vendor/laravel-filament-slug')], 'lang');
    }

    /**
     * Register any application services.
     */
    public function register(): void {}
}
