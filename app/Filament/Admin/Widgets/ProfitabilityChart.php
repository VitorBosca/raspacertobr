<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ProfitabilityChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '📊 Análise de Lucratividade (7 dias)';
    protected static ?int $sort = 8;
    protected static ?string $maxHeight = '450px';
    protected static ?string $pollingInterval = '120s';
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? Carbon::now()->subDays(7);
        $endDate = $this->filters['endDate'] ?? Carbon::now();

        if (!$startDate instanceof Carbon) {
            $startDate = Carbon::parse($startDate);
        }
        if (!$endDate instanceof Carbon) {
            $endDate = Carbon::parse($endDate);
        }

        $dates = [];
        $revenue = [];
        $costs = [];
        $profit = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $dates[] = $date->format('d/m');
            
            // Receita (apostas)
            $dailyRevenue = Order::whereIn('type', ['bet', 'loss'])
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');
            $revenue[] = $dailyRevenue;
            
            // Custos (pagamentos + saques)
            $dailyPayouts = Order::where('type', 'win')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');
            $dailyWithdrawals = Withdrawal::where('status', 'paid')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');
            $dailyCosts = $dailyPayouts + $dailyWithdrawals;
            $costs[] = $dailyCosts;
            
            // Lucro líquido
            $profit[] = $dailyRevenue - $dailyCosts;
        }

        return [
            'datasets' => [
                [
                    'label' => '💰 Receita',
                    'data' => $revenue,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(34, 197, 94, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                ],
                [
                    'label' => '💸 Custos',
                    'data' => $costs,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(239, 68, 68, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                ],
                [
                    'label' => '📈 Lucro Líquido',
                    'data' => $profit,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.3)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 4,
                    'fill' => true,
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 3,
                    'pointRadius' => 8,
                    'pointHoverRadius' => 12,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#ffffff',
                    'bodyColor' => '#ffffff',
                    'borderColor' => 'rgba(255, 255, 255, 0.1)',
                    'borderWidth' => 1,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { return "R$ " + value.toLocaleString("pt-BR", {minimumFractionDigits: 2}); }',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'elements' => [
                'point' => [
                    'hoverRadius' => 10,
                ],
            ],
        ];
    }
}
