<?php

interface Visitor
{
    public function visitStudent(Student $student): void;

    public function visitFaculty(Faculty $faculty): void;

    public function visitAlumni(Alumni $alumni): void;
}

interface Element
{
    public function accept(Visitor $visitor): void;
}

class Student implements Element
{
    public function __construct(private string $name, private float $gpa)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGpa(): float
    {
        return $this->gpa;
    }

    public function accept(Visitor $visitor): void
    {
        $visitor->visitStudent($this);
    }
}

class Faculty implements Element
{
    public function __construct(private string $name, private int $yearsOfService)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getYearsOfService(): int
    {
        return $this->yearsOfService;
    }

    public function accept(Visitor $visitor): void
    {
        $visitor->visitFaculty($this);
    }
}

class Alumni implements Element
{
    public function __construct(private string $name, private int $graduationYear)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGraduationYear(): int
    {
        return $this->graduationYear;
    }

    public function accept(Visitor $visitor): void
    {
        $visitor->visitAlumni($this);
    }
}

class CSVExportVisitor implements Visitor
{
    public function visitStudent(Student $student): void
    {
        echo "Student exported: Student,{$student->getName()},{$student->getGpa()}\n";
    }

    public function visitFaculty(Faculty $faculty): void
    {
        echo "Faculty exported: Faculty,{$faculty->getName()},{$faculty->getYearsOfService()}\n";
    }

    public function visitAlumni(Alumni $alumni): void
    {
        echo "Alumni exported: Alumni,{$alumni->getName()},{$alumni->getGraduationYear()}\n";
    }
}

class ReportVisitor implements Visitor
{
    public function visitStudent(Student $student): void
    {
        echo "Report - Student {$student->getName()} (GPA {$student->getGpa()})\n";
    }

    public function visitFaculty(Faculty $faculty): void
    {
        echo "Report - Faculty {$faculty->getName()} ({$faculty->getYearsOfService()} years of service)\n";
    }

    public function visitAlumni(Alumni $alumni): void
    {
        echo "Report - Alumni {$alumni->getName()} (Class of {$alumni->getGraduationYear()})\n";
    }
}

class StatisticsVisitor implements Visitor
{
    private int $students = 0;
    private int $faculty = 0;
    private int $alumni = 0;

    public function visitStudent(Student $student): void
    {
        $this->students++;
    }

    public function visitFaculty(Faculty $faculty): void
    {
        $this->faculty++;
    }

    public function visitAlumni(Alumni $alumni): void
    {
        $this->alumni++;
    }

    public function printSummary(): void
    {
        echo "Students: {$this->students}\n";
        echo "Faculty: {$this->faculty}\n";
        echo "Alumni: {$this->alumni}\n";
        echo "Total records: " . ($this->students + $this->faculty + $this->alumni) . "\n";
    }
}

/**
 * Challenge: new operation added without modifying Student, Faculty or Alumni.
 */
class PDFReportVisitor implements Visitor
{
    public function visitStudent(Student $student): void
    {
        echo "PDF generated for student {$student->getName()}.\n";
    }

    public function visitFaculty(Faculty $faculty): void
    {
        echo "PDF generated for faculty {$faculty->getName()}.\n";
    }

    public function visitAlumni(Alumni $alumni): void
    {
        echo "PDF generated for alumni {$alumni->getName()}.\n";
    }
}

// ---------- Demonstration ----------

echo "=== VISITOR PATTERN: Student Record Operations ===\n\n";

$records = [
    new Student('Juan Dela Cruz', 1.75),
    new Faculty('Prof. Reyes', 12),
    new Alumni('Ana Santos', 2020),
];

$run = function (string $title, Visitor $visitor) use ($records): void {
    echo "{$title}\n";
    foreach ($records as $record) {
        $record->accept($visitor);
    }
    echo "\n";
};

$run('CSV Export Visitor', new CSVExportVisitor());
$run('Report Visitor', new ReportVisitor());

$stats = new StatisticsVisitor();
$run('Statistics Visitor', $stats);
$stats->printSummary();
echo "\n";

$run('Challenge: PDF Report Visitor', new PDFReportVisitor());
