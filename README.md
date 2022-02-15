# PHP Wallet API
## _API for transfers between users_

This api allows send money trhougth transactions
- make with 💚 

## Features
- User Registration
- Fraud Transaction System
- Success Transaction Notifications
- User Validation
- Docker Available

## Tech

- [Laravel] - The PHP Framework for Web Artisans
- [Laravel Sail] - Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment
- [PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
- [MySQL] - MySQL Database Service is a fully managed database service to deploy cloud-native applications.
- 
## Installation

#### 1. With Docker
Wallet Requires [Docker](https://www.docker.com/) e [Docker-compose](https://docs.docker.com/compose/install/) to run.

clone the repository

```git clone https://github.com/MatthausNawan/wallet.git```

inside the project folder

```cd wallet```

run composer

```compose install```

create the .env file

``` cp .env.example .env```

configure the database connection on the .env file 

```sh
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=wallet
DB_USERNAME=root
DB_PASSWORD=
```
configure the external URL variables in the .env file

```sh
AUTHORIZATION_URL=http://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6
NOTIFICATION_URL=http://o4d9z.mocklab.io/notify
FORCE_FAILS=false
```

#### 2. Laravel Server
> Make sure if you enviroment has PHP 7.4 or 8.0 version and mysql server available
> Composer also is necessary.

inside of root project folder install dependencies:

```compose install```

copy the .env.example and create .env file

```cp .env.example .env```

configure the database connection on the .env file 

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wallet
DB_USERNAME=root
DB_PASSWORD=
```
configure the external URL variables in the .env file

```sh
AUTHORIZATION_URL=http://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6
NOTIFICATION_URL=http://o4d9z.mocklab.io/notify
FORCE_FAILS=false
```
inside of root project folder prepate database:

```php artisan migrate```

serve the aplication with

```php artisan serve```

the project will be available in ```localhost:8000/api``` you will see:

```sh
{
  "success": true,
  "message": "laravel framework: 8.83.0"
}
```

## Api Methods
#### User Registration:

```sh
POST - localhost:8000/api/v1/users
```
###### Payload:
#
```sh
{
    "name": "Taylor",
    "email": "taylor@email.com",
    "cpf_cnpj": "03.730.973/00101-71",
    "phone": "(83)9999-9999",
    "user_type": "USER" //
}
```
** All fields are required

```user_type``` param must be ```"USER"``` or ```"STORE"```

> A wallet automatically created after user created with [```Laravel Observer```](https://laravel.com/docs/8.x/eloquent#observers)

#### Transactions:
```sh
POST - localhost:8000/api/v1/transactions
```
###### Payload:
#
```sh
{
	"amount": 10.00,
	"payer": 1,
	"payee": 2
}
```

** All Fields are required

```payer``` is the ```ID``` of user will be make the transfer.

```payee``` is the ```ID``` of user will receive the money.

```amount``` is the transaction amount.

## Test
run tests with

```php artisan test```

this test are powered by [PHP UNIT](https://phpunit.readthedocs.io/en/9.5/)

## License

MIT
**Free Software!**`