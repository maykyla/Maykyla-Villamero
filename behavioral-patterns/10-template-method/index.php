<?php

abstract class Report
{
    /**
     * Template method: fixes the order of the steps.
     */
    final public function generateReport(): void
    {
        $this->generateHeader();
        $data = $this->retrieveData();
        $this->formatData($data);
        $this->generateFooter();
    }

    abstract protected function title(): string;

    abstract protected function retrieveData(): array;

    abstract protected function formatData(array $data): void;

    protected function generateHeader(): void
    {
        echo str_repeat('=', 24) . "\n";
        echo $this->title() . "\n";
        echo str_repeat('=', 24) . "\n";
    }

    protected function generateFooter(): void
    {
        echo str_repeat('=', 24) . "\n";
        echo "END OF REPORT\n";
        echo str_repeat('=', 24) . "\n\n";
    }
}

class StudentReport extends Report
{
    protected function title(): string
    {
        return 'STUDENT REPORT';
    }

    protected function retrieveData(): array
    {
        return ['id' => '2026-001', 'name' => 'Juan Dela Cruz', 'program' => 'BSIT'];
    }

    protected function formatData(array $data): void
    {
        echo "Student ID: {$data['id']}\n";
        echo "Name: {$data['name']}\n";
        echo "Program: {$data['program']}\n";
    }
}

class GradeReport extends Report
{
    protected function title(): string
    {
        return 'GRADE REPORT';
    }

    protected function retrieveData(): array
    {
        return ['Programming 1' => 1.50, 'Database Systems' => 1.75, 'Networking' => 2.00];
    }

    protected function formatData(array $data): void
    {
        foreach ($data as $subject => $grade) {
            echo str_pad($subject, 20) . number_format($grade, 2) . "\n";
        }
    }
}

class EnrollmentReport extends Report
{
    protected function title(): string
    {
        return 'ENROLLMENT REPORT';
    }

    protected function retrieveData(): array
    {
        return ['term' => '1st Semester 2026-2027', 'units' => 21, 'status' => 'Enrolled'];
    }

    protected function formatData(array $data): void
    {
        echo "Term: {$data['term']}\n";
        echo "Units: {$data['units']}\n";
        echo "Status: {$data['status']}\n";
    }
}

// ---------- Demonstration ----------

echo "=== TEMPLATE METHOD PATTERN: Student Report Generation ===\n\n";

$reports = [new StudentReport(), new GradeReport(), new EnrollmentReport()];

foreach ($reports as $report) {
    $report->generateReport();
}
