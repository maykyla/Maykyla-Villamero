<?php

require_once __DIR__ . '/InterpreterPattern.php';

echo "=== INTERPRETER PATTERN: Student Search/Query System ===\n\n";

echo "\n\n";

$parser = new ExpressionParser();

// Sample student: BSIT, 4th year
$student = new StudentContext('BSIT', 4);
echo "Student: PROGRAM = BSIT, YEAR = 4\n\n";

$queries = [
    'PROGRAM = "BSIT"',
    'YEAR = 4',
    'PROGRAM = "BSIT" AND YEAR = 4',
    'PROGRAM = "BSCS" AND YEAR = 4',
    // Challenge: OR support
    'PROGRAM = "BSIT" OR PROGRAM = "BSCS"',
    'PROGRAM = "BSCS" OR PROGRAM = "BSIS"',
];

foreach ($queries as $query) {
    $result = $parser->parse($query)->interpret($student);

    echo "Expression:\n";
    echo $query . "\n";
    echo "Result: " . ($result ? 'TRUE' : 'FALSE') . "\n\n";
}
