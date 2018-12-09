# Simple package to manipulate URLs

[![Build Status](https://travis-ci.org/KepplerPl/url.svg?branch=master)](https://travis-ci.org/KepplerPl/url)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package contains 2 parts. The parser and the builder.

#### Both can be used individually if you so desire.

The parser will simply parse a url and make available its parts. However you cannot modify them using this class directly. For all intents and purposes it's immutable.

The builder allows you to modify a url.
However you cannot retrieve information directly from it(for example you cannot do $builder->getHost()), you can only modify the existing parts of the url.

You can find information about each bellow.

## Parser

The parser is immutable

```php
require 'vendor/autoload.php';

$urlString = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';

$parser = Parser::from($urlString);

echo $parser->getHost(); // www.example.com
echo $parser->getSchema(); // http
echo $parser->getAuthority(); // john.doe@www.example.com:123

// you can also do
echo Parser::from($urlString)->getHost(); // www.example.com
echo Parser::from($urlString)->getSchema(); // http
echo Parser::from($urlString)->getAuthority(); // john.doe@www.example.com:123

// But this will create a new class instance every time
````

The path and query are kept in separte bags and can be accessed by getting the bag

#### The query bag:

```php
http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top

echo $parser->query->first(); // networking
echo $parser->query->last(); // 2015-11-12
echo $parser->query->get('tag'); // networking
echo $parser->query->original(); // tag=networking&order=newest&date=2015-11-12
...
````

#### The path bag

```php
http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top

echo $parser->path->first(); // forum
echo $parser->path->last(); // questions
echo $parser->path->get(0); // forum
echo $parser->path->original(); // /forum/questions/
...
````

## Builder

```php
require 'vendor/autoload.php';

$parser = Parser::from($url);
$builder = Builder::from($parser);

$builder->path->insertAfter('forum', 'new_path_value');
$builder->query->insertAfter('tag', ['new_query_index' => 'new_query_value']);
//$builder->path->overwrite('forum', 'new_value');
//$builder->path->prepend('prepended');
//echo $builder->path->buildPath(); // /forum/new_path_value/questions/
//echo $builder->path->buildPath(false); // /forum/new_path_value/questions
$builder->setScheme('http');
$builder->setFragment('new_fragment');

// or just chain them

$builder
    ->setScheme('http')
    ->setFragment('new_fragment')
    ->setHost('www.google.com')
    ->setUsername('keppler_pl')
    ->setPassword('hunter2')
    ->setPort(987);

echo $builder->getUrl(true); // withTrainingSlash - implicit value is true
// http://keppler_pl:hunter2@www.google.com:987/forum/new_path_value/questions/?tag=networking&new_query_index=new_query_value&order=newest#new_fragment

echo $builder->getUrl(false); // without trailing slash
// http://keppler_pl:hunter2@www.google.com:987/forum/new_path_value/questions?tag=networking&new_query_index=new_query_value&order=newest#new_fragment
````

You can also use the builder without the parsers, just create a new instance of it.

```php
$builder = new Builder();

$builder
    ->setScheme('http')
    ->setFragment('new_fragment')
    ->setHost('www.google.com')
    ->setUsername('keppler_pl')
    ->setPassword('hunter2')
    ->setPort(987);

echo $builder->getUrl();
// http://keppler_pl:hunter2@www.google.com:987#new_fragment
````

## Installation

```bash
composer require keppler/url
````

