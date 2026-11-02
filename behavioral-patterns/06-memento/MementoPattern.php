<?php

class StudentProfile
{
    public function __construct(
        private string $name,
        private string $program,
        private int $year,
        private string $email
    ) {
    }

    public function setProgram(string $program): void
    {
        $this->program = $program;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function save(): StudentProfileMemento
    {
        return new StudentProfileMemento($this->name, $this->program, $this->year, $this->email);
    }

    public function restore(StudentProfileMemento $memento): void
    {
        $this->name = $memento->getName();
        $this->program = $memento->getProgram();
        $this->year = $memento->getYear();
        $this->email = $memento->getEmail();
    }

    public function display(): void
    {
        echo "Name: {$this->name}\n";
        echo "Program: {$this->program}\n";
        echo "Year: {$this->year}\n";
        echo "Email: {$this->email}\n";
    }
}

class StudentProfileMemento
{
    public function __construct(
        private string $name,
        private string $program,
        private int $year,
        private string $email
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProgram(): string
    {
        return $this->program;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

class ProfileHistory
{
    /** @var StudentProfileMemento[] */
    private array $history = [];

    public function push(StudentProfileMemento $memento): void
    {
        $this->history[] = $memento;
    }

    public function pop(): ?StudentProfileMemento
    {
        return array_pop($this->history);
    }

    public function isEmpty(): bool
    {
        return empty($this->history);
    }
}
