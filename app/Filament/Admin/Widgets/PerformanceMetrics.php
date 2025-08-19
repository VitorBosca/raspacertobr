<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerformanceMetrics extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();
        
        // Dados de hoje
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        
        // Dados de ontem para comparação
        $yesterdayStart = $now->copy()->subDay()->startOfDay();
        $yesterdayEnd = $now->copy()->subDay()->endOfDay();
        
        // Usuários online (baseado em updated_at dos últimos 30 minutos como aproximação)
        $usersOnline = User::where('updated_at', '>=', $now->subMinutes(30))->count();
        
        // Apostas de hoje vs ontem
        $todayBets = Order::whereIn('type', ['bet', 'loss'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();
            
        $yesterdayBets = Order::whereIn('type', ['bet', 'loss'])
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->count();
        
        // Receita de hoje vs ontem
        $todayRevenue = Order::whereIn('type', ['bet', 'loss'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
            
        $yesterdayRevenue = Order::whereIn('type', ['bet', 'loss'])
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('amount');
        
        // Taxa de conversão (aproximada)
        $totalUsers = User::count();
        $activeUsers = User::whereHas('orders')->count();
        $conversionRate = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
        
        // Cálculos de tendência
        $betsTrend = $yesterdayBets > 0 ? (($todayBets - $yesterdayBets) / $yesterdayBets) * 100 : 0;
        $revenueTrend = $yesterdayRevenue > 0 ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 0;

        return [
            Stat::make('🟢 Usuários Ativos', $usersOnline)
                ->description('Atividade nos últimos 30min')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),
                
            Stat::make('🎯 Apostas Hoje', number_format($todayBets, 0, ',', '.'))
                ->description(
                    $betsTrend >= 0 
                        ? "+{$betsTrend}% vs ontem" 
                        : "{$betsTrend}% vs ontem"
                )
                ->descriptionIcon(
                    $betsTrend >= 0 
                        ? 'heroicon-m-arrow-trending-up' 
                        : 'heroicon-m-arrow-trending-down'
                )
                ->color($betsTrend >= 0 ? 'success' : 'danger'),
                
            Stat::make('💰 Receita Hoje', 'R$ ' . number_format($todayRevenue, 2, ',', '.'))
                ->description(
                    $revenueTrend >= 0 
                        ? "+{$revenueTrend}% vs ontem" 
                        : "{$revenueTrend}% vs ontem"
                )
                ->descriptionIcon(
                    $revenueTrend >= 0 
                        ? 'heroicon-m-arrow-trending-up' 
                        : 'heroicon-m-arrow-trending-down'
                )
                ->color($revenueTrend >= 0 ? 'success' : 'danger'),
                
            Stat::make('📊 Taxa Conversão', number_format($conversionRate, 1) . '%')
                ->description('Usuários que apostaram')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($conversionRate > 50 ? 'success' : ($conversionRate > 25 ? 'warning' : 'danger')),
        ];
    }
}
