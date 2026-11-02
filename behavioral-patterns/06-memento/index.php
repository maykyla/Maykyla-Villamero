<?php

require_once __DIR__ . '/MementoPattern.php';

echo "=== MEMENTO PATTERN: Student Profile Undo System ===\n\n";

echo "\n\n";

$profile = new StudentProfile('Juan Dela Cruz', 'BSIT', 3, 'juan@school.edu');
$history = new ProfileHistory();

echo "Original Profile:\n";
$profile->display();
echo "\n";

// Save a snapshot BEFORE the change so it can be undone.
$history->push($profile->save());
$profile->setProgram('BSCS');
$profile->setYear(4);

echo "Current Profile:\n";
$profile->display();
echo "\n";

echo "Undo...\n";
$profile->restore($history->pop());
echo "Restored Profile:\n";
$profile->display();
echo "\n";

echo "--- Multiple undo levels ---\n\n";

$history->push($profile->save());
$profile->setEmail('juan.delacruz@school.edu');

$history->push($profile->save());
$profile->setProgram('BSIS');

echo "Current Profile:\n";
$profile->display();
echo "\n";

while (!$history->isEmpty()) {
    echo "Undo...\n";
    $profile->restore($history->pop());
    echo "Restored Profile:\n";
    $profile->display();
    echo "\n";
}

echo "No more history to undo.\n";
