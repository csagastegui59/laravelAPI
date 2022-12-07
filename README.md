# Clone the project  
~~~
- $ git clone git@gitlab.com:christianesr/final-project.git  
~~~

## Start the server  
~~~
- $ php artisan serve
~~~
# You may install Laravel Sanctum via the Composer package manager:
composer require laravel/sanctum

# Next, you should publish the Sanctum configuration and migration files using the vendor:publish Artisan command. The sanctum configuration file will be placed in your application's config directory:
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Run migrations
php artisan migrate

# show relations between models
php artisan model:show Api/Candidate