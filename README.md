# online-support-system

1. Clone repository
```
$ git clone https://github.com/shahinaca/online-support-system.git
 
 $ cd online-support-system
```
## Install with Composer

```
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install or composer install
    
    
    if you have already installed composer just do the composer update 
    $ composer update
```

## Set Environment

```
    $ cp .env.example .env
```

## Set the application key

```
   $ php artisan key:generate
```

## Run migrations and seeds

```
   $ php artisan migrate
   $ php artisan db:seed
   
```
## Run Server

````
    $ php artisan serve
````
## Run Schedule for Cronjob
 For **Email Notification** and after **24 hours** automatically **status change to Answered** need to run this comand in Local.
````
    $ php artisan schedule:work
````
## API Documentation 
see below link for complete api documentation

**127.0.0.1:8000/api/documentation**

Note: If you have changed base url (especially **port**) , then change in **online-support-system/public/docs/api-docs.json** 
