# php-jwt-service

[![Build Status](https://travis-ci.org/alexpts/php-jwt-service.svg?branch=master)](https://travis-ci.org/alexpts/php-jwt-service)
[![Code Coverage](https://scrutinizer-ci.com/g/alexpts/php-jwt-service/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexpts/php-jwt-service/?branch=master)
[![Code Climate](https://codeclimate.com/github/alexpts/php-jwt-service/badges/gpa.svg)](https://codeclimate.com/github/alexpts/php-jwt-service)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/php-jwt-service/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/php-jwt-service/?branch=master)


Simple service with work [JWT tokens](https://jwt.io/).

### Install
`composer require alexpts/php-jwt-service`


### Demo
```php
<?php
use Emarref\Jwt\Algorithm\Hs512;
use PTS\JwtService\JwtService;

$secret = 'sa#FD423efdl#';
$alg = new Hs512($secret);

$service = new JwtService($alg);
$jwtToken = $service->encode(['userId' => 1]);

// with auto expire
$service->setExpire(3600);
$jwtToken2 = $service->encode(['userId' => 1]);


$jwtToken3 = 'some string jst token';
$tokenObject = $service->decode($jwtToken3);
```