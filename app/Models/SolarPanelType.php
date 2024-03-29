<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarPanelType extends Model
{
    use HasFactory;

    public function charge()
    {
    	return $this->hasMany(Charge::class);
    }
}
