<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Ajout de ces lignes pour configurer correctement les niveaux d'erreur
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Optionnel: erreurs sont affichées pendant le développement
ini_set('display_errors', '1');

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
