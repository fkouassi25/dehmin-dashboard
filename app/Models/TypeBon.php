<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeBon extends Model
{
    use HasFactory;

    protected $table = 'type_bon';
    public $timestamps = false;

    public function categorie()
    {
        return $this->belongsTo('App\Categorie', 'id_cat');
    }
}
