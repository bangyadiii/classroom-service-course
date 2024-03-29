# Classroom

## About Classroom

Classroom is a education platform that provide courses based on videos. This is the course services that used to handle Course domain.

## Framework & Technologies

-   Laravel
-   HTTP communication based

## How to use this repository

-   Raname the `.env.example` to `.env` file at project folder.
-   Fill the value in `.env` file based on your case.
-   Install dependencies
    ```bash
    $ composer install
    ```
-   Create Laravel app key
    ```bash
    $ php artisan key:generate
    ```
-   Run migration with :
    ```bash
    $ php artisan migrate
    ```
-   Run the laravel's server
    ```bash
    $ php artisan serve
    ```

## Documentation

Here is the postman documentation for api gateway. [Documentation](https://documenter.getpostman.com/view/16615700/2s93CKQubR)

## Service API

In other side, the API service for this platform built with microservice architecture.
Here is the list :

-   [Landing Page](https://github.com/bangyadiii/classroom-frontpage-FE)
-   [Member Page](https://github.com/bangyadiii/memberpage-micro)
-   [API Gateway](https://github.com/bangyadiii/classroom-api-gateway)
-   [User Service](https://github.com/bangyadiii/classroom-service-user)
-   [Media Service](https://github.com/bangyadiii/classroom-service-media)
-   [Course Service](https://github.com/bangyadiii/classroom-service-course)
-   [Order & Payment Service](https://github.com/bangyadiii/classroom-service-order-payment)
