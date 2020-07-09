# Dishtansya

Dishtansya is a food delivery app that provides delivery service from food chains and
restaurants around the globe.


## Installation

Clone the repository

```bash
git clone https://github.com/arkiv2/dishtansya
```

Install the required packages using composer

```bash
composer install
```

Setup the application

```bash
cp .env.example .env

php artisan key:generate
```

Edit the .env file to match DB, Mailer, and Queue settings. Then migrate and install passport

```bash
php artisan migrate --seed

php artisan passport:install
```

## License
[MIT](https://choosealicense.com/licenses/mit/)
