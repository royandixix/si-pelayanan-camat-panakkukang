<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Models\ApplicationStatusHistory;
use App\Models\ServiceApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplicationWorkflowService
{
    public function changeStatus(
        ServiceApplication $application,
        ApplicationStatus $status,
        User $user,
        ?string $notes = null,
    ): ServiceApplication {
        $this->authorize($application, $user);
        $this->validateTransition($application->status, $status);

        return DB::transaction(function () use (
            $application,
            $status,
            $user,
            $notes,
        ): ServiceApplication {
            $fromStatus = $application->status;

            $data = [
                'status' => $status,
                'assigned_admin_id' => $application->assigned_admin_id ?? $user->id,
                'internal_notes' => filled($notes)
                    ? $notes
                    : $application->internal_notes,
            ];

            if ($status === ApplicationStatus::SUBMITTED) {
                $data['submitted_at'] = $application->submitted_at ?? now();
            }

            if ($status === ApplicationStatus::VERIFICATION) {
                $data['verified_at'] = null;
                $data['rejected_at'] = null;
            }

            if ($status === ApplicationStatus::APPROVED) {
                $data['verified_at'] = now();
                $data['rejected_at'] = null;
            }

            if ($status === ApplicationStatus::REJECTED) {
                $data['rejected_at'] = now();
            }

            if ($status === ApplicationStatus::PROCESSING) {
                $data['rejected_at'] = null;
            }

            if ($status === ApplicationStatus::COMPLETED) {
                $data['completed_at'] = now();
                $data['rejected_at'] = null;
            }

            if ($status === ApplicationStatus::COLLECTED) {
                $data['completed_at'] = $application->completed_at ?? now();
            }

            $application->update($data);

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'changed_by' => $user->id,
                'from_status' => $fromStatus,
                'to_status' => $status,
                'notes' => $notes,
                'metadata' => [
                    'section_id' => $application->service?->section_id,
                    'service_id' => $application->service_id,
                    'admin_id' => $user->id,
                ],
                'created_at' => now(),
            ]);

            return $application->fresh([
                'user',
                'service.section',
                'assignedAdmin',
                'documents',
                'statusHistories.changer',
            ]);
        });
    }

    private function authorize(
        ServiceApplication $application,
        User $user,
    ): void {
        $application->loadMissing('service');

        if (
            ! $user->isAdminSeksi()
            || $user->section_id === null
            || $application->service?->section_id !== $user->section_id
        ) {
            abort(403);
        }
    }

    private function validateTransition(
        ApplicationStatus $from,
        ApplicationStatus $to,
    ): void {
        $allowedTransitions = [
            ApplicationStatus::DRAFT->value => [
                ApplicationStatus::SUBMITTED,
            ],
            ApplicationStatus::SUBMITTED->value => [
                ApplicationStatus::VERIFICATION,
                ApplicationStatus::REJECTED,
            ],
            ApplicationStatus::VERIFICATION->value => [
                ApplicationStatus::REVISION,
                ApplicationStatus::APPROVED,
                ApplicationStatus::REJECTED,
            ],
            ApplicationStatus::REVISION->value => [
                ApplicationStatus::SUBMITTED,
                ApplicationStatus::VERIFICATION,
                ApplicationStatus::REJECTED,
            ],
            ApplicationStatus::APPROVED->value => [
                ApplicationStatus::PROCESSING,
            ],
            ApplicationStatus::PROCESSING->value => [
                ApplicationStatus::COMPLETED,
                ApplicationStatus::REJECTED,
            ],
            ApplicationStatus::COMPLETED->value => [
                ApplicationStatus::COLLECTED,
            ],
            ApplicationStatus::REJECTED->value => [],
            ApplicationStatus::COLLECTED->value => [],
        ];

        if (! in_array($to, $allowedTransitions[$from->value] ?? [], true)) {
            throw ValidationException::withMessages([
                'status' => "Perubahan status dari {$from->label()} ke {$to->label()} tidak diizinkan.",
            ]);
        }
    }
}