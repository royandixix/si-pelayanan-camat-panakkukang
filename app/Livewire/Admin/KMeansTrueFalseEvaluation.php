<?php

namespace App\Livewire\Admin;

use App\Models\KMeansResult;
use App\Models\KMeansRun;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class KMeansTrueFalseEvaluation extends Component
{
    public function render(): View
    {
        $run = KMeansRun::query()
            ->where('status', 'completed')
            ->latest('id')
            ->first();

        $results = collect();

        if ($run) {
            $results = KMeansResult::query()
                ->where('kmeans_run_id', $run->id)
                ->orderBy('dataset_name')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        }

        $labels = [
            'Rendah',
            'Sedang',
            'Tinggi',
        ];

        $validated = $results
            ->filter(
                fn (KMeansResult $result): bool =>
                    in_array(
                        $result->reference_label,
                        $labels,
                        true
                    )
            )
            ->values();

        $metrics = [];

        foreach ($labels as $label) {
            $tp = $validated
                ->filter(
                    fn (KMeansResult $row): bool =>
                        $row->reference_label === $label
                        && $row->cluster_label === $label
                )
                ->count();

            $fn = $validated
                ->filter(
                    fn (KMeansResult $row): bool =>
                        $row->reference_label === $label
                        && $row->cluster_label !== $label
                )
                ->count();

            $fp = $validated
                ->filter(
                    fn (KMeansResult $row): bool =>
                        $row->reference_label !== $label
                        && $row->cluster_label === $label
                )
                ->count();

            $tn = $validated
                ->filter(
                    fn (KMeansResult $row): bool =>
                        $row->reference_label !== $label
                        && $row->cluster_label !== $label
                )
                ->count();

            $total = $tp + $tn + $fp + $fn;

            $accuracy = $total > 0
                ? (($tp + $tn) / $total) * 100
                : null;

            $precision = ($tp + $fp) > 0
                ? ($tp / ($tp + $fp)) * 100
                : null;

            $recall = ($tp + $fn) > 0
                ? ($tp / ($tp + $fn)) * 100
                : null;

            $f1 = (
                $precision !== null
                && $recall !== null
                && ($precision + $recall) > 0
            )
                ? (
                    2
                    * ($precision * $recall)
                    / ($precision + $recall)
                )
                : null;

            $metrics[$label] = [
                'tp' => $tp,
                'tn' => $tn,
                'fp' => $fp,
                'fn' => $fn,
                'accuracy' => $accuracy,
                'precision' => $precision,
                'recall' => $recall,
                'f1' => $f1,
            ];
        }

        $correct = $validated
            ->filter(
                fn (KMeansResult $row): bool =>
                    $row->reference_label
                    === $row->cluster_label
            )
            ->count();

        $overallAccuracy = $validated->count() > 0
            ? ($correct / $validated->count()) * 100
            : null;

        return view(
            'livewire.admin.k-means-true-false-evaluation',
            [
                'run' => $run,
                'results' => $results,
                'validated' => $validated,
                'metrics' => $metrics,
                'labels' => $labels,
                'overallAccuracy' => $overallAccuracy,
                'correct' => $correct,
            ]
        );
    }
}
