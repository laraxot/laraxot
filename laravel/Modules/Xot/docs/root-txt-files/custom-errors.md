---
title: 'Custom errors'
module: Xot
type: reference
slug: custom-errors
description: 'https://tutsforweb.com/how-to-create-custom-404-page-laravel/'
tags: [migrato-da-txt, xot]
converted_from: custom_errors.txt
created: 2026-08-24
updated: 2026-08-24
---

https://tutsforweb.com/how-to-create-custom-404-page-laravel/


public function render($request, Exception $exception)
{
    if ($this->isHttpException($exception)) {
        if (view()->exists('errors.' . $exception->getStatusCode())) {
            return response()->view('errors.' . $exception->getStatusCode(), [], $exception->getStatusCode());
        }
    }

    return parent::render($request, $exception);
}


public function render($request, Exception $exception)
{
    if ($this->isHttpException($exception)) {
        if ($exception->getStatusCode() == 404) {
            return response()->view('errors.' . '404', [], 404);
        }

        if ($exception->getStatusCode() == 500) {
            return response()->view('errors.' . '500', [], 500);
        }
    }

    return parent::render($request, $exception);
}


public function render($request, Exception $exception)
{
    if ($exception instanceof TestingHttpException) {
        return response()->view('errors.testing');
    }
    return parent::render($request, $exception);
}
