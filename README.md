### Logger initialization:

```php

require_once "vendor/autoload.php";

use Tleckie\Log\Handler\StreamHandler;
use Tleckie\Log\Handler\FileHandler;
use Tleckie\Log\Level;
use Tleckie\Log\Log;

$handlers = [
    new StreamHandler(Level::DEBUG),
    new FileHandler(Level::DEBUG, '/tmp/error.log')
];

$log = new Log($handlers);

class DummyToString {
    public function __toString(): string
    {
        return 'My string';
    }
}

class DummySerialize implements \JsonSerializable {
    public function jsonSerialize()
    {
        return [
            'value1',
            'value2'
        ];
    }
}

$log->alert(new Exception('Test alert message'),[1,2]);
$log->critical('Test critical message: {A} => {B}', ['A' => '(A value)', 'B' => '(B value)']);
$log->info('Test info message');
$log->error(new Exception('Test error message'));
$log->emergency('Test emergency message');
$log->emergency(['{A}' => 155555, '{B}' => 99999], ['A' => '(A value)', 'B' => '(B value)']);
$log->emergency(['A' => 155555, 'B' => 99999]);
$log->emergency(new class(){});
$log->emergency(new DummyToString());
$log->emergency(new DummySerialized());
```

#### Output:

```txt
[2021-04-20T13:24:35+02:00] channel.ALERT "Test alert message" {"class":"Exception","message":"Test alert message","code":0,"file":"/log/index.php:34"} [1,2]
[2021-04-20T13:24:35+02:00] channel.CRITICAL "Test critical message: (A value) => (B value)" {} {"A":"(A value)","B":"(B value)"}
[2021-04-20T13:24:35+02:00] channel.INFO "Test info message" {} []
[2021-04-20T13:24:35+02:00] channel.ERROR "Test error message" {"class":"Exception","message":"Test error message","code":0,"file":"/log/index.php:37"} []
[2021-04-20T13:24:35+02:00] channel.EMERGENCY "Test emergency message" {} []
[2021-04-20T13:24:35+02:00] channel.EMERGENCY "{\"(A value)\":155555,\"(B value)\":99999}" {} {"A":"(A value)","B":"(B value)"}
[2021-04-20T13:24:35+02:00] channel.EMERGENCY "{\"A\":155555,\"B\":99999}" {} []
[2021-04-20T13:24:35+02:00] channel.EMERGENCY "class@anonymous\u0000/log/index.php:41$2" {} []
[2021-04-20T13:24:35+02:00] channel.EMERGENCY "My string" {} []
[2021-04-20T13:24:35+02:00] channel.EMERGENCY "[\"value1\",\"value2\"]" {} []
```

#### Default line format:

```txt
[%date%] %channel%.%level% %message% %context%
```

```php
<?php

require_once "vendor/autoload.php";

use Tleckie\Log\Handler\StreamHandler;
use Tleckie\Log\Level;
use Tleckie\Log\Log;

$handlers = [new StreamHandler(Level::DEBUG)];

// change line format
$lineFormat = "[%date%] [%channel%] => (%level%) %message% %context%";
$log = new Log($handlers, 'channelName', $lineFormat);
```

#### Output:

```txt
[2021-04-20T13:33:03+02:00] [channel] => (ALERT) "Test alert message" {"class":"Exception","message":"Test alert message","code":0,"file":"/log/index.php:34"} [1,2]
[2021-04-20T13:33:03+02:00] [channel] => (CRITICAL) "Test critical message: (A value) => (B value)" {} {"A":"(A value)","B":"(B value)"}
[2021-04-20T13:33:03+02:00] [channel] => (INFO) "Test info message" {} []
[2021-04-20T13:33:03+02:00] [channel] => (ERROR) "Test error message" {"class":"Exception","message":"Test error message","code":0,"file":"/log/index.php:37"} []
[2021-04-20T13:33:03+02:00] [channel] => (EMERGENCY) "Test emergency message" {} []
[2021-04-20T13:33:03+02:00] [channel] => (EMERGENCY) "{\"(A value)\":155555,\"(B value)\":99999}" {} {"A":"(A value)","B":"(B value)"}
[2021-04-20T13:33:03+02:00] [channel] => (EMERGENCY) "{\"A\":155555,\"B\":99999}" {} []
[2021-04-20T13:33:03+02:00] [channel] => (EMERGENCY) "class@anonymous\u0000/log/index.php:41$2" {} []
[2021-04-20T13:33:03+02:00] [channel] => (EMERGENCY) "My string" {} []
[2021-04-20T13:33:03+02:00] [channel] => (EMERGENCY) "[\"value1\",\"value2\"]" {} []
```