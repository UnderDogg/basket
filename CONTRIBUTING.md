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

## Logging
The logging system uses the Laravel standard:

```php
Log::error('Message', ['context']);
```

These logs will automatically by written out to the underlying system's syslog. To change the log prefix for this,
override the LOG_PREFIX environment variable in the .env file.

Additionally, you can switch whether or not to log to Syslog and/or file, by setting the following variables to True or False.

`LOG_SYSLOG=true`
`LOG_FILE=true`

