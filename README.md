# Clone the project  
~~~
$ git clone git@gitlab.com:christianesr/final-project.git  
~~~
### Install dependencies  
~~~
$ composer install
~~~

### Configure env file using your own credentials, you can find an example of the credentials needed on the .env.example file

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

### show relations between models
~~~
$ php artisan model:show Api/Candidate
~~~