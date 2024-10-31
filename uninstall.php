<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

require __DIR__ . '/classes/Config.php';
require __DIR__ . '/classes/Helper.php';
require __DIR__ . '/classes/PDOQB.php';
require __DIR__ . '/classes/Adapter.php';
require __DIR__ . '/classes/DBUpdater.php';

SeamlessSlider\Helper::setConfig( __FILE__ );

SeamlessSlider\DBUpdater::getInstance()->prefixTables();

SeamlessSlider\DBUpdater::getInstance()->prepare()->handleDeletion();
