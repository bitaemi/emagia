<?php

require_once __DIR__ . '/../bootstrap/autoload.php';

/** 
@var \App\Models\Game\Game $game 
*/
$game = require_once __DIR__ . '/../bootstrap/game.php';
$game->getController()->run();
