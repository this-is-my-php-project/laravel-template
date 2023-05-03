<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About

This Laravel code template is designed to help you get started quickly with a solid foundation of object-oriented PHP principles. It provides a structure for your Laravel projects that is both scalable and easy to maintain.

With this template, you can create new modules with a single command using Laravel's built-in command line interface. Each module comes with its own set of controllers, service, repo, model, and more, all organized in a logical file structure.

## Installation

Install dependencies:

    composer install

## Commands

Generate a new module:

    --controller option is define user-end module. Default is admin-end module.
    ```
    php artisan make:module Post
    ```
    ```
    php artisan make:module Post --controller=User
    ```
