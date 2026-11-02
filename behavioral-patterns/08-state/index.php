<?php

interface State
{
    public function name(): string;

    public function enroll(Student $student): void;
}

class ApplicantState implements State
{
    public function name(): string
    {
        return 'Applicant';
    }

    public function enroll(Student $student): void
    {
        echo "Student must complete pre-assessment first.\n";
    }
}

class PreAssessedState implements State
{
    public function name(): string
    {
        return 'Pre-Assessed';
    }

    public function enroll(Student $student): void
    {
        echo "Student successfully enrolled.\n";
        $student->setState(new EnrolledState());
    }
}

class EnrolledState implements State
{
    public function name(): string
    {
        return 'Enrolled';
    }

    public function enroll(Student $student): void
    {
        echo "Student is already enrolled.\n";
    }
}

class DroppedState implements State
{
    public function name(): string
    {
        return 'Dropped';
    }

    public function enroll(Student $student): void
    {
        echo "Dropped student must re-apply before enrolling.\n";
    }
}

class GraduatedState implements State
{
    public function name(): string
    {
        return 'Graduated';
    }

    public function enroll(Student $student): void
    {
        echo "Graduated students cannot enroll.\n";
    }
}

class Student
{
    private State $state;

    public function __construct()
    {
        $this->state = new ApplicantState();
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }

    public function getStateName(): string
    {
        return $this->state->name();
    }

    public function enroll(): void
    {
        $this->state->enroll($this);
    }
}

// ---------- Demonstration ----------

echo "=== STATE PATTERN: Student Enrollment Status ===\n\n";

$student = new Student();

$show = function (Student $s): void {
    echo "Current State: " . $s->getStateName() . "\n";
    echo "Action: Enroll\nResult:\n";
    $s->enroll();
    echo "\n";
};

$show($student);

$student->setState(new PreAssessedState());
$show($student);

// After enrolling, the state changed itself to Enrolled.
$show($student);

$student->setState(new DroppedState());
$show($student);

$student->setState(new GraduatedState());
$show($student);
