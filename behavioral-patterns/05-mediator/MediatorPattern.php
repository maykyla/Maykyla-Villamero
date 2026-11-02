<?php

interface Mediator
{
    public function sendMessage(string $message, Student $sender): void;

    public function addStudent(Student $student): void;
}

class Student
{
    public function __construct(
        private string $name,
        private Mediator $mediator
    ) {
        $this->mediator->addStudent($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function send(string $message): void
    {
        echo "{$this->name}: {$message}\n";
        $this->mediator->sendMessage($message, $this);
    }

    public function receive(string $message, Student $from): void
    {
        echo "{$this->name} receives:\n";
        echo "{$from->getName()}: {$message}\n";
    }
}

class ChatMediator implements Mediator
{
    /** @var Student[] */
    private array $students = [];

    public function addStudent(Student $student): void
    {
        $this->students[] = $student;
    }

    public function sendMessage(string $message, Student $sender): void
    {
        foreach ($this->students as $student) {
            if ($student !== $sender) {
                $student->receive($message, $sender);
            }
        }
    }
}
