# Laravel Technical Test

## Commands

```shell script
cp .env.example .env
yarn install
yarn run dev
php artisan key:generate
php artisan migrate --seed
php artisan passport:install
php artisan passport:client --client --name="Laravel Client Credentials Grant Client"
```

## API Documentation

The API Docs can be found here: [Laravel Technical Test Documentation](https://documenter.getpostman.com/view/415095/SWTG5aTY).

In the database there is an credential using the same `client_id` and `client_secret` values 
displayed in the example request body of the "Generate Token" section. 
