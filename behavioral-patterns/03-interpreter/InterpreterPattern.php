<?php

class StudentContext
{
    private array $data;

    public function __construct(string $program, int $year)
    {
        $this->data = [
            'PROGRAM' => strtoupper($program),
            'YEAR' => $year,
        ];
    }

    public function get(string $field): string|int|null
    {
        return $this->data[strtoupper($field)] ?? null;
    }
}

interface Expression
{
    public function interpret(StudentContext $context): bool;
}

class TerminalExpression implements Expression
{
    public function __construct(
        private string $field,
        private string|int $expected
    ) {
    }

    public function interpret(StudentContext $context): bool
    {
       
        return $context->get($this->field) == $this->expected;
    }
}

class AndExpression implements Expression
{
    public function __construct(
        private Expression $left,
        private Expression $right
    ) {
    }

    public function interpret(StudentContext $context): bool
    {
        return $this->left->interpret($context) && $this->right->interpret($context);
    }
}

class OrExpression implements Expression
{
    public function __construct(
        private Expression $left,
        private Expression $right
    ) {
    }

    public function interpret(StudentContext $context): bool
    {
        return $this->left->interpret($context) || $this->right->interpret($context);
    }
}

class ExpressionParser
{
    public function parse(string $query): Expression
    {
        $orParts = preg_split('/\s+OR\s+/i', trim($query));

        $expression = null;
        foreach ($orParts as $orPart) {
            $andExpr = $this->parseAnd($orPart);
            $expression = $expression === null ? $andExpr : new OrExpression($expression, $andExpr);
        }

        return $expression;
    }

    private function parseAnd(string $text): Expression
    {
        $andParts = preg_split('/\s+AND\s+/i', trim($text));

        $expression = null;
        foreach ($andParts as $andPart) {
            $comparison = $this->parseComparison($andPart);
            $expression = $expression === null ? $comparison : new AndExpression($expression, $comparison);
        }

        return $expression;
    }

    private function parseComparison(string $text): Expression
    {
        if (!preg_match('/^\s*(\w+)\s*=\s*(?:"([^"]*)"|(\d+))\s*$/', $text, $m)) {
            throw new InvalidArgumentException("Invalid comparison: {$text}");
        }

        $value = isset($m[3]) && $m[3] !== '' ? (int) $m[3] : $m[2];

        return new TerminalExpression($m[1], $value);
    }
}
