<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    
    protected $fillable = [
        'menu_id',
        'title',
        'short_description',
        'link_url',
        'image_path',
        'order_no'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}