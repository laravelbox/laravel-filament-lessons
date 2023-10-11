# Laravel Filament Roles and Permissions

Reference: https://spatie.be/docs/laravel-permission/v5/installation-laravel

[Back to Main](../README.md)

## Roles and Permissions Modules

- [Create Laravel Project and Install Filament](#create-laravel-project-and-install-filament)
- [Install Permissions Package and Create Admin User](#install-perimssions-package-and-create-admin-user)
- [Create Role Resource for Laravel Filament Permissions](#create-role-resource-for-laravel-filament-permissions)
### Create Laravel Project and Install Filament
- [Create Permission and User Resources for Laravel Filament Permissions](#create-permission-and-user-resources-for-laravel-filament-permissions)

1. Create Laravel Project (Install Docker and open up the Docker first)
```
curl -s "https://laravel.build/example-app?with=mysql" | bash
```

2. Start up laravel application
```
cd example-app && ./vendor/bin/sail up
```
Or:
```
cd example-app && docker compose up -d
```

3. Open docker-compose.yml and add phpmyadmin:
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
4. Open localhost http://localhost/ or http://localhost:80/
5. Open phpmyadmin http://localhost:8080/

6. Set up database for laravel
```
php artisan migrate
```

7. Install Filament 2
```
composer require filament/filament:"^2.0"
```

8. Create Filament Admin User
```
php artisan make:filament-user
```

6. Open admin panel at http://localhost/admin and login using the newly created admin user account

### Install Permissions Package and Create Admin User


1. Install the package via composer
```
composer require spatie/laravel-permission
```

2. Publish migration and permission config file
```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

3. To use the TEAMS feature, update config/permission.php config file:
```
'teams' => true,
```

4. Clear your config cache
```
php artisan optimize:clear
# or
php artisan config:clear
```

5. Run the migrations
```
php artisan migrate
```

6. Edit app/Models/User.php to include import class for HasRoles
```
use Spatie\Permission\Traits\HasRoles;
...
// The User model requires this trait 
use HasRoles;
```

7. Test functionalities 
Edit run function in database/seeders/DatabaseSeeder.php
```
//EA 10 Oct 2023 - Added permission functionalities
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
...
//Test permission functionalities
$user = User::factory()->create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
]);
//password is password by default
$role = Role::create(['name' => 'admin']);
$user->assignRole($role);
```
Run migrate seeder:
```
php artisan migrate:fresh --seed
```

8. Implement Laravel Filament Auth - Authorizing access to the admin panel
Edit app/Models/User.php
```
class User extends Authenticatable implements FilamentUser
...
public function canAccessFilament(): bool { 
    return $this->hasRole('Admin');
    //return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail(); 
}

```

### Create Role Resource for Laravel Filament Permissions

1. Create Filament Resource for Role
```
php artisan make:filament-resource Role
```

2. Edit app/Filament/Resources/RoleResource.php to use permission role model
```
//EA 10 Oct 2023 - Use permission role model instead of default role
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
...

public static function form(Form $form): Form
{
    return $form
        ->schema([
            //EA 11 Oct 2023 - Added card for permission
            Card::make()->schema([
                //EA 11 Oct 2023 - Added field for permission
                TextInput::make('name')
                        ->minLength(2)
                        ->maxLength(255)
                        ->required()
                        ->unique()
            ])
        ]);
}

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //EA 10 Oct 2023 - Added column for permission role
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('updated_at'),
            ])

...

```

3. Edit create and edit to change redirection of submission
- app/Filament/Resources/RoleResource/Pages/CreateRole.php
- app/Filament/Resources/RoleResource/Pages/EditRole.php
```
...
//EA 11 Oct 2023 - Redirect to list page after submission
protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
```

### Create Permission and User Resources for Laravel Filament Permissions

1. Run this command so that Filament can automatically generate the form and table for you, based on your model's database columns.
```
composer require doctrine/dbal --dev
```

2. Create permission resource with generate
```
php artisan make:filament-resource Permission --generate
```

3. Edit app/Filament/Resources/PermissionResource.php
```
//EA 11 Oct 2023 - Use permission model instead of default permission
use Spatie\Permission\Models\Permission;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
...

public static function form(Form $form): Form
{
    return $form
        ->schema([
                //EA 11 Oct 2023 - Added card for permission role
            Card::make()->schema([
                //EA 10 Oct 2023 - Added field for permission role
                        TextInput::make('name')
                        ->minLength(2)
                        ->maxLength(255)
                        ->required()
                        ->unique()
            ])
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            //EA 11 Oct 2023 - Added column for permission
            TextColumn::make('id'),
            TextColumn::make('name'),
            TextColumn::make('updated_at'),
        ])
...

```

4. Edit create and edit to change redirection of submission
- app/Filament/Resources/PermissionResource/Pages/CreatePermission.php
- app/Filament/Resources/PermissionResource/Pages/EditPermission.php
```
...
//EA 11 Oct 2023 - Redirect to list page after submission
protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
```

5. Create user resource with generate
```
php artisan make:filament-resource User --generate
```

6. Edit app/Filament/Resources/UserResource.php
```
//EA 11 Oct 2023 - Added card for permission user
use Filament\Forms\Components\Card;
...
public static function form(Form $form): Form
{
    return $form
        ->schema([
            //EA 11 Oct 2023 - Added card for permission
            Card::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ])
        ]);
}


```