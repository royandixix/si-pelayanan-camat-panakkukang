<?php

namespace App\Services;

use App\Models\KMeansRun;

class KMeansEvaluationService
{
    public function evaluate(?int $runId = null): array
    {
        $labels = ['Rendah', 'Sedang', 'Tinggi'];

        $run = $runId
            ? KMeansRun::query()->with('results')->find($runId)
            : KMeansRun::query()->with('results')->latest('id')->first();

        if (! $run) {
            return [
                'run_id' => null,
                'k' => null,
                'iterations' => null,
                'wcss' => null,
                'silhouette_score' => null,
                'labels' => $labels,
                'matrix' => $this->emptyMatrix($labels),
                'total_results' => 0,
                'labeled_results' => 0,
                'unlabeled_results' => 0,
                'coverage' => 0,
                'is_complete' => false,
                'accuracy' => null,
                'precision_macro' => null,
                'recall_macro' => null,
                'f1_macro' => null,
                'class_metrics' => $this->emptyClassMetrics($labels),
            ];
        }

        $results = $run->results;

        $labeled = $results
            ->filter(
                fn ($result) =>
                    in_array($result->reference_label, $labels, true)
                    && in_array($result->cluster_label, $labels, true)
            )
            ->values();

        $matrix = $this->emptyMatrix($labels);

        foreach ($labeled as $result) {
            $matrix[$result->reference_label][$result->cluster_label]++;
        }

        $total = $labeled->count();

        $correct = 0;

        foreach ($labels as $label) {
            $correct += $matrix[$label][$label];
        }

        $classMetrics = [];

        foreach ($labels as $label) {
            $tp = $matrix[$label][$label];

            $fp = 0;

            foreach ($labels as $actual) {
                if ($actual !== $label) {
                    $fp += $matrix[$actual][$label];
                }
            }

            $fn = 0;

            foreach ($labels as $predicted) {
                if ($predicted !== $label) {
                    $fn += $matrix[$label][$predicted];
                }
            }

            $tn = $total - $tp - $fp - $fn;

            $precision = ($tp + $fp) > 0
                ? $tp / ($tp + $fp)
                : 0;

            $recall = ($tp + $fn) > 0
                ? $tp / ($tp + $fn)
                : 0;

            $f1 = ($precision + $recall) > 0
                ? 2 * (($precision * $recall) / ($precision + $recall))
                : 0;

            $classMetrics[$label] = [
                'tp' => $tp,
                'fp' => $fp,
                'fn' => $fn,
                'tn' => $tn,
                'precision' => $precision,
                'recall' => $recall,
                'f1' => $f1,
            ];
        }

        $precisionMacro = array_sum(
            array_column($classMetrics, 'precision')
        ) / count($labels);

        $recallMacro = array_sum(
            array_column($classMetrics, 'recall')
        ) / count($labels);

        $f1Macro = array_sum(
            array_column($classMetrics, 'f1')
        ) / count($labels);

        $totalResults = $results->count();

        return [
            'run_id' => $run->id,
            'k' => $run->k,
            'iterations' => $run->iterations,
            'wcss' => $run->wcss,
            'silhouette_score' => $run->silhouette_score,
            'labels' => $labels,
            'matrix' => $matrix,
            'total_results' => $totalResults,
            'labeled_results' => $total,
            'unlabeled_results' => $totalResults - $total,
            'coverage' => $totalResults > 0
                ? ($total / $totalResults) * 100
                : 0,
            'is_complete' => $totalResults > 0
                && $total === $totalResults,
            'accuracy' => $total > 0
                ? $correct / $total
                : null,
            'precision_macro' => $total > 0
                ? $precisionMacro
                : null,
            'recall_macro' => $total > 0
                ? $recallMacro
                : null,
            'f1_macro' => $total > 0
                ? $f1Macro
                : null,
            'class_metrics' => $classMetrics,
        ];
    }

    private function emptyMatrix(array $labels): array
    {
        $matrix = [];

        foreach ($labels as $actual) {
            foreach ($labels as $predicted) {
                $matrix[$actual][$predicted] = 0;
            }
        }

        return $matrix;
    }

    private function emptyClassMetrics(array $labels): array
    {
        $metrics = [];

        foreach ($labels as $label) {
            $metrics[$label] = [
                'tp' => 0,
                'fp' => 0,
                'fn' => 0,
                'tn' => 0,
                'precision' => 0,
                'recall' => 0,
                'f1' => 0,
            ];
        }

        return $metrics;
    }
}
