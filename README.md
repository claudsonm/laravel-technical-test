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

## Running the Project

````shell script
git clone https://github.com/claudsonm/laravel-technical-test.git && cd laravel-technical-test
cp .env.example .env
sudo docker-compose up -d
sudo docker-compose exec app php artisan key:generate
sudo docker-compose exec app php artisan passport:install
sudo docker-compose exec app php artisan migrate --seed
# open http://localhost:8888

# -----

git clone https://github.com/claudsonm/laravel-technical-test.git && cd laravel-technical-test
cp .env.example .env
docker-compose build app
````


## API Documentation

The API Docs can be found here: [Laravel Technical Test Documentation](https://documenter.getpostman.com/view/415095/SWTG5aTY).

In the database there is an credential using the same `client_id` and `client_secret` values 
displayed in the example request body of the "Generate Token" section. 
