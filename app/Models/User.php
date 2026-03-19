<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; // Pievieno fabriku un paziņojumu funkcijas

    /**
     * Atribūti, kurus var masveidā pievienot (mass assignable)
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',      // Lietotāja vārds
        'email',     // Lietotāja e-pasts
        'password',  // Lietotāja parole
        'AmataID',   // Amata ID
    ];

    /**
     * Atribūti, kas jāslēpj serializējot (piem., JSON)
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',       // Slēpt paroli
        'remember_token', // Slēpt atcerēšanās tokenu
    ];

    /**
     * Atribūti, kurus jācastē uz noteiktu datu tipu
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // E-pasta apstiprinājuma laiks kā datetime
            'password' => 'hashed',            // Parole automātiski hashēta
        ];
    }

    // Saistība ar amata tabulu
    public function amats(): BelongsTo
    {
        return $this->belongsTo(Amati::class, 'AmataID', 'AmataID');
    }

    // Pārbauda vai lietotājs ir administrators
    public function isAdmin(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'admins';
    }

    // Pārbauda vai lietotājs ir darbinieks
    public function isDarbinieks(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'darbinieks';
    }
}