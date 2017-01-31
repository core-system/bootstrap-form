<?php

namespace Core\BootstrapForm;

use Collective\Html\HtmlServiceProvider;

class BootstrapFormServiceProvider extends HtmlServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing package configuration file
        $this->publishes([
            realpath(__DIR__ . '/../config') => config_path()
        ], 'config');
    }
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        // Load cms configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/form-builder.php', 'form-builder'
        );
        // Register FormBuilder as singleton instance
        $this->app->singleton('form', function($app) {
            $form = new BootstrapFormBuilder(
                $app['html'],
                $app['url'],
                $app['view'],
                $app['session.store']->token()
            );
            // Return session store
            return $form->setSessionStore($app['session.store']);
        });
    }
}