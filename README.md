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

