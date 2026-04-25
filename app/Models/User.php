<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * User modelis - Pārstāv sistēmas lietotāju ierakstus
 * 
 * Lietotājs ir persona, kas pielogojās sistēmā
 * Tiek izmantots autentifikācijai (login/logout) un tiesību noteikšanai (roles)
 * Katram lietotājam ir loma (amats), kas nosaka, kādu funkcionalitāti viņš var izmantot
 * 
 * Datu bāzes tabula: users (Laravel noklusējums)
 * Primārā atslēga: id
 */
class User extends Authenticatable
{
    // Iekļaujam Factory un Notifiable traits
    use HasFactory, Notifiable;

    /**
     * Lauki, kurus drīkst aizpildīt masveidā pie create/update
     * 
     * @var array
     */
    protected $fillable = [
        'name',           // Lietotājvārds
        'email',          // E-pasta adrese
        'password',       // Parole (tiks hashota)
        'AmataID',        // Amata ID (loma sistēmā)
    ];

    /**
     * Lauki, kas tiks slēpti, serializējot modeli
     * Šie lauki netiks iekļauti JSON atbildēs API
     * 
     * @var array
     */
    protected $hidden = [
        'password',       // Paslēpt paroli
        'remember_token', // Paslēpt "atcerēties mani" tokenu
    ];

    /**
     * Lauku tipa pārveides - definē, kā lauki tiek konvertēti
     * 
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Konvertē uz datetime objektu
            'password' => 'hashed',             // Hasho paroli
        ];
    }

    /**
     * Saistība ar amatu - Lietotājs "pieder" vienam amatam
     * Amats nosaka lietotāja lomu sistēmā (admins, darbinieks, klients)
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function amats(): BelongsTo
    {
        return $this->belongsTo(Amati::class, 'AmataID', 'AmataID');
    }

    /**
     * Pārbauda vai lietotājs ir administrators
     * Admin var piekļūt administratora funkcionalitātei
     * 
     * @return bool - true, ja lietotājs ir admins
     */
    public function isAdmin(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'admins';
    }

    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isDarbinieks();
    }

    /**
     * Pārbauda vai lietotājs ir darbinieks
     * Darbinieks ir sistēmas operators ar ierobežotām tiesībām
     * 
     * @return bool - true, ja lietotājs ir darbinieks
     */
    public function isDarbinieks(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'darbinieks';
    }

    /**
     * Pārbauda vai lietotājs ir klients
     * Klients var skatīt tikai savu nomas informāciju
     * 
     * @return bool - true, ja lietotājs ir klients
     */
    public function isKlients(): bool
    {
        return $this->amats && strtolower($this->amats->Nosaukums) === 'klients';
    }

    /**
     * Saistība ar klienta ierakstu - Lietotājs "saistīts" ar vienu klienta profilu
     * Saistība tiek veidota caur e-pasta adresi (user email = klienti.Epasts)
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function klienti(): HasOne
    {
        return $this->hasOne(Klienti::class, 'Epasts', 'email');
    }
}