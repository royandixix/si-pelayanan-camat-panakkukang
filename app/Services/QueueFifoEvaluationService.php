<?php

namespace App\Services;

use App\Models\ServiceQueue;

class QueueFifoEvaluationService
{
    public function evaluate(): array
    {
        $queues = ServiceQueue::query()
            ->with([
                'user',
                'service',
                'section',
            ])
            ->whereNotNull('registered_at')
            ->whereNotNull('called_at')
            ->orderBy('queue_date')
            ->orderBy('service_id')
            ->orderBy('sequence')
            ->get();

        $groups = $queues->groupBy(
            fn (ServiceQueue $queue): string =>
                $queue->queue_date->format('Y-m-d')
                . '|'
                . $queue->service_id
        );

        $rows = [];
        $testedGroups = 0;

        foreach ($groups as $group) {
            if ($group->count() < 2) {
                continue;
            }

            $testedGroups++;

            $expected = $group
                ->sortBy(
                    fn (ServiceQueue $queue): string =>
                        sprintf(
                            '%08d|%020d|%08d',
                            $queue->sequence,
                            $queue->registered_at?->timestamp ?? 0,
                            $queue->id,
                        )
                )
                ->values();

            $actual = $group
                ->sortBy(
                    fn (ServiceQueue $queue): string =>
                        sprintf(
                            '%020d|%08d',
                            $queue->called_at?->timestamp ?? 0,
                            $queue->id,
                        )
                )
                ->values();

            $actualPositions = [];

            foreach ($actual as $index => $queue) {
                $actualPositions[$queue->id] = $index + 1;
            }

            foreach ($expected as $index => $queue) {
                $expectedPosition = $index + 1;
                $actualPosition = $actualPositions[$queue->id] ?? null;

                $rows[] = [
                    'id' => $queue->id,
                    'queue_date' => $queue->queue_date?->format('d-m-Y'),
                    'queue_number' => $queue->queue_number,
                    'sequence' => $queue->sequence,
                    'user' => $queue->user?->name ?? '-',
                    'service' => $queue->service?->name ?? '-',
                    'section' => $queue->section?->name ?? '-',
                    'registered_at' => $queue->registered_at?->format('H:i:s'),
                    'called_at' => $queue->called_at?->format('H:i:s'),
                    'service_started_at' => $queue->service_started_at?->format('H:i:s'),
                    'served_at' => $queue->served_at?->format('H:i:s'),
                    'expected_order' => $expectedPosition,
                    'actual_order' => $actualPosition,
                    'is_match' => $actualPosition === $expectedPosition,
                ];
            }
        }

        $total = count($rows);

        $matched = collect($rows)
            ->where('is_match', true)
            ->count();

        $mismatched = $total - $matched;

        return [
            'total' => $total,
            'matched' => $matched,
            'mismatched' => $mismatched,
            'tested_groups' => $testedGroups,
            'percentage' => $total > 0
                ? ($matched / $total) * 100
                : null,
            'rows' => $rows,
        ];
    }
}
