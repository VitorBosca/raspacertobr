<?php

namespace App\Filament\Admin\Resources\UserResource\Widgets;

use App\Models\Deposit;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepositsOverview extends BaseWidget
{
    public User $record;

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        // Estatísticas de depósitos
        $totalDeposits = Deposit::where('user_id', $this->record->id)->sum('amount');
        $totalDepositsConfirmed = Deposit::where('user_id', $this->record->id)->where('status', 1)->sum('amount');
        $totalDepositsUnConfirmed = Deposit::where('user_id', $this->record->id)->where('status', 0)->sum('amount');
        $totalDepositsCanceled = Deposit::where('user_id', $this->record->id)->where('status', 2)->sum('amount');
        
        // Contagem de transações
        $countDeposits = Deposit::where('user_id', $this->record->id)->count();
        $countConfirmed = Deposit::where('user_id', $this->record->id)->where('status', 1)->count();
        $countPending = Deposit::where('user_id', $this->record->id)->where('status', 0)->count();
        
        // Último depósito
        $lastDeposit = Deposit::where('user_id', $this->record->id)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->first();

        return [
            Stat::make('💰 Total Depositado', 'R$ ' . number_format($totalDeposits, 2, ',', '.'))
                ->description("$countDeposits transações realizadas")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
                
            Stat::make('✅ Depósitos Confirmados', 'R$ ' . number_format($totalDepositsConfirmed, 2, ',', '.'))
                ->description("$countConfirmed depósitos aprovados")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('⏳ Depósitos Pendentes', 'R$ ' . number_format($totalDepositsUnConfirmed, 2, ',', '.'))
                ->description("$countPending aguardando aprovação")
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('❌ Depósitos Cancelados', 'R$ ' . number_format($totalDepositsCanceled, 2, ',', '.'))
                ->description('Transações rejeitadas')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
                
            Stat::make('📅 Último Depósito', $lastDeposit ? 'R$ ' . number_format($lastDeposit->amount, 2, ',', '.') : 'Nenhum')
                ->description($lastDeposit ? $lastDeposit->created_at->diffForHumans() : 'Nunca depositou')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($lastDeposit ? 'info' : 'gray'),
        ];
    }
}
