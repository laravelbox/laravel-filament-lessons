# About Laravel Filament Lessons

Using Laravel + Filament to create web portal with mySQL database

## Development Resources
- [Install Laravel](#install-laravel).
- [Set up Laravel Database Migration](#set-up-laravel-database-migration).
- [Create Laravel Database Seeding](#create-laravel-database-seeding).
- [Install Laravel Filament](#install-laravel-filament).
- [Create Laravel Filament Resources](#create-laravel-filament-resources).
- [Connect Laravel to Google Cloud SQL MySQL](#connect-laravel-to-google-cloud-sql-mysql).
- [Install Laravel Debugger](#install-laravel-debugger)
- [Laravel Filament Roles and Permissions](#laravel-filament-roles-and-permissions).
- [Create Laravel Filament Relation Manager](#create-laravel-filament-relation-manager).

### Install Laravel

1. Create new laravel application (Install Docker first)
```
curl -s "https://laravel.build/example-app" | bash
```
2. If you do not specify which services you would like configured, a default stack of mysql, redis, meilisearch, mailpit, and selenium will be configured.
```
curl -s "https://laravel.build/example-app?with=mysql,redis" | bash
```

3. Start up laravel application
```
cd example-app && ./vendor/bin/sail up
```
Or:
```
cd example-app && docker compose up -d
```
4. Open docker-compose.yml and add phpmyadmin:
```
phpmyadmin:
        image: phpmyadmin/phpmyadmin
        links:
            - mysql:mysql
        ports:
            - 8080:80
        environment:
            MYSQL_USERNAME: "${DB_USERNAME}"
            MYSQL_ROOT_PASSWORD: "${DB_PASSWORD}"
            PMA_HOST: mysql
        networks:
            - sail
        depends_on:
            - mysql
```
5. Open localhost http://localhost/ or http://localhost:80/
6. Open phpmyadmin http://localhost:8080/

### Set up Laravel Database Migration

1. Create migration table
```
php artisan make:migration create_companies_table
```
2. Open the create migration file and add new columns
3. Run command
```
php artisan migrate
php artisan migrate: status
```

5. If migrate does not work, check DB_HOST in env file to change to mysql or localhost or 127.0.0.1 (check value of DB_HOST in config/database.php)

6. To rollback, 
```
php artisan migrate:rollback
```

#### If there is an error:
 
Edit env file
```
DB_HOST=127.0.0.1 - if running migrate, change to 127.0.0.1
DB_HOST=mysql - if running laravel filament, change to mysql
```

### To clear cache:
```
php artisan config:cache
php artisan config:clear
php artisan cache:clear
```

#### To edit MAC Host:
```
sudo nano /etc/hosts
```

hosts:
```
127.0.0.1       localhost 
255.255.255.255 broadcasthost 
::1             localhost 
127.0.0.1       mysql
```

### Create Laravel Database Seeding

1. Create seeder
```
php artisan make:seeder CompanySeeder
```
2. Run individually
```
php artisan db:seed php artisan db:seed --class=CompanySeeder
```
3. Run whole
```
php artisan migrate:fresh --seedphp artisan migrate:fresh --seed --seeder=CompanySeeder
```
4. Run rollback
```
php artisan migrate:rollback --path=/database/migrations/your-specific-migration.php
php artisan migrate:refresh --step=1
```

### Install Laravel Filament

1. Install Filament
```
composer require filament/filament:"^2.0"
composer require doctrine/dbal --dev
```
2. Create new super user
```
php artisan make:filament-user
php artisan make:filament-resource User --generate
php artisan make:model User
```

#### If "SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, SQL: select count(*) as aggregate from `users` where `email` =" error occurs:

```
#Need to add to 127.0.0.1 mysql to sudo nano /etc/hosts so that the frontend can run
#use mysql so that both migration and db access and frontend can run
#DB_HOST=127.0.0.1 - if running migrate, change to 127.0.0.1
#DB_HOST=mysql - if running laravel filament, change to mysql
```

Or check if the phpmyadmin has started up.

### Create Laravel Filament Resources

1. Create resource
```
php artisan make:filament-resource Driver
php artisan make:filament-resource Driver --generate
```

2. Create model
```
php artisan make:model Driver
```



#### If index router error, clear the route cache:
```
php artisan route:clear
```

### Connect Laravel to Google Cloud SQL MySQL

1. Go to Google Cloud SQL > {Instance} > Overview to get Public IP address
https://console.cloud.google.com/sql/instances/first-project-db/overview

2. Replace env variables for local environment to connect to  Google Cloud SQL's MySQL database
```
DB_CONNECTION=mysql
DB_HOST=xx.xxx.xxx.xx #Connect to Google Cloud SQL - MySQL
DB_PORT=3306
DB_DATABASE=dbnameingooglecloudsql
DB_USERNAME=user
DB_PASSWORD=password
```

### Install Laravel Debugger

1. Install debugbar and set env APP_DEBUG to true
```
composer require barryvdh/laravel-debugbar
```

2. Open config/app.php and add the service provider to providers
```
'providers' => [
    Barryvdh\Debugbar\ServiceProvider::class,
    ...
]
```

3. Examples of debugging
```
Debugbar::info($object);
Debugbar::error('Error!');
Debugbar::warning('Watch out…');
Debugbar::addMessage('Another message', 'mylabel');

try {
    throw new Exception('foobar');
} catch (Exception $e) {
    Debugbar::addThrowable($e);
}

```

### Laravel Filament Roles and Permissions
Reference: https://spatie.be/docs/laravel-permission/v5/installation-laravel

1. Install the package via composer
```
composer require spatie/laravel-permission
```

2. More details at [Laravel Filament Roles and Permissions](docs/PERMISSIONS-ROLES.md)

### Create Laravel Filament Relation Manager

1. Create relation manager
```
php artisan make:filament-relation-manager CategoryResource message id
```

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
