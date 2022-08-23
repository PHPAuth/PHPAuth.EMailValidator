# PHPAuth.EmailValidator

Custom e-mail validator (for PHPAuth)

## Use with PHPAuth

```php
require_once __DIR__ . '/path/to/vendor/autoload.php';

// ...

$config = new \PHPAuth\Config($pdo);

$config = $config->setEMailValidator(static function ($email) {
    return \PHPAuth\EmailValidator::isValid($email);
});
```

## Standalone usage:

```php

require_once __DIR__ . '/path/to/vendor/autoload.php';

$l = [
    'karel.wintersky@gmail.com',
    'foo@0d00.com',
    'xxxx'
];

foreach ($l as $e) {
    var_dump(\PHPAuth\EmailValidator::check($e)->state);
    echo "{$e} ==> " . ( \PHPAuth\EmailValidator::isValid($e) ? 'VALID' : 'INVALID' ) . PHP_EOL;
}

```

# FAQ

Q: Why static class? 
A: Email validator uses internal cache for storing loaded domains. It can be useful for sequential checks.

# Thanks to

- https://github.com/MattKetmo/EmailChecker 
- https://github.com/FGRibreau/mailchecker
- legacy PHPAuth `domains.json` and legacy `database_emails_banned.sql`
- 

