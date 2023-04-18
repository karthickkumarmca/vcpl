<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use App\Helpers\Helper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') !== "local") {
            $url->forceScheme('https');
        }
        //Case-insensitive unique validator
        app('validator')->extend('iunique', function ($attribute, $value, $parameters, $validator) {
            //echo "<pre>";print_r($parameters);exit;
            $query  = \DB::table($parameters[0]);
            $column = $query->getGrammar()->wrap($parameters[1]);
            $value = Helper::seoUrl($value);

            if (isset($parameters[2])) {
                if (isset($parameters[3]))
                    $idCol = $parameters[3];
                else
                    $idCol = 'id';

                $query->where($idCol, '!=', $parameters[2]);
            }
            return !$query->whereRaw("lower({$column}) = lower(?)", [$value])
            ->count();
        });
    }
}
