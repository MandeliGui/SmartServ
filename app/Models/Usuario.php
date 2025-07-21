<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;

class Usuario extends Authenticatable
{

    protected $table = 'tb_usuarios';

    protected $primaryKey = 'id';

    protected $fillable = ['nome', 'email', 'password', 'removido', 'user_id'];

    public $timestamps = true;

    protected $hidden = ['password'];

    protected $casts = [
        'removido' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Override the method to set the remember token.
     */
    public function setRememberToken($value): void
    {
        // Não faz nada
    }

    /**
     * Override the method to get the remember token.
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Override the method to get the remember token name.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
