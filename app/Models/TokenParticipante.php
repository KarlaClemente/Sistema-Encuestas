<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TokenParticipante extends Model
{
    protected $table = 'token_participante';

    protected $primaryKey = 'id_token_participante';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'id_participante',
        'token',
    ];

    protected $casts = [
        'id_participante' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function (TokenParticipante $tokenParticipante) {
            do {
                $token = Str::uuid();
            } while (TokenParticipante::where('token', $token)->exists());
            $tokenParticipante->token = $token;
        });
    }

    public function getCompletadoAttribute(): bool
    {
        return $this->tokensEncuesta->isNotEmpty() && $this->tokensEncuesta->every(fn ($tokensEncuesta) => $tokensEncuesta->completado);
    }

    public function correos(): HasMany
    {
        return $this->hasMany(Correo::class, 'id_token_participante', 'id_token_participante');
    }

    public function participante(): BelongsTo
    {
        return $this->belongsTo(Participante::class, 'id_participante', 'id_participante');
    }

    public function tokensEncuesta(): HasMany
    {
        return $this->hasMany(TokenEncuesta::class, 'id_token_participante', 'id_token_participante');
    }
}
