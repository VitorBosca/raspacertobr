<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Deposit;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class FinancialFlowChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '� Fluxo Financeiro (Depósitos vs Saques)';
    protected static ?int $sort = 6;
    protected static ?string $maxHeight = '400px';
    protected static ?string $pollingInterval = '180s';

    protected int | string | array $columnSpan = [
        'sm' => 2,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
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

        // Calcular totais do período
        $totalDeposits = Deposit::where('status', 'paid')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('amount');
            
        $totalWithdrawals = Withdrawal::where('status', 'paid')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'data' => [$totalDeposits, $totalWithdrawals],
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => [
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(239, 68, 68, 0.9)',
                    ],
                ],
            ],
            'labels' => ['💰 Depósitos', '💸 Saques'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { 
                            var label = context.label || "";
                            var value = context.parsed;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = ((value / total) * 100).toFixed(1);
                            return label + ": R$ " + value.toLocaleString("pt-BR", {minimumFractionDigits: 2}) + " (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '50%',
        ];
    }
}
