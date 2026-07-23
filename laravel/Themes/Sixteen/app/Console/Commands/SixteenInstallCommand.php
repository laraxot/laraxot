<?php

declare(strict_types=1);

namespace Themes\Sixteen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Comando Artisan per l'installazione completa del tema Sixteen
 */
class SixteenInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sixteen:install {--force : Overwrite any existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Install Sixteen theme with all required files and dependencies';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Installing Sixteen theme...');

        // Step 1: Publish theme files
        $this->info('📦 Publishing theme files...');
        $this->call('sixteen:publish', [
            '--force' => $this->option('force'),
        ]);

        // Step 2: Create environment variables template
        $this->createEnvTemplate();

        // Step 3: Clear caches
        $this->info('🧹 Clearing caches...');
        $this->call('optimize:clear');

        // Step 4: Show completion
        $this->showCompletionMessage();

        return self::SUCCESS;
    }

    /**
     * Create .env template with Sixteen variables
     */
    protected function createEnvTemplate(): void
    {
        $this->info('📄 Creating .env template...');

        $envExample = base_path('.env.sixteen.example');

        $template = $this->getEnvTemplate();

        File::put($envExample, $template);

        $this->comment("Created {$envExample}");
        $this->line('You can copy these variables to your .env file');
    }

    /**
     * Get the environment variables template
     */
    protected function getEnvTemplate(): string
    {
        return <<<'ENV'
# Sixteen Theme Configuration
# Copy these variables to your .env file and customize as needed

# App Information
SIXTEEN_APP_NAME="Applicazione PA"
SIXTEEN_TAGLINE="Servizi digitali per i cittadini"
SIXTEEN_DESCRIPTION="Ente di appartenenza"
SIXTEEN_VERSION="1.0.0"

# Brand Configuration
SIXTEEN_LOGO_TYPE=icon
SIXTEEN_LOGO_SOURCE="heroicon-o-building-office"
SIXTEEN_LOGO_ALT="Logo istituzionale"
SIXTEEN_PRIMARY_COLOR="#0066CC"
SIXTEEN_SECONDARY_COLOR="#5A6772"

# Layout Settings
SIXTEEN_SLIM_HEADER=true
SIXTEEN_SHOW_SEARCH=true
SIXTEEN_SHOW_SOCIAL=true
SIXTEEN_BREADCRUMBS=true
SIXTEEN_BACK_TO_TOP=true
SIXTEEN_COOKIEBAR=true

# Contact Information
SIXTEEN_ADDRESS="Via Roma 1<br>00100 Roma (RM)"
SIXTEEN_PHONE="+39 06 12345678"
SIXTEEN_EMAIL="info@comune.esempio.it"
SIXTEEN_PEC="protocollo@pec.comune.esempio.it"
SIXTEEN_CF_PIVA="12345678901"
SIXTEEN_IPA_CODE="c_a123"

# Performance & SEO
SIXTEEN_LAZY_LOADING=true
SIXTEEN_PRELOAD_CSS=true
SIXTEEN_CACHE_MENU=true
SIXTEEN_OG_ENABLED=true
SIXTEEN_SCHEMA_ENABLED=true

# Accessibility
SIXTEEN_SKIP_LINKS=true
SIXTEEN_KEYBOARD_NAV=true
SIXTEEN_SCREEN_READER=true
ENV;
    }

    /**
     * Show installation completion message
     */
    protected function showCompletionMessage(): void
    {
        $this->info('🎉 Sixteen theme installed successfully!');

        $this->comment('Next steps:');
        $this->line('1. Copy variables from .env.sixteen.example to your .env file');
        $this->line('2. Customize the configuration in config/sixteen/sixteen.php');
        $this->line('3. Run "npm install && npm run build" to build assets');
        $this->line('4. Update your layout to use pub_theme views');

        $this->warn('⚠️  Remember to:');
        $this->line('• Configure your web server to serve static assets');
        $this->line('• Set up proper permissions for the public/themes directory');
        $this->line('• Test accessibility features with screen readers');
    }
}
