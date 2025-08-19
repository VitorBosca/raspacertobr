<?php

namespace App\Filament\Admin\Resources\UserResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOverview extends BaseWidget
{
    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        // Contadores básicos
        $totalUsers = User::count();
        $totalActiveUsers = User::where('status', 1)->count();
        $totalBannedUsers = User::where('banned', 1)->count();
        $totalInfluencers = User::where('is_demo_agent', 1)->count();
        
        // Novos usuários por período
        $totalMonthUsers = User::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
        $totalWeekUsers = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $totalTodayUsers = User::whereDate('created_at', Carbon::today())->count();
        
        // Usuários verificados
        $totalVerifiedUsers = User::whereNotNull('email_verified_at')->count();
        
        // Calcular porcentagens
        $activePercentage = $totalUsers > 0 ? round(($totalActiveUsers / $totalUsers) * 100, 1) : 0;
        $verifiedPercentage = $totalUsers > 0 ? round(($totalVerifiedUsers / $totalUsers) * 100, 1) : 0;

        return [
            Stat::make('👥 Total de Usuários', number_format($totalUsers))
                ->description("$activePercentage% ativos")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
                
            Stat::make('✅ Usuários Ativos', number_format($totalActiveUsers))
                ->description('Podem acessar a plataforma')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('📧 E-mails Verificados', number_format($totalVerifiedUsers))
                ->description("$verifiedPercentage% verificados")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
                
            Stat::make('🌟 Influenciadores', number_format($totalInfluencers))
                ->description('Usuários com privilégios especiais')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
                
            Stat::make('🚫 Usuários Banidos', number_format($totalBannedUsers))
                ->description('Permanentemente suspensos')
                ->descriptionIcon('heroicon-m-no-symbol')
                ->color('danger'),
                
            Stat::make('📅 Novos Hoje', number_format($totalTodayUsers))
                ->description('Cadastrados hoje')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
                
            Stat::make('📈 Novos esta Semana', number_format($totalWeekUsers))
                ->description('Últimos 7 dias')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('📊 Novos este Mês', number_format($totalMonthUsers))
                ->description('Mês atual')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}
