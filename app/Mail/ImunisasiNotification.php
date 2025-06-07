<?php

// app/Mail/ImunisasiNotification.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImunisasiNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $namaBalita;
    public $jenisVaksin;
    public $tanggalImunisasi;

    /**
     * Create a new message instance.
     */
    public function __construct($namaBalita, $jenisVaksin, $tanggalImunisasi)
    {
        $this->namaBalita = $namaBalita;
        $this->jenisVaksin = $jenisVaksin;
        $this->tanggalImunisasi = $tanggalImunisasi;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat Jadwal Imunisasi Balita Anda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Pastikan ini merujuk ke file Blade yang baru Anda buat
            view: 'emails.imunisasi-notification',
            with: [
                'namaBalita' => $this->namaBalita,
                'jenisVaksin' => $this->jenisVaksin,
                'tanggalImunisasi' => $this->tanggalImunisasi,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}