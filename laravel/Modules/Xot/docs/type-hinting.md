<<<<<<< HEAD
---
title: "Type hinting"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da type_hinting.txt (documento) da convert-docs-txt-to-md.py."
---

# type_hinting

<!-- Contenuto migrato da _docs/type_hinting.txt -->

=======
>>>>>>> 7f6cf6be (.)
https://mlocati.github.io/articles/php-type-hinting.html
https://howto.webarea.it/php/type-hinting-php-e-controllo-wake-strict-mode_170
https://wiki.php.net/rfc/scalar_type_hints
https://wiki.php.net/rfc/return_types

https://packagist.org/packages/maksi/laravel-idea-type-hinting

<<<<<<< HEAD
=======

>>>>>>> 7f6cf6be (.)
/** @var $post Post */

/** @var $posts Post[] */

/**
     * @Route("/types")
     */

<<<<<<< HEAD
declare(strict_types = 1);

=======

declare(strict_types = 1);


>>>>>>> 7f6cf6be (.)
protected ClassName $classType;

 // Types are also legal on static properties
    public static iterable $staticProp;

  // Types can also be used with the "var" notation
    var bool $flag;

  // Typed properties may have default values (more below)
    public string $str = "foo";
    public ?string $nullableStr = null;

Scalar types

Boolean (bool | boolean)
Integer (int | integer)
Float (float | double)
String (string)

Compound types

array
object
callable
iterable

unction callACallable(
  callable $f
): int {
  return $f('thephp.website');
}

function iterable_map(iterable $list, callable $operation) : iterable
{
  foreach ($list as $k => $v) {
    yield $operation($k, $v);
  }
}

<<<<<<< HEAD
=======

>>>>>>> 7f6cf6be (.)
public static function byArray(iterable $data)
    {
        $results = [];

        foreach($data as $name) {
            $results[] = self::byString($name);
        }

        return $results;
    }

    public static function byString(string $name)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
        $slug = strtolower($slug);

        return $slug;
    }

<<<<<<< HEAD
=======



>>>>>>> 7f6cf6be (.)
https://sodocumentation.net/it/php/topic/504/classi-e-oggetti

 private static $instance = null;

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

<<<<<<< HEAD
=======



>>>>>>> 7f6cf6be (.)
class ClassName
{
    public function foo(): self
    {
        return new ClassName();
    }
}

$instance = new ClassName();
$instance->foo();

<<<<<<< HEAD
=======

>>>>>>> 7f6cf6be (.)
ublic function foo(): ?stdClass
    {
        return new stdClass();
    }

    public function bar(): ?stdClass
    {
        return null;
    }

<<<<<<< HEAD
=======

>>>>>>> 7f6cf6be (.)
function foo(): object
{
    return new stdClass();
}

<<<<<<< HEAD
=======


>>>>>>> 7f6cf6be (.)
Relazioni
https://github.com/larastan/larastan/issues/689

"types have capital letter: HasOne, BelongsTo, HasMany, etc
if using return types, remember to reference them at the beginning with:
use Illuminate\Database\Eloquent\Relations\HasOne;".

esempio:
public function articles(): HasMany {
    return $this->hasMany(Article::class);
}

<<<<<<< HEAD
https://github.com/oucil/Code-Hint-Aggregator
=======



https://github.com/oucil/Code-Hint-Aggregator
>>>>>>> 7f6cf6be (.)
