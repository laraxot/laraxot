<<<<<<<< HEAD:laravel/Modules/Xot/docs/_archive/root-txt-files/custom_errors.md
---
title: "Custom errors"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da custom_errors.txt (documento) da convert-docs-txt-to-md.py."
---

# Custom errors

========
>>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php):laravel/Modules/Xot/docs/_archive/custom-errors.md
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