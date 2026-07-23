import preset from '../../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        '../../app/Filament/**/*.php',
        '../../resources/views/filament/**/*.blade.php',
        //'../../vendor/filament/**/*.blade.php',
        '../../vendor/filament/**/*.blade.php',
        '../../Modules/**/resources/views/**/*.blade.php',
        '../../Themes/**/resources/views/**/*.blade.php',
        '../../Modules/**/Filament/**/*.php',
        '../../../public_html/vendor/**/*.blade.php',
    ],
}
