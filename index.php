<?php

spl_autoload_register(function ($class) {

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $filename = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class . '.php';

    if (file_exists($filename)) {
        require $filename;
    }
});

/**
 * En déclarant la variable $jeu, je :
 * - initialise la session
 * - récupère (si c'est présent) la liste des animaux et des provisions
 */
//$jeu = new Jeu();
$jeu = Jeu::getInstance();

/**
 * - détermine si une action doit être faite
 */
$jeu->executeAction();

/**
 * - procède à l'affichage
 */
$jeu->afficheInterface();

