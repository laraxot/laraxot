cd laravel
echo "Aggiorno composer..."
sudo php -d memory_limit=-1 composer.phar selfupdate
sudo rm -rf ./app/View/Components/vendor/
sudo rm -rf ./resources/views/vendor/
echo "Aggiorno librerie..."
sudo COMPOSER_ALLOW_SUPERUSER=1 php -d memory_limit=-1 composer.phar update -W --no-interaction 1>/dev/null
echo "Pubblico variabili e configurazioni..."
sudo php artisan vendor:publish --all --quiet
sudo rm -rf database/migrations/*
sudo rm -rf ./app/View/Components/vendor/
echo "Eseguo migrazioni..."
sudo php artisan migrate --force --no-interaction --quiet
echo "Aggiorno Filament..."
sudo php artisan filament:upgrade --quiet
echo "Ottimizzo Filament..."
sudo php artisan filament:optimize --quiet
echo "Ottimizzo applicazione..."
#sudo php artisan optimize --quiet
sudo php artisan livewire:publish --assets
sudo php artisan config:cache
sudo php artisan view:cache
sudo php artisan icons:cache
echo "Pulizia route..."
sudo php artisan route:clear --quiet

# riaggiorno i permessi
echo "Aggiornamento permessi..."
sudo chown -R www-data:www-data .
sudo chmod -R g+w .

echo "Deploy completato con successo!"

echo "DEPLOY_SUCCESS"
