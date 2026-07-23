<?php
use function Laravel\Folio\{name};

name('home.simple');
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - Test</title>
    @vite(['resources/css/app.css'],'themes/TwentyOne')
</head>
<body class="antialiased">
    <div class="container mx-auto p-8">
        <h1 class="text-4xl font-bold mb-8">Benvenuto!</h1>
        <p class="text-lg mb-4">Il sito è funzionante.</p>
        <nav class="space-y-2">
            <div><a href="/admin" class="text-blue-500 hover:underline">Accedi all'Admin</a></div>
            <div><a href="/it/predicts" class="text-blue-500 hover:underline">Visualizza Predizioni</a></div>
        </nav>
    </div>
    @vite(['resources/js/app.js'],'themes/TwentyOne')
</body>
</html>