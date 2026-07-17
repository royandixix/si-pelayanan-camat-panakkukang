<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\DocumentVerificationStatus;
use App\Enums\UserRole;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusHistory;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\ServiceRequirement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminSeksiTestSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function(): void {
            $service=Service::where('section_id',4)
                ->where('code','PEMBUATAN_KTP')
                ->firstOrFail();

            $masyarakat=User::updateOrCreate(
                ['email'=>'masyarakat.test@gmail.com'],
                [
                    'name'=>'Masyarakat Test',
                    'password'=>'password123',
                    'role'=>UserRole::MASYARAKAT,
                    'nik'=>'7371000000000001',
                    'phone'=>'081234567890',
                    'address'=>'Kecamatan Panakkukang',
                    'section_id'=>null,
                    'email_verified_at'=>now(),
                    'is_active'=>true,
                ],
            );

            $requirement=ServiceRequirement::firstOrCreate(
                [
                    'service_id'=>$service->id,
                    'name'=>'Fotokopi KTP',
                ],
                [
                    'description'=>'Dokumen KTP untuk pengujian sistem',
                    'allowed_extensions'=>['txt','pdf','jpg','jpeg','png'],
                    'max_size_kb'=>2048,
                    'is_required'=>true,
                    'sort_order'=>1,
                ],
            );

            $application=ServiceApplication::updateOrCreate(
                ['registration_number'=>'TEST-ADMIN-SEKSI-001'],
                [
                    'user_id'=>$masyarakat->id,
                    'service_id'=>$service->id,
                    'assigned_admin_id'=>null,
                    'status'=>ApplicationStatus::SUBMITTED,
                    'applicant_data'=>[
                        'nama_lengkap'=>$masyarakat->name,
                        'nik'=>$masyarakat->nik,
                        'keperluan'=>'Pengujian permohonan pembuatan KTP',
                    ],
                    'applicant_notes'=>'Permohonan pengujian sistem Admin Seksi',
                    'internal_notes'=>null,
                    'submitted_at'=>now(),
                    'verified_at'=>null,
                    'completed_at'=>null,
                    'rejected_at'=>null,
                ],
            );

            $path='application-documents/'.$application->id.'/contoh-dokumen.txt';

            Storage::disk('public')->put(
                $path,
                "DOKUMEN PENGUJIAN\nNomor Registrasi: {$application->registration_number}\nNama: {$masyarakat->name}\nLayanan: {$service->name}",
            );

            ApplicationDocument::updateOrCreate(
                [
                    'application_id'=>$application->id,
                    'requirement_id'=>$requirement->id,
                ],
                [
                    'uploaded_by'=>$masyarakat->id,
                    'original_name'=>'contoh-dokumen.txt',
                    'path'=>$path,
                    'disk'=>'public',
                    'mime_type'=>'text/plain',
                    'size_bytes'=>Storage::disk('public')->size($path),
                    'verification_status'=>DocumentVerificationStatus::PENDING,
                    'verification_notes'=>null,
                    'verified_by'=>null,
                    'verified_at'=>null,
                ],
            );

            ApplicationStatusHistory::updateOrCreate(
                [
                    'application_id'=>$application->id,
                    'to_status'=>ApplicationStatus::SUBMITTED,
                ],
                [
                    'changed_by'=>$masyarakat->id,
                    'from_status'=>null,
                    'notes'=>'Permohonan diajukan oleh masyarakat.',
                    'metadata'=>[
                        'source'=>'admin_seksi_test_seeder',
                        'service_id'=>$service->id,
                        'section_id'=>$service->section_id,
                    ],
                    'created_at'=>now(),
                ],
            );
        });
    }
}