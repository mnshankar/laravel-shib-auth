# Shibboleth-Laravel authentication bridge
Shibboleth authentication typically results in the identity provider setting a bunch of server variables after successful authentication. This is a Laravel 5.0+/PHP 5.4+ package helps with converting those server tokens into Laravel User Table fields so that regular Laravel semantics of authentication can be followed.

__Note that shib auth must already have occured - usually using directives in the .htaccess file.__

The included middleware checks the user table and 
1. If a matching user is NOT found, it creates a new user row
2. If a user is found in the users table, the corresponding user record is retrieved
3. The user is then logged in to the application using Auth::login($user)

## Installation
require the package via composer
``` bash
$ composer require mnshankar/laravel-shib-auth
```

Next, setup the service provider. This allows you to modify the config file (the default tokens are specific to UF implementation of Shibboleth)

In your config/app providers array, add:

```php
'mnshankar\Shib\ShibAuthServiceProvider',
```

You must insure that 
1. The mapped fields exist in your users table. 
2. Shibboleth sets valid values for all tokens specified (else exception is thrown)
3. "password" field in the users table must be nullable as we will not be using it.

## Usage
Edit your http kernel.php file to include the shib middleware from the package like so:

```bash
'shib'=>'mnshankar\Shib\Middleware\ShibAuth',
```

Now, you can use the middleware either from the controller or from your route. 

1. In your controller:
    ```php
    function __construct()
    {
        $this->middleware('shib');
    }
    ```
2. In your route:

   Using Laravel 5.0
   ```php
   Route::get('my/page', ['middleware' => 'shib', function()
   {
       //
   }]);
   ```
   Using Laravel 5.1+
   
   You can continue using Laravel 5.0 style.. or use chaining:
   ```php
   Route::get('/', function () {
       //
   })->middleware(['shib']);
   ```
   
   You may also use route groups. Please look up Laravel documentation on Middleware to learn more https://laravel.com/docs/5.2/middleware

## Configuration Options
You can customize the configuration options by publishing them

```bash
php artisan vendor:publish --provider="mnshankar\Shib\ShibAuthServiceProvider"
```