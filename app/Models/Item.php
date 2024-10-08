<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Menentukan kolom 'kode' sebagai primary key
    protected $primaryKey = 'kode';

    // Primary key tidak bertipe auto-increment integer
    public $incrementing = false;

    // Primary key tipe string
    protected $keyType = 'string';

    // Kolom yang bisa diisi
    protected $fillable = [
        'kode',
        'gambar',
        'ket',
    ];
    public $timestamps = true;
}