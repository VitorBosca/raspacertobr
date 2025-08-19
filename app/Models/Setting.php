<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'software_name',
        'software_description',

        /// logos e background
        'software_favicon',
        'software_logo_white',
        'software_logo_black',
        'software_background',

        'currency_code',
        'decimal_format',
        'currency_position',
        'prefix',
        'storage',
        'min_deposit',
        'max_deposit',
        'min_withdrawal',
        'max_withdrawal',

        /// vip
        'bonus_vip',
        'activate_vip_bonus',

        // Percent
        'ngr_percent',
        'revshare_percentage',
        'revshare_reverse',
        'cpa_value',
        'cpa_baseline',

        /// soccer
        'soccer_percentage',
        'turn_on_football',

        'initial_bonus',

        'digitopay_is_enable',
        'sharkpay_is_enable',
        'pradapay_is_enable',
        'bspay_is_enable',

        /// withdrawal limit
        'withdrawal_limit',
        'withdrawal_period',

        'disable_spin',

        /// sub afiliado
        'perc_sub_lv1',
        'perc_sub_lv2',
        'perc_sub_lv3',

        /// campos do rollover
        'rollover',
        'rollover_deposit',
        'disable_rollover',
        'rollover_protection',

        'premios_padrao'
    ];

    protected $hidden = array('updated_at');

    public static function getDefaultPrizes()
    {
        try {
            $setting = self::first();
            
            if (!$setting || !$setting->premios_padrao) {
                return [];
            }
            
            // Se já é um array, retorna diretamente
            if (is_array($setting->premios_padrao)) {
                return $setting->premios_padrao;
            }
            
            // Se é string, tenta decodificar JSON
            if (is_string($setting->premios_padrao)) {
                $prizes = json_decode($setting->premios_padrao, true);
                
                // Verifica se a decodificação foi bem-sucedida
                if (json_last_error() === JSON_ERROR_NONE && is_array($prizes)) {
                    return $prizes;
                }
            }
            
            return [];
            
        } catch (\Exception $e) {
            // Em caso de erro, retorna array vazio
            Log::error('Erro ao buscar prêmios padrão: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Método auxiliar para obter configurações específicas
     */
    public static function get($key, $default = null)
    {
        $setting = self::first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->getAttribute($key) ?? $default;
    }
}
