<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifikasiMasyarakat extends Notification
{
    use Queueable;

    public function __construct(
        public string $judul,
        public string $pesan,
        public string $jenis = 'informasi',
        public ?string $url = null,
        public string $ikon = 'informasi',
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'jenis' => $this->jenis,
            'url' => $this->url,
            'ikon' => $this->ikon,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}