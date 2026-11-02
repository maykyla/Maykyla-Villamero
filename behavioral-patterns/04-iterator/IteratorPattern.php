<?php

class Student
{
    public function __construct(
        private string $id,
        private string $name
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
class StudentIterator implements Iterator
{
    private int $position = 0;

    public function __construct(private array $students)
    {
    }

    public function current(): Student
    {
        return $this->students[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->students[$this->position]);
    }
}

class StudentCollection implements IteratorAggregate
{
    private array $students = [];

    public function add(Student $student): void
    {
        $this->students[] = $student;
    }

    public function getIterator(): StudentIterator
    {
        return new StudentIterator($this->students);
    }
}
