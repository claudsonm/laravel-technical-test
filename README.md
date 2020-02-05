# Laravel Technical Test

## Installing the Project

````shell script
git clone https://github.com/claudsonm/laravel-technical-test.git && cd laravel-technical-test
cp .env.example .env
docker-compose build app
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan passport:keys

# Update the DB_HOST variable in your .env file to point to the docker challenge-db container.
# You have to do that before execute the commands below.
# For more informatin take a look at the Troubleshooting section.

docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=PassportTableSeeder

# open http://127.0.0.1:8000
````

## API Documentation

The API Docs can be found here: [Laravel Technical Test Documentation](https://documenter.getpostman.com/view/415095/SWTG5aTY).

### Credentials
If you performed the database seeding using the class `PassportTableSeeder`, an credential 
was created using the same `client_id` and `client_secret` values displayed
in the example request body of the "Generate Token" section. But in any case,
here you have it:

```
client_id: 1
client_secret: 44mOB9p7eCHggYNlvPxuF7RnLV1TnNx6ee2Qn1IH
```

### Creating New Credentials
If by any chance you wish to create new credentials for the API, you can generate
those by running the command:

```shell script
docker-compose exec app php artisan passport:client --client -n

# Outputs:
# New client created successfully.
# Client ID: 2
# Client secret: NHBv0CedSiLqW6Rc1tjiSqtEXZY0lR9zaGo3jX8z
```

## Testing

````shell script
composer test
````

Although completely unnecessary for the testing scripts, it's possible to 
populate the database with a bunch of dummy data. This is useful if you
just want to play around with the API. You can achieve this by running: 

```shell script
docker-compose exec app php artisan migrate:fresh --seed
```

## Troubleshooting

### Unable to connect to the database

In order to connect to the database you have to update the `DB_HOST` value in your 
`.env` file so that it points to the database service we just created using Docker.

Execute the following command to get a list of all your docker containers IP addresses.

````shell script
sudo docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(sudo docker ps -aq)

# Outputs:
# ...
# /challenge-db - 172.18.0.2 // <------ Point to that IP
# /challenge-nginx - 172.18.0.3
# /challenge-app - 172.18.0.4
# ...
````
