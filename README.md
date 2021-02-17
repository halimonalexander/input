# Input
Input library that provides easy access to POST data and authorization headers.

![main branch parameter](https://github.com/halimonalexander/input/workflows/php/badge.svg)

## Install
```bash
$ composer require halimonalexander/input
```

## Example of usage
```php
use HalimonAlexander\Input\Input;

$input = new Input();

/** @var int|null $ident */
$ident = $input->get('id', Input::TYPE_INT, true);

/** @var string $post */
$post = $input->post('field', Input::TYPE_STRING, false);

/** @var string|null $auth */
$auth = $input->headers()->auth();

/** @var array $allHeaders */
$allHeaders = $input->headers()->all();
```