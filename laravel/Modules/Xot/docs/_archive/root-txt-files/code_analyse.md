---
title: 'Download using curl'
module: Xot
type: reference
slug: code-analyse
description: '//---------------------------------------------------------- phpstan install:'
tags: [migrato-da-txt, xot]
converted_from: code_analyse.txt
created: 2026-08-24
updated: 2026-08-24
---

//----------------------------------------------------------
phpstan
install:

cmd:
./vendor/bin/phpstan analyse ./Modules/Xot


//----------------------------------------------------------
https://github.com/phan/phan/wiki/Getting-Started

//----------------------------------------------------------
https://github.com/phpro/grumphp

//----------------------------------------------------------
https://github.com/phpmetrics/PhpMetrics
install:
composer require phpmetrics/phpmetrics --dev

cmd:
php ./vendor/bin/phpmetrics --report-html=../_phpmetrics_report Modules
//----------------------------------------------------------
https://github.com/squizlabs/PHP_CodeSniffer
install:
# Download using curl
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar

# Or download using wget
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar

cmd:
php phpcs.phar ./Modules
//----------------------------------------------------------
https://github.com/sebastianbergmann/phpcpd

$ wget https://phar.phpunit.de/phpcpd.phar

$ php phpcpd.phar --version


//---------------------
https://scrutinizer-ci.com/docs/tools/php/php-scrutinizer/

//--------------------
https://github.com/Qafoo/QualityAnalyzer

install:
git clone https://github.com/Qafoo/QualityAnalyzer.git
cd QualityAnalyzer
composer install

cmd:
bin/analyze analyze /path/to/source
//-------------------------------------------------------------

https://psalm.dev/docs/running_psalm/installation/

//--------------------------------------------------------------------
https://github.com/scrutinizer-ci/php-analyzer
https://medium.com/bumble-tech/php-code-static-analysis-based-on-the-example-of-phpstan-phan-and-psalm-a20654c4011d
https://link.medium.com/fPm2yP1xrZ

https://geekflare.com/php-security-scanner/

https://hub.docker.com/r/adamculp/php-code-quality
https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html


https://github.com/enlightn/enlightn

 "edgedesign/phpqa": "^1.23",

 "phan/phan": "^4.0",
        "phpmetrics/phpmetrics": "^2.7",
<<<<<<<< HEAD:laravel/Modules/Xot/docs/_archive/root-txt-files/code_analyse.md
        "phpunit/php-code-coverage": "^9.2",
========
        "phpunit/php-code-coverage": "^9.2",
>>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php):laravel/Modules/Xot/docs/_archive/code-analyse.md
