<?php

namespace App\Filament\Admin\Resources\UserResource\Widgets;

use App\Models\Deposit;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepositsIndicationsOverview extends BaseWidget
{
    public User $record;

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        // Obtém todos os IDs das indicações do usuário
        $totalIndicationsId = User::where('inviter', $this->record->id)->pluck('id');

        // Calcula o total de depósitos
        $totalDeposits = Deposit::whereIn('user_id', $totalIndicationsId)->sum('amount');

        // Calcula o total de depósitos confirmados
        $totalDepositsConfirmed = Deposit::whereIn('user_id', $totalIndicationsId)
            ->where('status', 1)
            ->sum('amount');

        // Calcula o total de depósitos não confirmados
        $totalDepositsUnConfirmed = Deposit::whereIn('user_id', $totalIndicationsId)
            ->where('status', 0)
            ->sum('amount');

        // Retorna as estatísticas
        return [
            Stat::make('Depósitos Indicações', \Helper::amountFormatDecimal($totalDeposits)),
            Stat::make('Depósitos Indicações Confirmado', \Helper::amountFormatDecimal($totalDepositsConfirmed)),
            Stat::make('Depósitos Indicações Pendente', \Helper::amountFormatDecimal($totalDepositsUnConfirmed)),
        ];
    }
}
