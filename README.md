## Getting Started

It's super easy to get the application up and running.

1. Clone the project

```shell
git clone https://github.com/zubayer-hossain/contact-app-vue3-lv
```

2. Install the dependencies

```shell
composer install
npm install
```

3. Copy `.env.example` to `.env`

```shell
cp .env.example .env
```

4. Generate application key

```shell
php artisan key:generate
```

5. Start the webserver

```shell
php artisan serve --port=8088
npm run dev
```

5. Database Migration and Seeding

```shell
php artisan migrate:fresh --seed
```

Here you go! Setup is done!
Now register as a new user and login!
