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
### You can find json file postman collection on the source app folder
![image](https://github.com/csagastegui59/laravelAPI/assets/45051315/041ea7f7-346a-4455-8e35-0f9d6c24538f)

### Register admin user using secret_key: "123456"
![image](https://github.com/csagastegui59/laravelAPI/assets/45051315/fa0ec1d1-8697-457b-9e11-68ad0533eee5)
### Login
![image](https://github.com/csagastegui59/laravelAPI/assets/45051315/eaa831f6-a234-4cdb-ba49-9cd6210a77e5)
### Companies Generated with seeders
![image](https://github.com/csagastegui59/laravelAPI/assets/45051315/969515d7-4a7c-42e1-bb9b-475fe6164802)
### Job openings from company id given from the url
![image](https://github.com/csagastegui59/laravelAPI/assets/45051315/a11fdf06-70de-4415-82d4-606ff6e7ed25)
### Candidates from a job opening id given from the url
![image](https://github.com/csagastegui59/laravelAPI/assets/45051315/e6ef5566-8b5a-4628-9f34-88ad3f08e7ea)


