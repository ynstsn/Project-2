<?php

include "header.php";
include "function.php";

// Répertoire du fichier autoload.php
spl_autoload_register(function ($className) {
    @include __DIR__ . "/" . strtr($className, "\\", "/") . ".php";
});

// Répertoire Classes du répertoire du fichier autoload.php
spl_autoload_register(function ($className) {
    @include __DIR__ . "/Classes/".strtr($className, "\\", "/").".php";
});
// Répertoire courant
spl_autoload_register(function ($className) {
    @include strtr($className, "\\", "/") . ".php";
});
// Répertoire Classes du répertoire courant
spl_autoload_register(function ($className) {
    @include "Classes/".strtr($className, "\\", "/").".php";
});



