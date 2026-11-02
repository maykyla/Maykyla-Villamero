<?php

interface Observer
{
    public function update(float $grade): void;
}

interface Subject
{
    public function attach(Observer $observer): void;

    public function detach(Observer $observer): void;

    public function notify(): void;
}

class GradeSystem implements Subject
{
    /** @var Observer[] */
    private array $observers = [];
    private float $grade = 0.0;

    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            fn(Observer $o) => $o !== $observer
        );
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this->grade);
        }
    }

    public function setGrade(float $grade): void
    {
        $this->grade = $grade;
        echo "Grade updated: " . number_format($grade, 2) . "\n";
        $this->notify();
    }
}

class StudentPortal implements Observer
{
    public function update(float $grade): void
    {
        echo "Student Portal:\nNew grade posted.\n";
    }
}

class EmailNotification implements Observer
{
    public function update(float $grade): void
    {
        echo "Email Notification:\nYour grade has been updated.\n";
    }
}

class ParentNotification implements Observer
{
    public function update(float $grade): void
    {
        echo "Parent Notification:\nStudent grade has been posted.\n";
    }
}

/**
 * Challenge: added without touching GradeSystem.
 */
class SmsNotification implements Observer
{
    public function update(float $grade): void
    {
        echo "SMS Notification:\nGrade update sent to your phone.\n";
    }
}

// ---------- Demonstration ----------

echo "=== OBSERVER PATTERN: Grade Notification System ===\n\n";

$gradeSystem = new GradeSystem();
$gradeSystem->attach(new StudentPortal());
$gradeSystem->attach(new EmailNotification());
$gradeSystem->attach(new ParentNotification());

$gradeSystem->setGrade(1.75);

echo "\n--- Challenge: add a new observer without modifying GradeSystem ---\n\n";

$gradeSystem->attach(new SmsNotification());
$gradeSystem->setGrade(1.50);
