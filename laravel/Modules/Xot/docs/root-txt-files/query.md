---
title: 'Query'
module: Xot
type: reference
slug: query
description: 'https://laravel-news.com/quickly-dumping-laravel-queries'
tags: [migrato-da-txt, xot]
converted_from: query.txt
created: 2026-08-24
updated: 2026-08-24
---

https://laravel-news.com/quickly-dumping-laravel-queries

\DB::enableQueryLog(); // Enable query log

// Your Eloquent query executed by using get()

dd(\DB::getQueryLog()); // Show results of log


$sql = Str::replaceArray('?', $query->getBindings(), $query->toSql());
