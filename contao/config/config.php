<?php

// Register global frontend output hook
$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][] = [
    \Dud\ContaoRSupBundle\Hook\OutputFrontendTemplate::class,
    'replace'
];
