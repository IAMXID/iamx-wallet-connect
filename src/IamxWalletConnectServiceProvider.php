<?php

namespace IAMXID\IamxWalletConnect;

use IAMXID\IamxWalletConnect\View\Components\IdentityConnector;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class IamxWalletConnectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if($this->app->runningInConsole()) {

            if(!class_exists('AddDidToUsersTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/add_vuid_to_users_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_add_vuid_to_users_table.php'),

                ], 'migrations');
            }

            if(!class_exists('CreateIamxIdentityAttributesTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_iamx_identity_attributes_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_iamx_identity_attributes_table.php'),

                ], 'migrations');
            }
        }


        // Load package views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'iamxwalletconnect');

        // Load package routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Register blade components
        Blade::component('iamxwalletconnect-identity-connector', IdentityConnector::class);
    }
}
