<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Helpers\Core as Helper;

class Order extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';
    protected $appends = ['dateHumanReadable', 'createdAt'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'game_items' => 'array',
        'winning_prize' => 'array',
        'has_won' => 'boolean',
        'amount' => 'decimal:2',
        'prize_amount' => 'decimal:2'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'transaction_id',
        'game',
        'game_uuid',
        'type',
        'type_money',
        'amount',
        'providers',
        'refunded',
        'round_id',
        'status',
        'hash',
        'game_items',
        'has_won',
        'winning_prize',
        'prize_amount'
    ];

    /**
     * Get the user's first name.
     */
//    protected function type(): Attribute
//    {
//        return Attribute::make(
//            get: fn (?string $value) => Helper::getTypeOrder($value),
//        );
//    }

    /**
     * Get the user's first name.
     */
    // protected function typeMoney(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (?string $value) => Helper::getTypeTransactionOrder($value),
    //     );
    // }

    /**
     * Get the user's first name.
     */
    protected function providers(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => Helper::getDistribution()[$value] ?? $value,
        );
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * @return mixed
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at']);
    }

    /**
     * @return mixed
     */
    public function getDateHumanReadableAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Verificar se é uma jogada de raspadinha
     *
     * @return bool
     */
    public function isScratchCard()
    {
        return $this->type === 'scratch_card';
    }

    /**
     * Obter resultado da jogada
     *
     * @return string
     */
    public function getGameResult()
    {
        if (!$this->isScratchCard()) {
            return 'N/A';
        }

        return $this->has_won ? 'Ganhou' : 'Perdeu';
    }

    /**
     * Obter descrição do prêmio
     *
     * @return string
     */
    public function getPrizeDescription()
    {
        if (!$this->isScratchCard() || !$this->has_won || !$this->winning_prize) {
            return 'Nenhum prêmio';
        }

        return $this->winning_prize['name'] ?? 'Prêmio desconhecido';
    }

    /**
     * Escopo para buscar apenas jogadas de raspadinha
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScratchCard($query)
    {
        return $query->where('type', 'scratch_card');
    }

    /**
     * Escopo para buscar jogadas vencedoras
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWinning($query)
    {
        return $query->where('has_won', true);
    }

    /**
     * Escopo para buscar jogadas por usuário
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
