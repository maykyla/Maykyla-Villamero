<?php

/**
 * Command interface: every action is wrapped in an object
 * that can be executed and undone.
 */
interface Command
{
    public function execute(): void;

    public function undo(): void;

    public function getName(): string;
}

/**
 * Receiver: the object that actually performs the work.
 */
class StudentSystem
{
    private bool $enrolled = false;
    private bool $subjectDropped = false;
    private bool $loggedIn = true;

    public function enroll(): void
    {
        $this->enrolled = true;
        echo "Student successfully enrolled.\n";
    }

    public function cancelEnrollment(): void
    {
        $this->enrolled = false;
        echo "Enrollment cancelled (undone).\n";
    }

    public function dropSubject(): void
    {
        $this->subjectDropped = true;
        echo "Subject successfully dropped.\n";
    }

    public function restoreSubject(): void
    {
        $this->subjectDropped = false;
        echo "Subject restored (undone).\n";
    }

    public function printRegistration(): void
    {
        $status = $this->enrolled ? 'ENROLLED' : 'NOT ENROLLED';
        $subject = $this->subjectDropped ? 'one subject dropped' : 'all subjects active';
        echo "Registration printed: {$status}, {$subject}.\n";
    }

    public function logout(): void
    {
        $this->loggedIn = false;
        echo "Student logged out.\n";
    }

    public function login(): void
    {
        $this->loggedIn = true;
        echo "Student logged back in (undone).\n";
    }
}

class EnrollCommand implements Command
{
    public function __construct(private StudentSystem $system)
    {
    }

    public function execute(): void
    {
        $this->system->enroll();
    }

    public function undo(): void
    {
        $this->system->cancelEnrollment();
    }

    public function getName(): string
    {
        return 'Enroll Student';
    }
}

class DropSubjectCommand implements Command
{
    public function __construct(private StudentSystem $system)
    {
    }

    public function execute(): void
    {
        $this->system->dropSubject();
    }

    public function undo(): void
    {
        $this->system->restoreSubject();
    }

    public function getName(): string
    {
        return 'Drop Subject';
    }
}

class PrintRegistrationCommand implements Command
{
    public function __construct(private StudentSystem $system)
    {
    }

    public function execute(): void
    {
        $this->system->printRegistration();
    }

    public function undo(): void
    {
        echo "Nothing to undo for printing.\n";
    }

    public function getName(): string
    {
        return 'Print Registration';
    }
}

class LogoutCommand implements Command
{
    public function __construct(private StudentSystem $system)
    {
    }

    public function execute(): void
    {
        $this->system->logout();
    }

    public function undo(): void
    {
        $this->system->login();
    }

    public function getName(): string
    {
        return 'Logout';
    }
}

/**
 * Invoker: runs commands and keeps a history so they can be undone.
 */
class CommandInvoker
{
    /** @var Command[] */
    private array $history = [];

    public function run(Command $command): void
    {
        echo "Executing: " . $command->getName() . "\n";
        $command->execute();
        $this->history[] = $command;
        echo "\n";
    }

    public function undo(): void
    {
        if (empty($this->history)) {
            echo "Nothing to undo.\n\n";
            return;
        }

        $command = array_pop($this->history);
        echo "Undo: " . $command->getName() . "\n";
        $command->undo();
        echo "\n";
    }
}

// ---------- Demonstration ----------

echo "=== COMMAND PATTERN: Student System Actions ===\n\n";

$system = new StudentSystem();
$invoker = new CommandInvoker();

$invoker->run(new EnrollCommand($system));
$invoker->run(new DropSubjectCommand($system));
$invoker->run(new PrintRegistrationCommand($system));
$invoker->run(new LogoutCommand($system));

echo "--- Challenge: Undo (most recent command first) ---\n\n";
$invoker->undo(); // Logout
$invoker->undo(); // Print Registration
$invoker->undo(); // Drop Subject
