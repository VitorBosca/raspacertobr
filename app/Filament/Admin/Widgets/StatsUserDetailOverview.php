<?php

namespace App\Filament\Admin\Widgets;

use App\Helpers\Core as Helper;
use App\Models\AffiliateHistory;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsUserDetailOverview extends BaseWidget
{

    public User $record;

    public function mount($record)
    {
       $this->record = $record;
    }

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        // Calcular estatísticas
        $totalGanhos = Order::where('user_id', $this->record->id)->where('type', 'win')->sum('amount');
        $totalPerdas = Order::where('user_id', $this->record->id)->where('type', 'loss')->sum('amount');
        $totalAfiliados = AffiliateHistory::where('inviter', $this->record->id)->sum('commission_paid');
        $totalDepositos = Order::where('user_id', $this->record->id)->where('type', 'deposit')->sum('amount');
        $totalSaques = Order::where('user_id', $this->record->id)->where('type', 'withdraw')->sum('amount');
        $indicacoes = User::where('inviter', $this->record->id)->count();
        
        // Calcular saldo atual
        $saldoAtual = $this->record->wallet->total_balance ?? 0;
        $pontosVip = $this->record->wallet->vip_points ?? 0;

        return [
            Stat::make('💰 Saldo Atual', 'R$ ' . number_format($saldoAtual, 2, ',', '.'))
                ->description('Saldo disponível na carteira')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($saldoAtual > 0 ? 'success' : 'gray'),
                
            Stat::make('📈 Total de Ganhos', 'R$ ' . number_format($totalGanhos, 2, ',', '.'))
                ->description('Lucros obtidos na plataforma')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                
            Stat::make('📉 Total de Perdas', 'R$ ' . number_format($totalPerdas, 2, ',', '.'))
                ->description('Perdas na plataforma')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
                
            Stat::make('💎 Pontos VIP', number_format($pontosVip))
                ->description('Pontos acumulados no sistema VIP')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
                
            Stat::make('💳 Total Depositado', 'R$ ' . number_format($totalDepositos, 2, ',', '.'))
                ->description('Valor total depositado')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('info'),
                
            Stat::make('🏧 Total Sacado', 'R$ ' . number_format($totalSaques, 2, ',', '.'))
                ->description('Valor total retirado')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->color('primary'),
                
            Stat::make('🤝 Indicações', number_format($indicacoes))
                ->description('Usuários indicados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            Stat::make('💸 Ganhos Afiliado', 'R$ ' . number_format($totalAfiliados, 2, ',', '.'))
                ->description('Comissões recebidas como afiliado')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
