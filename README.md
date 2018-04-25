#STEPON-API
====


Installation
---

1. Clone project from repository
2. Go to project dir `cd /var/www/stepon`
3. Install  Composer dependencies `composer install`.
4. Copy example config file `cp config/autoload/local.php.dist config/autoload/local.php` 
5. Complete project variables in `config/autoload/local.php`:
6. Execute `./vendor/bin/doctrine-module migrations:migrate`
7. Encode string 'new_client_id:new_client_password' with Base64
8. Generate password for client `php vendor/zfcampus/zf-oauth2/bin/bcrypt.php new_client_password`
9. Execute INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`) VALUES ('new_client_id', 'new_generated_client_password', '/oauth/receivecode');

Login: /api/user/login
---
    headers:
        Authorization: Basic base_64_string
        Content-Type: application/json
    body:
    {
        "username": "",
        "password": "",
        "grant_type": "password"
    }

Generate proxies:
`/var/www/stepon/vendor/bin/doctrine-module orm:generate:proxies`

Generate migration file:
`/var/www/stepon/vendor/bin/doctrine-module migrations:generate`