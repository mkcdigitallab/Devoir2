<?php

require_once(dirname(__DIR__) . "/vendor/autoload.php");
//correction pour le chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();
