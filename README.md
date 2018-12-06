# jijihttpclient
jiji  make a http client

## Installation & loading
jijihttpclient is available on [Packagist](https://packagist.org/packages/phpmailer/phpmailer) (using semantic versioning), and installation via [Composer](https://getcomposer.org) is the recommended way to install PHPMailer. Just add this line to your `composer.json` file:

```json
"jiji/http": "^1.1"
```

or run

```sh
composer require jiji/http
```
## A Simple Example

```php
<?php
require __DIR__."/../vendor/autoload.php";

class Test{
    public function __construct()
    {

        $client = new \Jiji\Http\Client();
        $client->get("https://www.apiopen.top/weatherApi", ['city'=>'成都']);
        
    }
}
new Test();
```
## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
