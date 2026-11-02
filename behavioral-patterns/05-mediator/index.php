<?php

require_once __DIR__ . '/MediatorPattern.php';

echo "=== MEDIATOR PATTERN: Student Communication System ===\n\n";

echo "\n\n";

$chat = new ChatMediator();

$juan = new Student('Juan', $chat);
$maria = new Student('Maria', $chat);
$pedro = new Student('Pedro', $chat);

$juan->send('Hello everyone!');
echo "\n";
$maria->send('Hi Juan! Are we meeting later?');
