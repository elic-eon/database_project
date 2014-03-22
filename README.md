###Data Base project

####Dependency
1. http server { apache | nginx }
2. PHP and extension
3. Database { MySql | MariaDB }

####Usage
create `config.php`

``` php
<?php
$DB_HOST = "your database host";
$DB_USER = "account";
$DB_PASSWORD = "*******";
$DB_NAME = "nema of Data Base";

$PW_SALT = 'some salt';
?>
```

