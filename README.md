# P6SnowTricks

P6 SnowTricks is the sixth project in my PHP / Symfony developper course with OpenClassrooms.

Codacy dashboard for the project : 

https://app.codacy.com/gh/JasonCooke1988/P6SnowTricks/dashboard?branch=main

## Installation
Clone the repository.

Use the package manager [composer](https://getcomposer.org/download/) to install the projects PHP dependencies.

```bash
composer install
```

Use the package manager [npm](https://www.npmjs.com/) to install the projects JS dependencies.

```bash
npm install
```

Create a .env file and define your `DATABASE_URL` ex:

`DATABASE_URL="mysql://root:@127.0.0.1:3306/p6_snow_tricks?serverVersion=mariadb-10.4.10&charset=utf8"`

also in your .env define `MAILER_DSN` (to be used with symfony/mailer for sending the confirmation email upon user registration.)

Use doctrine to migrate database :

`php bin/console doctrine:migrations:migrate`

and load data fixtures :

`php bin/console doctrine:fixtures:load`

## Start project

From terminal `symfony server:start`

## Structure

1. assets
2. bin   
3. config
4. migrations   
5. public
6. src
7. templates
8. tests
9. translations


#User accounts

Two user accounts have been created with doctrine data fixtures, you can connect with these credentials :

Email : jason.cooke@hotmail.fr
Pass : testing

Email : jasonpcooke88@gmail.com
Pass : testing

Or you can create your own account.

## License
[MIT](https://choosealicense.com/licenses/mit/)

