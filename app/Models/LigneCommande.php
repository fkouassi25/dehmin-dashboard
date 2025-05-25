<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    protected $table = 'ligne_cmd';
    public $timestamps = false;

    public function typebon()
    {
        return $this->belongsTo('App\TypeBon', 'id_type_bon');
    }
}
