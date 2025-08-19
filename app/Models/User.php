<?php

namespace App\Models;

use Attribute;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements FilamentUser, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(auth()->check() && auth()->user()->hasRole('admin')) {
                if(isset($model->id)) {
                    \Helper::CreateReport('Criou', 'Usuário criado pelo admin: '. auth()->user()->name . ' o id é:' .$model->id);
                }

            }
        });

        static::updated(function ($model) {

        });

        static::deleted(function ($model) {
            if(auth()->check() && auth()->user()->hasRole('admin')) {
                \Helper::CreateReport('Apagou', 'O Admin '. auth()->user()->name . ' apagou um usuário');
            }

            self::deleteAll($model);
        });
    }

    /**
     * @param $user
     * @return void
     */
    public static function deleteAll($user)
    {
        $wallet = Wallet::find($user->id);
        if(!empty($wallet)) {
            $wallet->delete();
        }

        $affiliateHistory = AffiliateHistory::where('user_id', $user->id)->get();
        foreach($affiliateHistory as $affh) {
            $affh->delete();
        }

        $affiliateWithdraw = AffiliateWithdraw::where('user_id', $user->id)->get();
        foreach($affiliateWithdraw as $affw) {
            $affw->delete();
        }

        $deposits = Deposit::where('user_id', $user->id)->get();
        foreach($deposits as $dep) {
            $dep->delete();
        }

        $likes = Like::where('user_id', $user->id)->get();
        foreach($likes as $lk) {
            $lk->delete();
        }

        $transactions = Transaction::where('user_id', $user->id)->get();
        foreach($transactions as $trans) {
            $trans->delete();
        }

        $withdrawals = Withdrawal::where('user_id', $user->id)->get();
        foreach($withdrawals as $wts) {
            $wts->delete();
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'avatar',
        'name',
        'last_name',
        'cpf',
        'phone',
        'email',
        'password',
        'logged_in',
        'banned',
        'inviter',
        'inviter_code',
        'affiliate_revenue_share',
        'affiliate_revenue_share_fake',
        'affiliate_cpa',
        'affiliate_baseline',
        'is_demo_agent',
        'is_admin',
        'language',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['dateHumanReadable', 'createdAt', 'totalLikes', 'totalVipPoints', 'vipLevel'];

    /**
     * @return mixed
     */
    public function getTotalVipPointsAttribute()
    {
        $totalVipPoints = VipUser::where('user_id', $this->id)
            ->where('status', 1)
            ->sum('points');

        return $totalVipPoints;
    }

    /**
     * @return int
     */
    public function getVipLevelAttribute()
    {
        $vip = VipUser::where('user_id', $this->id)->where('status', 1)->latest()->first();
        return $vip->level ?? 0;
    }

    /**
     * Orders
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Favorites
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(GameFavorite::class);
    }

    /**
     * Favorites
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Interact with the user's first name.
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    /**
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter', 'id');
    }

    /**
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class)->where('active', 1);
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    /**
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'afiliado']);
    }

    /**
     * @return int
     */
    public function getTotalLikesAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * @return mixed
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }

    /**
     * @return mixed
     */
    public function getDateHumanReadableAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
