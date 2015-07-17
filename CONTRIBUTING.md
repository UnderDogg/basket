# Contributing

## Setup Dev Environment
Based on http://laravel.com/docs/5.1/homestead

### First Time
1. `ssh-keygen -t rsa -C "you@homestead"` use default file name

### Every Time
1. Clone repository
2. `cd` repository folder in terminal
3. `composer install`
4. `cp .env.example .env`
5. `vagrant up`

## Constraints

#### Timestamps
When creating new migrations, instead of the usual:
```php
$table->timestamps()
```
Please use the following to help with DB compatibility:
 ```php
 $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 ```
 
