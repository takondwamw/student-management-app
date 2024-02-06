<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Guardian extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'contact_number',
        'relation_type', 
    ];

    public function students():HasMany
    {
        $this->belongsToMany(Student::class);
    }
}
 