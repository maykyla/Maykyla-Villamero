<?php

require_once __DIR__ . '/IteratorPattern.php';

echo "=== ITERATOR PATTERN: Student Record Collection ===\n\n";

echo "\n\n";

$students = new StudentCollection();
$students->add(new Student('2026-001', 'Juan'));
$students->add(new Student('2026-002', 'Maria'));
$students->add(new Student('2026-003', 'Pedro'));

echo "Student List\n";
foreach ($students as $student) {
    echo $student->getId() . ' - ' . $student->getName() . "\n";
}
