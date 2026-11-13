<?php

declare(strict_types=1);

namespace Modules\Xot\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PathHelper
{
    /**
     * Percorso base del progetto.
     */
    public static string $projectBasePath = '/var/www/html/<nome progetto>';

    /**
     * Percorso base di Laravel.
     */
    public static string $laravelBasePath = '/var/www/html/<nome progetto>/laravel';

    /**
     * Percorso base dei moduli.
     */
    public static string $modulesBasePath = '/var/www/html/<nome progetto>/laravel/Modules';

    /**
     * Ottiene il percorso completo di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso completo del modulo
     */
    public static function modulePath(string $moduleName): string
    {
        return self::$modulesBasePath.'/'.$moduleName;
    }

    /**
     * Ottiene il percorso dei modelli di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso dei modelli
     */
    public static function modelsPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/app/Models';
    }

    /**
     * Ottiene il percorso delle migrazioni di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso delle migrazioni
     */
    public static function migrationsPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/database/migrations';
    }

    /**
     * Ottiene il percorso dei seeder di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso dei seeder
     */
    public static function seedersPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/database/seeders';
    }

    /**
     * Ottiene il percorso dei controller di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso dei controller
     */
    public static function controllersPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/app/Http/Controllers';
    }

    /**
     * Ottiene il percorso delle risorse Filament di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso delle risorse Filament
     */
    public static function filamentResourcesPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/app/Filament/Resources';
    }

    /**
     * Ottiene il percorso dei provider di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso dei provider
     */
    public static function providersPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/app/Providers';
    }

    /**
     * Ottiene il percorso delle viste di un modulo.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso delle viste
     */
    public static function viewsPath(string $moduleName): string
    {
        return self::modulePath($moduleName).'/resources/views';
    }

    /**
     * Verifica se un percorso è corretto secondo le convenzioni del progetto.
     *
<<<<<<< HEAD
     * @param  string  $path  Percorso da verificare
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $path  Percorso da verificare
=======
     * <<<<<<< HEAD
     *
     * @param string $path Percorso da verificare
     *                     =======
     *                     <<<<<<< HEAD
     *                     <<<<<<< HEAD
     *                     <<<<<<< .merge_file_ZovUz4
     * @param string $path Percorso da verificare
     *                     =======
     *                     <<<<<<< .merge_file_rXB1x4
     * @param string $path Percorso da verificare
     *                     =======
     *                     <<<<<<< HEAD
     * @param string $path Percorso da verificare
     *                     =======
     * @param string $path Percorso da verificare
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * <<<<<<< HEAD
     * @param string $path Percorso da verificare
     *                     =======
     *                     <<<<<<< .merge_file_C0p0qa
     *                     =======
     *                     <<<<<<< HEAD
     *                     <<<<<<< .merge_file_ZovUz4
     *                     >>>>>>> .merge_file_SZewhF
     * @param string $path Percorso da verificare
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param string $path Percorso da verificare
     *                     >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $path Percorso da verificare
     *                     >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return bool True se il percorso è corretto, false altrimenti
     */
    public static function isValidPath(string $path): bool
    {
        // Verifica che il percorso contenga /laravel/Modules/ e non solo /Modules/
        if (Str::contains($path, '/Modules/') && ! Str::contains($path, '/laravel/Modules/')) {
            return false;
        }

        return true;
    }

    /**
     * Corregge un percorso errato secondo le convenzioni del progetto.
     *
<<<<<<< HEAD
     * @param  string  $path  Percorso da correggere
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $path  Percorso da correggere
=======
     * <<<<<<< HEAD
     *
     * @param string $path Percorso da correggere
     *                     =======
     *                     <<<<<<< HEAD
     *                     <<<<<<< HEAD
     *                     <<<<<<< .merge_file_ZovUz4
     * @param string $path Percorso da correggere
     *                     =======
     *                     <<<<<<< .merge_file_rXB1x4
     * @param string $path Percorso da correggere
     *                     =======
     *                     <<<<<<< HEAD
     * @param string $path Percorso da correggere
     *                     =======
     * @param string $path Percorso da correggere
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * <<<<<<< HEAD
     * @param string $path Percorso da correggere
     *                     =======
     *                     <<<<<<< .merge_file_C0p0qa
     *                     =======
     *                     <<<<<<< HEAD
     *                     <<<<<<< .merge_file_ZovUz4
     *                     >>>>>>> .merge_file_SZewhF
     * @param string $path Percorso da correggere
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * @param string $path Percorso da correggere
     *                     >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $path Percorso da correggere
     *                     >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return string Percorso corretto
     */
    public static function correctPath(string $path): string
    {
        // Corregge /var/www/html/Modules/ in /var/www/html/<nome progetto>/laravel/Modules/
        if (Str::contains($path, '/var/www/html/Modules/')) {
            $path = Str::replace('/var/www/html/Modules/', self::$modulesBasePath.'/', $path);
        }

        return $path;
    }

    /**
     * Ottiene tutti i moduli disponibili.
     *
     * @return array<string> Array con i nomi dei moduli
     */
    public static function getModules(): array
    {
        $modulesPath = self::$modulesBasePath;

        if (! File::exists($modulesPath)) {
            return [];
        }

        /** @var array<string> $directories */
        $directories = File::directories($modulesPath);

        return array_map(basename(...), $directories);
    }

    /**
     * Verifica se un modulo esiste.
     *
<<<<<<< HEAD
     * @param  string  $moduleName  Nome del modulo
=======
<<<<<<< .merge_file_Nhfnmt
     * @param  string  $moduleName  Nome del modulo
=======
     * <<<<<<< HEAD
     *
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_rXB1x4
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     * @param string $moduleName Nome del modulo
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_cLnhY6
     *
     * >>>>>>> .merge_file_Wir3Xz
     *
     * =======
     * <<<<<<< HEAD
     * @param string $moduleName Nome del modulo
     *                           =======
     *                           <<<<<<< .merge_file_C0p0qa
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< .merge_file_ZovUz4
     *                           >>>>>>> .merge_file_SZewhF
     * @param string $moduleName Nome del modulo
     *
     * <<<<<<< .merge_file_C0p0qa
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_SZewhF
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $moduleName Nome del modulo
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_dWjAVn
>>>>>>> 77b1e2748 (first)
     * @return bool True se il modulo esiste, false altrimenti
     */
    public static function moduleExists(string $moduleName): bool
    {
        return File::exists(self::modulePath($moduleName));
    }
}
