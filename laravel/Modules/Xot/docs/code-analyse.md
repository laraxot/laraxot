<<<<<<< HEAD
---
title: 'code_analyse'
module: Xot
type: reference
slug: code-analyse
description: '<!-- Contenuto migrato da _docs/code_analyse.txt -->'
tags: [migrato-da-txt, xot]
converted_from: code_analyse.txt
created: 2026-08-24
updated: 2026-08-24
---

# code_analyse

<!-- Contenuto migrato da _docs/code_analyse.txt -->

=======
>>>>>>> 7f6cf6be (.)
//----------------------------------------------------------
phpstan
install:

cmd:
./vendor/bin/phpstan analyse ./Modules/Xot

<<<<<<< HEAD
=======

>>>>>>> 7f6cf6be (.)
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
<<<<<<< HEAD

=======
>>>>>>> 7f6cf6be (.)
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

<<<<<<< HEAD
//---------------------
https://scrutinizer-ci.com/project_docs/tools/php/php-scrutinizer/
=======

//---------------------
https://scrutinizer-ci.com/docs/tools/php/php-scrutinizer/
>>>>>>> 7f6cf6be (.)

//--------------------
https://github.com/Qafoo/QualityAnalyzer

install:
git clone https://github.com/Qafoo/QualityAnalyzer.git
cd QualityAnalyzer
composer install

cmd:
bin/analyze analyze /path/to/source
//-------------------------------------------------------------

<<<<<<< HEAD
https://psalm.dev/project_docs/running_psalm/installation/
=======
https://psalm.dev/docs/running_psalm/installation/
>>>>>>> 7f6cf6be (.)

//--------------------------------------------------------------------
https://github.com/scrutinizer-ci/php-analyzer
https://medium.com/bumble-tech/php-code-static-analysis-based-on-the-example-of-phpstan-phan-and-psalm-a20654c4011d
https://link.medium.com/fPm2yP1xrZ

https://geekflare.com/php-security-scanner/

https://hub.docker.com/r/adamculp/php-code-quality
https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html

<<<<<<< HEAD
=======




>>>>>>> 7f6cf6be (.)
https://github.com/enlightn/enlightn

 "edgedesign/phpqa": "^1.23",

 "phan/phan": "^4.0",
        "phpmetrics/phpmetrics": "^2.7",
<<<<<<< HEAD
        "phpunit/php-code-coverage": "^9.2",
=======
        "phpunit/php-code-coverage": "^9.2",
>>>>>>> 7f6cf6be (.)
