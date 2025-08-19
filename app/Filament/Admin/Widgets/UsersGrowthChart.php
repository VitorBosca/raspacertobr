<?php
namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class UsersGrowthChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '👥 Distribuição de Usuários';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '400px';
    protected static ?string $pollingInterval = '120s';

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
        $totalUsers = User::where('created_at', '<=', $endDate)->count();
        $newUsers = User::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->count();
        $existingUsers = $totalUsers - $newUsers;

        return [
            'datasets' => [
                [
                    'data' => [$newUsers, $existingUsers],
                    'backgroundColor' => [
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(168, 85, 247, 1)',
                        'rgba(59, 130, 246, 1)',
                    ],
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => [
                        'rgba(168, 85, 247, 0.9)',
                        'rgba(59, 130, 246, 0.9)',
                    ],
                ],
            ],
            'labels' => ['✨ Novos Usuários', '👥 Usuários Existentes'],
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
                            return label + ": " + value.toLocaleString("pt-BR") + " (" + percentage + "%)";
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
