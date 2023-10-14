# Clone the project  
~~~
$ git clone git@github.com:csagastegui59/laravelAPI.git
~~~
### Install dependencies  
~~~
$ composer install
~~~

### Configure env and env.testing files using your own credentials, also don't forget to create your databases for local environment. You can find an example of the credentials needed on the .env.example file

### Run migrations
~~~
$ php artisan migrate
~~~
### Run seeders
~~~
$ php artisan db:seed
~~~
### Start the server  
~~~
$ php artisan serve
~~~
### Run tests
~~~
php artisan test --env=testing
~~~
### show relations between models
~~~
$ php artisan model:show Api/Candidate
~~~
