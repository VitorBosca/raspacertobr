<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '💰 Receita vs Pagamentos (30 dias)';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '500px';
    protected static ?string $pollingInterval = '60s';
    
    // Ocupa mais espaço horizontal - APENAS ISTO FOI ADICIONADO
    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? Carbon::now()->subDays(30);
        $endDate = $this->filters['endDate'] ?? Carbon::now();

        if (!$startDate instanceof Carbon) {
            $startDate = Carbon::parse($startDate);
        }
        if (!$endDate instanceof Carbon) {
            $endDate = Carbon::parse($endDate);
        }

        // Gera dados dos últimos 30 dias
        $dates = [];
        $revenue = [];
        $payouts = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $dates[] = $date->format('d/m');
            
            // Receita (apostas perdidas)
            $dailyRevenue = Order::whereIn('type', ['bet', 'loss'])
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');
            $revenue[] = $dailyRevenue;
            
            // Pagamentos (ganhos)
            $dailyPayouts = Order::where('type', 'win')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('amount');
            $payouts[] = $dailyPayouts;
        }

        return [
            'datasets' => [
                [
                    'label' => '💰 Receita Total',
                    'data' => $revenue,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => '💸 Pagamentos',
                    'data' => $payouts,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
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
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "R$ " + value.toLocaleString("pt-BR", {minimumFractionDigits: 2}); }',
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
