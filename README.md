# iamx-wallet-connect
IAMX wallet connect is a Laravel package to login to a laravel application using the IAMX identity wallet.

- [IAMX-wallet-connect](#iamx-wallet-connect)
    - [Installation](#Installation)
    - [Configuration](#Configuration)
    - [Usage](#Usage)
    - [Bugs, Suggestions, Contributions and Support](#bugs-and-suggestions)
    - [Copyright and License](#copyright-and-license)

## Installation


Install the current version of the `iamxid/iamx-wallet-connect` package via composer:
```sh
    composer require iamxid/iamx-wallet-connect:dev-main
```

## Configuration

Publish the migration file:
```sh
    php artisan vendor:publish --provider="IAMXID\IamxWalletConnect\IamxWalletConnectServiceProvider" --tag="migrations"
```

Run the migration:
```sh
    php artisan migrate
```

## Usage
Add the attribute "iamx_vuid" to the $fillable array in /app/Models/User.php
```php
    protected $fillable = [
        'name',
        'email',
        'password',
        'iamx_vuid'
    ];
```
Add the HasDID trait to the user model in /app/Models/User.php
```php
use IAMXID\IamxWalletConnect\Traits\HasDID;

class User extends Model
{
    use HasDID;
    ...
}
```
Place the component ```<x-iamxwalletconnect-identity-connector />``` in your blade template to insert the wallet connect button.

## Examples
Use the functions in the HasDID trait in your application to access the IAMX wallet attributes:

Fetch single attributes:
```php
$user = User::find(1);
$street = $user->getDIDAttribute('address', 'street', $user->id);
$housenr = $user->getDIDAttribute('address', 'housenr', $user->id);
$zip = $user->getDIDAttribute('address', 'zip', $user->id);
```
Fetch all attributes of a category:
```php
$user = User::find(1);
$allCategoryValues = $user->getDIDCategoryValues('address', $user->id);
```

Fetch all available attributes:
```php
$user = User::find(1);
$allValues = $user->getAllDIDValues($user->id);
```

## Bugs and Suggestions

## Copyright and License

[MIT](https://choosealicense.com/licenses/mit/)
