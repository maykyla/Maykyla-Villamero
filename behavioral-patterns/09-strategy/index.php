<?php

interface GradeStrategy
{
    public function name(): string;

    /** @param float[] $grades */
    public function calculate(array $grades): float;
}

class SimpleAverageStrategy implements GradeStrategy
{
    public function name(): string
    {
        return 'Simple Average';
    }

    public function calculate(array $grades): float
    {
        return array_sum($grades) / count($grades);
    }
}

class WeightedAverageStrategy implements GradeStrategy
{
    /** @param float[] $weights must add up to 1.0 and match the grades order */
    public function __construct(private array $weights)
    {
    }

    public function name(): string
    {
        return 'Weighted Average';
    }

    public function calculate(array $grades): float
    {
        $total = 0.0;
        foreach ($grades as $i => $grade) {
            $total += $grade * $this->weights[$i];
        }
        return $total;
    }
}

/**
 * Challenge: new strategy added without modifying GradeCalculator.
 * Converts a percentage into the Philippine 1.0 - 5.0 scale.
 */
class TransmutedGradeStrategy implements GradeStrategy
{
    public function name(): string
    {
        return 'Transmuted Grade';
    }

    public function calculate(array $grades): float
    {
        $average = array_sum($grades) / count($grades);

        return match (true) {
            $average >= 97 => 1.00,
            $average >= 94 => 1.25,
            $average >= 91 => 1.50,
            $average >= 88 => 1.75,
            $average >= 85 => 2.00,
            $average >= 82 => 2.25,
            $average >= 79 => 2.50,
            $average >= 76 => 2.75,
            $average >= 75 => 3.00,
            default => 5.00,
        };
    }
}

class GradeCalculator
{
    public function __construct(private GradeStrategy $strategy)
    {
    }

    public function setStrategy(GradeStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function calculate(array $grades): void
    {
        echo "Strategy: " . $this->strategy->name() . "\n";
        echo "Result: " . number_format($this->strategy->calculate($grades), 2) . "\n\n";
    }
}

// ---------- Demonstration ----------

echo "=== STRATEGY PATTERN: Grade Calculation System ===\n\n";

// Quizzes, Activities, Midterm Exam, Final Exam
$grades = [80, 87, 92, 95];

$calculator = new GradeCalculator(new SimpleAverageStrategy());
$calculator->calculate($grades);

// Weights: Quizzes 10%, Activities 15%, Midterm 35%, Final 40%
$calculator->setStrategy(new WeightedAverageStrategy([0.10, 0.15, 0.35, 0.40]));
$calculator->calculate($grades);

echo "--- Challenge: TransmutedGradeStrategy ---\n\n";
$calculator->setStrategy(new TransmutedGradeStrategy());
$calculator->calculate($grades);
