Have It Done Task Tracker
====

Installation
---

1. After project cloning apply `vagrant up` command to install local environment
2. Inside the VM (`vagrant ssh`) go to project dir `cd /var/www/stepon`
3. Install  Composer dependencies `composer install`.
4. Execute export APPLICATION_ENV=local and ./vendor/bin/doctrine-module migrations:migrate
5. Execute INSERT INTO `users` (`username`, `password`,`createdAt`) VALUES ('johndoe', '$2y$10$FnkngtFrGeu1DPtasu68euDpJksCU3o092gIFV0H.N0WW2YTB88.K', NOW());
6. Execute INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ('testclient', '$2y$10$tdDUYJ5zEPdQgVThmzmRm.sZklfCdfRwDMoAH2V2ANRIK72a6cqt6', '/oauth/receivecode');

Login: /api/user/login
---
    headers:
        Authorization: Basic dGVzdGNsaWVudDp0ZXN0Y2xpZW50
        Content-Type: application/json
    body:
    {
        "username": "johndoe",
        "password": "123456",
        "grant_type": "password"
    }

How to ...
-------------------------------------------

- Generate proxies:
`/var/www/stepon/vendor/bin/doctrine-module orm:generate:proxies`
- Generate migration file:
`/var/www/stepon/vendor/bin/doctrine-module migrations:generate`