<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

// Lietotāju tabulas modelis autentifikācijai un lomām.
class User extends Authenticatable
{
    // Lietotāja modelis ar notifikācijām.
    use HasFactory, Notifiable;

    // Masveidā aizpildāmie lauki.
    protected $fillable = [
        'name',
        'email',
        'password',
        'AmataID',
    ];

    // Slēptie lauki serializācijā.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Lauku tipa pārveides.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Saistība ar amatu.
    public function amats(): BelongsTo
    {
        return $this->belongsTo(Amati::class, 'AmataID', 'AmataID');
    }

    // Pārbauda admin lomu.
    public function isAdmin(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'admins';
    }

    // Pārbauda darbinieka lomu.
    public function isDarbinieks(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'darbinieks';
    }

    // Pārbauda klienta lomu.
    public function isKlients(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'klients';
    }

    // Saistība ar klienta ierakstu (caur e-pastu).
    public function klienti(): HasOne
    {
        return $this->hasOne(Klienti::class, 'Epasts', 'email');
    }
}