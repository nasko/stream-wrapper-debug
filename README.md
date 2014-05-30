# Задача: Реализация на потребителски Stream Wrapper

> Да се реализира потребителски Stream Wrapper, който да позволява чрез поточния програмен интерфейс на PHP да записваме и изчитаме изображения в и от MySql база от данни.

Това е проект с няколко класа, които да ни помогнат да изследваме [интерфейса](http://bg2.php.net/manual/en/class.streamwrapper.php) на `streamWrapper` класа в PHP.

### 1. `\StreamWrapper\Debug`

Регистрира се в `public/debug.php`. не прави нищо друго освен да отпечатва имената на всеки от методите на `streamWrapper` интерфейса, когато биват извиквани при извикването на функциите в `public/debug.php`:
- `fopen()`
- `feof()`
- `fread()`
- `fclose()`

В `public/debug.php` можем да извикваме различни функции от поточния интерфейс в PHP, а `\StreamWrapper\Debug` класа ще отпечати съответно извиканите методи от `streamWrapper` интерфейса.

Например, с текущото съдържание на `public/debug.php`:

```php
<?php
require_once 'Bootstrap.php';

stream_wrapper_register('mydebug', '\StreamWrapper\Debug');

$fp = fopen('mydebug://notes_do.jpg', 'r');

while(!feof($fp)){
    echo fread($fp, 8192) . "\n";
}
 
fclose($fp);
?>
```

... изходът на програмата ще е:

```
$ php -f debug.php 
StreamWrapper\Debug::__construct
StreamWrapper\Debug::stream_open
StreamWrapper\Debug::stream_eof
StreamWrapper\Debug::stream_read
StreamWrapper\Debug::stream_eof
1. abc
StreamWrapper\Debug::stream_eof
StreamWrapper\Debug::stream_read
StreamWrapper\Debug::stream_eof
2. abc
StreamWrapper\Debug::stream_eof
StreamWrapper\Debug::stream_read
StreamWrapper\Debug::stream_eof
3. abc
StreamWrapper\Debug::stream_eof
StreamWrapper\Debug::stream_read
StreamWrapper\Debug::stream_eof
4. abc
StreamWrapper\Debug::stream_flush
StreamWrapper\Debug::stream_close
StreamWrapper\Debug::__destruct
$
```

Така вече ще се ориентираме по-лесно, кои методи от `streamWrapper` интерфейса в PHP е нужно да имплементираме в нашия Custom Stream Wrapper.

### 2. `\StreamWrapper\Decorator`

Това е друг клас, който можем да регистрираме като custom stream wrapper. Той има само два магически метода:

- `__construct()`
- `__call()`

В конструктора конфигурира `Decorator::$wrapper` променливата като инстанция на `\StreamWrapper\Wrapper` класа.

Магическият метод `__call()` отпечатва TRACE информация за текущо-извикания метод, след това проверява, дали в `Decorator::$wrapper` инстанцията е съответният метод е имплементиран и ако е - го извиква. Ако пък не е имплементиран - програмата прекъсва изпълнението си с подходящо съобщение за грешка. 

Така можем да се ориентираме, кой метод на интерфейса да имплементираме, преди да продължим с разработката на нашия custom stream wrapper.

При текущото съдържание на `public/decorator.php`:

```php
<?php
require_once 'Bootstrap.php';

stream_wrapper_register('my', '\StreamWrapper\Decorator');

$fp = fopen('my://do.jpg', 'r');

while(!feof($fp)){
    echo fread($fp, 8192) . "\n";
}
 
fclose($fp);
?>
```

... изходът на програмата ще е:

```
$ php -f decorator.php 
CREATED
#  stream_open('my://notes_do.jpg', 'r', 0, NULL)
#0 [internal function]: StreamWrapper\Decorator->__call('stream_open', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_open('my://notes_do.j...', 'r', 0, NULL)
#2 /stream-wrapper-debug/public/decorator.php(6): fopen('my://notes_do.j...', 'r')
#3 {main}

#  stream_eof()
#0 [internal function]: StreamWrapper\Decorator->__call('stream_eof', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_eof()
#2 /stream-wrapper-debug/public/decorator.php(8): feof(Resource id #11)
#3 {main}

#  stream_read(8192)
#0 [internal function]: StreamWrapper\Decorator->__call('stream_read', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_read(8192)
#2 /stream-wrapper-debug/public/decorator.php(9): fread(Resource id #11, 8192)
#3 {main}

#  stream_eof()
#0 [internal function]: StreamWrapper\Decorator->__call('stream_eof', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_eof()
#2 /stream-wrapper-debug/public/decorator.php(9): fread(Resource id #11, 8192)
#3 {main}

1. abc
#  stream_eof()
#0 [internal function]: StreamWrapper\Decorator->__call('stream_eof', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_eof()
#2 /stream-wrapper-debug/public/decorator.php(8): feof(Resource id #11)
#3 {main}

#  stream_read(8192)
#0 [internal function]: StreamWrapper\Decorator->__call('stream_read', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_read(8192)
#2 /stream-wrapper-debug/public/decorator.php(9): fread(Resource id #11, 8192)
#3 {main}

#  stream_eof()
#0 [internal function]: StreamWrapper\Decorator->__call('stream_eof', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_eof()
#2 /stream-wrapper-debug/public/decorator.php(9): fread(Resource id #11, 8192)
#3 {main}

2. abc
#  stream_flush()
#0 [internal function]: StreamWrapper\Decorator->__call('stream_flush', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_flush()
#2 /stream-wrapper-debug/public/decorator.php(12): fclose(Resource id #11)
#3 {main}

#  stream_close()
#0 [internal function]: StreamWrapper\Decorator->__call('stream_close', Array)
#1 [internal function]: StreamWrapper\Decorator->stream_close()
#2 /stream-wrapper-debug/public/decorator.php(12): fclose(Resource id #11)
#3 {main}

$
```