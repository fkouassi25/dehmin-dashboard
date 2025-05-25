<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commande';
    public $timestamps = false;

    public function donateur()
    {
        return $this->belongsTo(Donateur::class, 'id_donateur');
    }
}
