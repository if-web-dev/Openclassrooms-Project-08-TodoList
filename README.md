# Openclassrooms-Project-08-TodoList


[![Codacy Badge](https://app.codacy.com/project/badge/Grade/44e220000b174e3c8df750ccdaaf8203)](https://app.codacy.com/gh/if-web-dev/Openclassrooms-Project-08-ToDo-List/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/44e220000b174e3c8df750ccdaaf8203)](https://app.codacy.com/gh/if-web-dev/Openclassrooms-Project-08-TodoList/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)

We present project 8 of the PHP/Symfony application developer course. Enhance an existing ToDo & Co app.

Project basis: [https://openclassrooms.com/projects/ameliorer-un-projet-existant-1](https://openclassrooms.com/projects/ameliorer-un-projet-existant-1)


## To start

This project was developed with PHP 8.1, it integrates the bootstrap, datatables and jquery libraries.

### Prerequisites

- A machine with at least PHP 8.1.
- Composer
- Symfony CLI
> **NOTE** : I used WAMP Server on local.

### Installation

- Clone or download the repository
- Duplicate and rename the `.env` file to `.env.local` and modify the necessary information and choose your database (`APP_ENV`, `APP_SECRET`, ...)
- Install the dependencies with `symfony composer install --optimize-autoloader`
- Run migrations with `symfony console doctrine:migrations:migrate --no-interaction`
- Add default datasets with `symfony console doctrine:fixtures:load --no-interaction`

## Startup

- Locally run your server like WAMP SERVER
- Run the app with `symfony serve -d`
- Admin Credentials : `Admin` and `password`
- User Credentials : `User` and `password`

## Run the tests

- First create a  `.env.test.local.`  file for your test environment datas and create your test database:

`php bin/console --env=test doctrine:database:create`

`php bin/console --env=test doctrine:fixtures:load --append`

- Then run the tests:

 `./vendor/bin/phpunit tests`

You can add this flag to generate a HTML render of your tests.

`--coverage-html public/test-coverage`

## Contributing

- If you would like to contribute to this project, please read  `CONTRIBUTING.md`

## Made with

* [Bootstrap](https://getbootstrap.com/) - Framework CSS (front-end)
* [DataTables](https://datatables.net/) - Javascript library (front-end)
* [Composer](https://getcomposer.org/) - Dependancy manager
* [Visual Studio code](https://code.visualstudio.com/) - Code editor

## Author

* **Ishake FOUHAL** _alias_ [@IF-WEB-DEV](https://github.com/if-web-dev)
