<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'order_no', 'type', 'url', 'icon_path'];

    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('order_no');
    }
}