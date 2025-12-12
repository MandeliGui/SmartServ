<?php

namespace App\Models\Adm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPlano extends Model
{
    protected $table = 'user_planos';

    protected $fillable = [
        'user_id',
        'plano_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plano()
    {
        return $this->belongsTo(PlanosModel::class, 'plano_id');
    }
}
