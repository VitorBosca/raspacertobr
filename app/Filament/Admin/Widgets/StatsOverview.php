<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        $today = Carbon::now();
        $sevenDaysAgo = $today->copy()->subDays(7);
        $thirtyDaysAgo = $today->copy()->subDays(30);

        // Dados atuais
        $totalUsers = User::count();
        $totalApostas = Order::whereIn('type', ['bet', 'loss'])->sum('amount');
        $totalWins = Order::where('type', 'win')->sum('amount');
        $totalGames = \App\Models\Game::count();

        // Dados dos últimos 7 dias para comparação
        $usersLast7Days = User::where('created_at', '>=', $sevenDaysAgo)->count();
        $apostasLast7Days = Order::whereIn('type', ['bet', 'loss'])->where('created_at', '>=', $sevenDaysAgo)->sum('amount');
        $winsLast7Days = Order::where('type', 'win')->where('created_at', '>=', $sevenDaysAgo)->sum('amount');

        // Gráficos dos últimos 30 dias
        $userChart = [];
        $revenueChart = [];
        $winsChart = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dayStart = $date->startOfDay();
            $dayEnd = $date->endOfDay();
            
            $userChart[] = User::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $revenueChart[] = Order::whereIn('type', ['bet', 'loss'])->whereBetween('created_at', [$dayStart, $dayEnd])->sum('amount');
            $winsChart[] = Order::where('type', 'win')->whereBetween('created_at', [$dayStart, $dayEnd])->sum('amount');
        }

        $profit = $totalApostas - $totalWins;
        $profitLast7Days = $apostasLast7Days - $winsLast7Days;

        return [
            Stat::make('👥 Total de Usuários', number_format($totalUsers, 0, ',', '.'))
                ->description($usersLast7Days > 0 ? "+{$usersLast7Days} nos últimos 7 dias" : 'Sem novos usuários')
                ->descriptionIcon($usersLast7Days > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus')
                ->color($usersLast7Days > 0 ? 'success' : 'gray')
                ->chart($userChart),
                
            Stat::make('💰 Receita Total', \Helper::amountFormatDecimal($totalApostas))
                ->description(\Helper::amountFormatDecimal($apostasLast7Days) . ' últimos 7 dias')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart($revenueChart),
                
            Stat::make('🎯 Lucro Líquido', \Helper::amountFormatDecimal($profit))
                ->description(\Helper::amountFormatDecimal($profitLast7Days) . ' últimos 7 dias')
                ->descriptionIcon($profit > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($profit > 0 ? 'success' : 'danger')
                ->chart(array_map(function($revenue, $win) { return $revenue - $win; }, $revenueChart, $winsChart)),
                
            Stat::make('🎮 Jogos Ativos', number_format($totalGames, 0, ',', '.'))
                ->description('Total de jogos cadastrados')
                ->descriptionIcon('heroicon-m-puzzle-piece')
                ->color('warning'),
                
            Stat::make('💸 Pagamentos', \Helper::amountFormatDecimal($totalWins))
                ->description(\Helper::amountFormatDecimal($winsLast7Days) . ' últimos 7 dias')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart($winsChart),
        ];
    }

    /**
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
