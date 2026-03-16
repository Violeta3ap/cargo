<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klienti extends Model
{
    protected $table = 'klienti'; // Eloquent modelis saistīts ar datu bāzes tabulu 'klienti'
    protected $primaryKey = 'KlientaID'; // Tabulas primārā atslēga
    protected $keyType = 'int'; // Primārās atslēgas tips ir vesels skaitlis
    public $incrementing = true; // Primārā atslēga automātiski palielinās (auto-increment)
    public $timestamps = false; // Nesaglabāt laika zīmogus created_at un updated_at
}