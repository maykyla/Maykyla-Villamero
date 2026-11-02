<?php

class StudentRequest
{
    public function __construct(
        private string $studentName,
        private string $type
    ) {
    }

    public function getStudentName(): string
    {
        return $this->studentName;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

abstract class Handler
{
    private ?Handler $next = null;

    public function setNext(Handler $next): Handler
    {
        $this->next = $next;
        return $next; // allows fluent chaining
    }

    public function handle(StudentRequest $request): void
    {
        if ($this->canHandle($request)) {
            echo $this->office() . ": Processing " . $request->getType() . "...\n";
            return;
        }

        echo $this->office() . ": Cannot process request.\n";

        if ($this->next !== null) {
            $this->next->handle($request);
        } else {
            echo "No office can process this request.\n";
        }
    }

    abstract protected function canHandle(StudentRequest $request): bool;

    abstract protected function office(): string;
}

class StudentAffairsHandler extends Handler
{
    protected function canHandle(StudentRequest $request): bool
    {
        return $request->getType() === 'Good Moral Certificate';
    }

    protected function office(): string
    {
        return 'Student Affairs';
    }
}

class RegistrarHandler extends Handler
{
    protected function canHandle(StudentRequest $request): bool
    {
        return in_array($request->getType(), ['Certificate of Enrollment', 'Transcript of Records'], true);
    }

    protected function office(): string
    {
        return 'Registrar';
    }
}

class FinanceHandler extends Handler
{
    protected function canHandle(StudentRequest $request): bool
    {
        return $request->getType() === 'Payment Concern';
    }

    protected function office(): string
    {
        return 'Finance';
    }
}

class ICTHandler extends Handler
{
    protected function canHandle(StudentRequest $request): bool
    {
        return $request->getType() === 'Student ID Concern';
    }

    protected function office(): string
    {
        return 'ICT';
    }
}

// ---------- Demonstration ----------

echo "=== CHAIN OF RESPONSIBILITY: Student Request Processing ===\n\n";

$studentAffairs = new StudentAffairsHandler();
$registrar = new RegistrarHandler();
$finance = new FinanceHandler();
$ict = new ICTHandler();

$studentAffairs->setNext($registrar)->setNext($finance)->setNext($ict);

$requests = [
    'Transcript of Records',
    'Good Moral Certificate',
    'Student ID Concern',
    'Certificate of Enrollment',
    'Parking Permit',
];

foreach ($requests as $type) {
    echo "Request: {$type}\n";
    $studentAffairs->handle(new StudentRequest('Juan', $type));
    echo "\n";
}
