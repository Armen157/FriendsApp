<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class statuses extends Model
{
    use HasFactory;

    protected $primaryKey = 'status_id';

    protected $fillable = [
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function friendships()
    {
        return $this->hasMany('App\models\friendship','status_id');
    }
}
