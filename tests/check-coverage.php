<?php

declare(strict_types=1);

$coverageFile = __DIR__.'/../coverage.xml';

if (!is_file($coverageFile)) {
    fwrite(STDERR, "Coverage report not found.\n");
    exit(1);
}

$coverage = simplexml_load_file($coverageFile);
if (false === $coverage) {
    fwrite(STDERR, "Coverage report is not valid XML.\n");
    exit(1);
}

$metrics = $coverage->project->metrics;
$coveredStatements = (float) ($metrics['coveredstatements'] ?? 0);
$totalStatements = (float) ($metrics['statements'] ?? 0);

if (0.0 === $totalStatements || $coveredStatements / $totalStatements < 0.9) {
    $percentage = 0.0 === $totalStatements ? 0.0 : 100 * $coveredStatements / $totalStatements;
    fwrite(STDERR, sprintf("Coverage %.2f%% is below the required 90%%.\n", $percentage));
    exit(1);
}

printf("Coverage %.2f%% meets the required 90%%.\n", 100 * $coveredStatements / $totalStatements);
