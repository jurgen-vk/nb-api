<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // normally I would extract this in a separate service provider, but this is a really simple app so this is a bit unnecessary at this point.
        /**
         * Grabs a value from a single or multidimensional associative collection, by a given key.
         * Be careful, this function uses eval. Use only when necessary, and don't use with dirty input directly from users.
         *
         * @param string $search_key The key of the value you are looking for, separated by a ".", for example: "nested.item.key".
         *
         * @return mixed The value of the corresponding key.
        */
        Collection::macro('grab', function($search_key) {
            $array = $this->toArray();
            $keys = explode('.', $search_key);
            $array_keys = "";

            foreach($keys as $key) {
                $array_keys .= "['$key']"; // Remove single quotes around $key
            }

            $stmt = "\$array$array_keys";

            return eval("return $stmt;"); // Add 'return' statement and wrap the entire eval in double quotes
        });
    }
}
