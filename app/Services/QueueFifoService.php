<?php

namespace App\Services;

use App\Enums\QueueStatus;
use App\Models\ServiceQueue;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class QueueFifoService
{
    public function call(ServiceQueue $queue): ServiceQueue
    {
        return DB::transaction(function () use ($queue): ServiceQueue {
            $queue = ServiceQueue::query()
                ->lockForUpdate()
                ->findOrFail($queue->id);

            if ($queue->status !== QueueStatus::WAITING) {
                throw new RuntimeException(
                    'Antrean ini sudah tidak berstatus menunggu.'
                );
            }

            $activeQueue = ServiceQueue::query()
                ->whereDate(
                    'queue_date',
                    $queue->queue_date->toDateString()
                )
                ->where('service_id', $queue->service_id)
                ->whereIn('status', [
                    QueueStatus::CALLED->value,
                    QueueStatus::SERVING->value,
                ])
                ->whereKeyNot($queue->id)
                ->exists();

            if ($activeQueue) {
                throw new RuntimeException(
                    'Masih ada antrean yang sedang dipanggil atau dilayani.'
                );
            }

            $firstQueue = ServiceQueue::query()
                ->whereDate(
                    'queue_date',
                    $queue->queue_date->toDateString()
                )
                ->where('service_id', $queue->service_id)
                ->where('status', QueueStatus::WAITING->value)
                ->orderBy('sequence')
                ->orderBy('registered_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (! $firstQueue || $firstQueue->id !== $queue->id) {
                throw new RuntimeException(
                    'FIFO menolak antrean ini. Nomor antrean sebelumnya harus dipanggil terlebih dahulu.'
                );
            }

            $queue->update([
                'status' => QueueStatus::CALLED,
                'called_at' => now(),
            ]);

            return $queue->refresh();
        });
    }

    public function start(ServiceQueue $queue): ServiceQueue
    {
        return DB::transaction(function () use ($queue): ServiceQueue {
            $queue = ServiceQueue::query()
                ->lockForUpdate()
                ->findOrFail($queue->id);

            if ($queue->status !== QueueStatus::CALLED) {
                throw new RuntimeException(
                    'Antrean harus berstatus Dipanggil sebelum mulai dilayani.'
                );
            }

            $queue->update([
                'status' => QueueStatus::SERVING,
                'service_started_at' => now(),
            ]);

            return $queue->refresh();
        });
    }

    public function complete(ServiceQueue $queue): ServiceQueue
    {
        return DB::transaction(function () use ($queue): ServiceQueue {
            $queue = ServiceQueue::query()
                ->lockForUpdate()
                ->findOrFail($queue->id);

            if ($queue->status !== QueueStatus::SERVING) {
                throw new RuntimeException(
                    'Antrean harus berstatus Sedang Dilayani sebelum diselesaikan.'
                );
            }

            $queue->update([
                'status' => QueueStatus::COMPLETED,
                'served_at' => now(),
            ]);

            return $queue->refresh();
        });
    }

    public function skip(ServiceQueue $queue): ServiceQueue
    {
        return DB::transaction(function () use ($queue): ServiceQueue {
            $queue = ServiceQueue::query()
                ->lockForUpdate()
                ->findOrFail($queue->id);

            if ($queue->status !== QueueStatus::CALLED) {
                throw new RuntimeException(
                    'Hanya antrean yang sudah dipanggil yang dapat dilewati.'
                );
            }

            $queue->update([
                'status' => QueueStatus::SKIPPED,
            ]);

            return $queue->refresh();
        });
    }

    public function cancel(ServiceQueue $queue): ServiceQueue
    {
        return DB::transaction(function () use ($queue): ServiceQueue {
            $queue = ServiceQueue::query()
                ->lockForUpdate()
                ->findOrFail($queue->id);

            if (! in_array($queue->status, [
                QueueStatus::WAITING,
                QueueStatus::CALLED,
            ], true)) {
                throw new RuntimeException(
                    'Antrean ini sudah tidak dapat dibatalkan.'
                );
            }

            $queue->update([
                'status' => QueueStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);

            return $queue->refresh();
        });
    }
}
