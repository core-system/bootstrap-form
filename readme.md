# CORE-SYSTEM BOOTSTRAP FORM

[![Packagist](https://img.shields.io/packagist/l/core-system/bootstrap-form.svg?maxAge=2592000)](https://packagist.org/packages/core-system/bootstrap-form)
[![GitHub release](https://img.shields.io/github/release/core-system/bootstrap-form.svg?maxAge=2592000)](https://packagist.org/packages/core-system/bootstrap-form)

This is a standalone part of `core-system` application for Laravel 5 Framework. 

> [CORE-SYSTEM](http://www.core-system.cz) is Laravel 5 based application 

CORE-SYSTEM Bootstrap Form contains `laravel-collective/html` extension for simple Twitter Bootstrap 3 form generation and and request validation error handling.
 
## Summary

- [Requirements and dependencies](#requirements-and-dependencies)
- [Installation](#installation)
- [Usage](#usage)
 
## Licence

GPL-3.0+

## Requirements and dependencies

This package uses `composer` to installing dependencies

### Composer

- "php": ">=5.5.9"
- "laravel/laravel": "^5.2"
- "laravelcollective/html": "^5.0"

## Installation

Run terminal. Go to your web projects root directory and type following `composer create-project` command and install new installation of Laravel Framework 

> __Skip this command if you have Laravel framework already installed__
    
    $ composer create-project laravel/laravel your-project-name --prefer-dist
    
Open `composer.json` file located in your project folder and add following lines to `require` key

> __For `laravelcollective/html - 5.3` and bellow versions please use `"core-system/bootstrap-form": "1.0.*"` because there is some changes in `laravelcollective/html` API between `5.3` and `5.4` release__ 

```json
{
    "require": {
        "core-system/bootstrap-form": "1.1.*"
    }
}
```

Run `composer update` command

```
$ composer update
```
    
Go to your Laravel `config/app.php` and add this line to your `providers` key

```php 
Core\BootstrapForm\BootstrapFormServiceProvider::class 
```

and following line to `aliases` key to register Laravel Collective form facade 

```php
'Form' => Collective\Html\FormFacade::class
```

> If you need you can config control and error class publish vendor config file

```
$ php artisan vendor:publish --provider="Core\BootstrapForm\BootstrapFormServiceProvider" --tag="config"
```

## Usage

In Blade templates you can use this extensions as standard Laravel Collective Form.

### Form methods

```php
Form::open(array $options = [])
```
> create `<form>` tag with given attributes and `<input type="hidden">` for Laravel cross site request forgery protection

```php
Form::close()
```
> create `</form>` closing tag

```php
Form::openGroup(string $name, mixed $label = null, array $options = [])
```
> create opening `.form-group` element with given attributes

```php
Form::closeGroup()
```
> close cloning tag for actually opened `.form-group` element

```php
Form::input(string $type, string $name, mixed $value = null, array $options = [])
```
> create `<input>` field

```php
Form::select(string $name, array $list = [], mixed $selected, array $selectAttributes = [], array $optionsAttributes = [])
```
> create `<select>` field

```php
Form::plainInput(string $type, string $name, mixed $value = null, array $options = [])
```
> create plain `<input>` field

```php
Form::plainSelect(string $name, array $list = [], mixed $selected, array $selectAttributes = [], array $optionsAttributes = [])
```
> create plain `<select>` field

```php
Form::checkbox(string $name, mixed $value = 1, mixed $label = null, bool $checked = null, array $options = [])
```
> create `<input type="checkbox">` field

```php
Form::radio(string $name, mixed $value = null, mixed $label = null, bool $checked = null, array $options = [])
```
> create `<input type="radio">` field

```php
Form::inlineCheckbox(string $name, mixed $value = 1, mixed $label = null, bool $checked = null, array $options = [])
```
> create in-line `<input type="checkbox">` field

```php
Form::inlineRadio(string $name, mixed $value = null, mixed $label = null, bool $checked = null, array $options = [])
```
> create in-line `<input type="radio">` field

```php
Form::textarea(string $name, mixed $value = null, array $options = [])
```
> create `<textarea>` field

```php
Form::plainTextarea(string $name, mixed $value = null, array $options = [])
```
> create plain `<textarea>` field

### Basic Blade template usage example

```php
{!! Form::open(['class' => 'form', 'id' => 'loginForm', 'url' => route('backend.auth.login')]) !!}
    {!! Form::openGroup('email', null, ['class' => 'has-feedback']) !!}
        {!! Form::text('email', null, ['placeholder' => trans('auth.email-placeholder') ]) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('password', null, ['class' => 'has-feedback']) !!}
        {!! Form::password('password', ['placeholder' => trans('auth.password-placeholder') ]) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('submit', null) !!}
        {!! Form::input('submit', 'submit', trans('auth.login'), ['class' => 'btn btn-primary btn-lg']) !!}
    {!! Form::closeGroup() !!}
{!! Form::close() !!}
```

### Validation

> U can use standard Laravel request validation methods. Package render error classes for Twitter Bootstrap 3 automatically.

#### Laravel 5 request validation example

Create new request class file via `php artisan make:request <className>` command

    $ php artisan make:request LoginFormRequest
    
In created `LoginFormRequest.php` file which is located in your `app/Http/Requests` folder change content to following lines
 
```php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoginFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }
}

```

And in you controller add request to post action parameters via Laravel 5 dependency injection

```php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;

class FormsController extends Controller
{
    /**
     * Example action to handle login form POST action
     *
     * @return void
     */     
    public function login(LoginFormRequest $request)
    {
        // your next logical code
    }
}

```
