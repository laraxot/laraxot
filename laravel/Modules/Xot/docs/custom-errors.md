<<<<<<< HEAD
---
title: "Custom errors"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da custom_errors.txt (documento) da convert-docs-txt-to-md.py."
---

# custom_errors

<!-- Contenuto migrato da _docs/custom_errors.txt -->

https://tutsforweb.com/how-to-create-custom-404-page-laravel/

=======
https://tutsforweb.com/how-to-create-custom-404-page-laravel/



>>>>>>> 7f6cf6be (.)
public function render($request, Exception $exception)
{
    if ($this->isHttpException($exception)) {
        if (view()->exists('errors.' . $exception->getStatusCode())) {
            return response()->view('errors.' . $exception->getStatusCode(), [], $exception->getStatusCode());
        }
    }
<<<<<<< HEAD

    return parent::render($request, $exception);
}

=======
 
    return parent::render($request, $exception);
}


>>>>>>> 7f6cf6be (.)
public function render($request, Exception $exception)
{
    if ($this->isHttpException($exception)) {
        if ($exception->getStatusCode() == 404) {
            return response()->view('errors.' . '404', [], 404);
        }
<<<<<<< HEAD

=======
         
>>>>>>> 7f6cf6be (.)
        if ($exception->getStatusCode() == 500) {
            return response()->view('errors.' . '500', [], 500);
        }
    }
<<<<<<< HEAD

    return parent::render($request, $exception);
}

=======
 
    return parent::render($request, $exception);
}


>>>>>>> 7f6cf6be (.)
public function render($request, Exception $exception)
{
    if ($exception instanceof TestingHttpException) {
        return response()->view('errors.testing');
    }
    return parent::render($request, $exception);
<<<<<<< HEAD
}
=======
}
>>>>>>> 7f6cf6be (.)
