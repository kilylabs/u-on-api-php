# u-on-api-php

!!ПОКА НЕ ДЛЯ ИСПОЛЬЗОВАНИЯ В PRODUCTION!!

API клиент для U-ON.RU

Установка
------------

Рекомендуемый способ установки через
[Composer](http://getcomposer.org):

```
$ composer require kilylabs/u-on-api-php
```

Использование
-----
#### Инициализация
```php
<?php

require 'vendor/autoload.php';

$client = new Kily\API\UOn\Client('<API_KEY>',[
	'timeout'=>60,
    'debug'=>true,
]);

$res = $client->request()->create([
    'r_id_internal'=>'OLOLO',
    'u_name'=>'ИВАН',
    'u_surname'=>'ИВАНОВ',
    'u_email'=>'spam@sux',
]);
```

TODO
-----
- документация
- валидация методов
