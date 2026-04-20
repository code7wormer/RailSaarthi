<?php
// Load Environment Variables
require_once __DIR__ . '/env_loader.php';

// RapidAPI Configuration
define('RAPIDAPI_KEY', getenv('RAPIDAPI_KEY') ?: '48769fd4ccmsh640b167a4a55799p10f36djsndc8bfd917aa4');
define('RAPIDAPI_HOST', getenv('RAPIDAPI_HOST') ?: 'indian-railway-irctc.p.rapidapi.com');
define('RAPIDAPI_DB', getenv('RAPIDAPI_DB') ?: 'rapid-api-database');
?>
