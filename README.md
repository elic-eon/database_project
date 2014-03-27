###Data Base project

####Dependency

1. http server { apache | nginx }
2. PHP and extension
3. Database { MySql | MariaDB }

####Usage
create `config.php`

``` php
<?
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

define('PW_SALT', '');

define('PATH_ROOT_FILE', '');
define('PATH_ROOT_SITE', '');
define('PATH_ROOT_URL', '');
define('PATH_SESSION_STORE', PATH_ROOT_FILE.'/session_store');
?>
```

####Demo
[demo site](http://people.cs.nctu.edu.tw/~cpweng/filghtSchedule/)
