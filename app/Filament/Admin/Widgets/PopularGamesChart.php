<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Game;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularGamesChart extends ChartWidget
{
    protected static ?string $heading = '� Jogos Mais Populares';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '450px';
    protected static ?string $pollingInterval = '300s';

    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];

    protected function getData(): array
    {
        // Busca os 10 jogos mais jogados baseado no campo 'game'
        $gameStats = Order::select('game', DB::raw('COUNT(*) as plays'), DB::raw('SUM(amount) as total_amount'))
            ->whereNotNull('game')
            ->whereIn('type', ['bet', 'loss', 'win'])
            ->groupBy('game')
            ->orderBy('plays', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $plays = [];
        $revenue = [];
        $backgroundColors = [
            'rgba(59, 130, 246, 0.8)',   // Azul vibrante
            'rgba(239, 68, 68, 0.8)',    // Vermelho vibrante  
            'rgba(16, 185, 129, 0.8)',   // Verde vibrante
            'rgba(245, 158, 11, 0.8)',   // Amarelo vibrante
            'rgba(139, 92, 246, 0.8)',   // Roxo vibrante
            'rgba(6, 182, 212, 0.8)',    // Ciano vibrante
            'rgba(132, 204, 22, 0.8)',   // Lima vibrante
            'rgba(249, 115, 22, 0.8)',   // Laranja vibrante
            'rgba(236, 72, 153, 0.8)',   // Rosa vibrante
            'rgba(99, 102, 241, 0.8)'    // Índigo vibrante
        ];
        $borderColors = [
            'rgba(59, 130, 246, 1.0)',
            'rgba(239, 68, 68, 1.0)',
            'rgba(16, 185, 129, 1.0)',
            'rgba(245, 158, 11, 1.0)',
            'rgba(139, 92, 246, 1.0)',
            'rgba(6, 182, 212, 1.0)',
            'rgba(132, 204, 22, 1.0)',
            'rgba(249, 115, 22, 1.0)',
            'rgba(236, 72, 153, 1.0)',
            'rgba(99, 102, 241, 1.0)'
        ];

        foreach ($gameStats as $index => $stat) {
            $gameName = $stat->game ? $stat->game : "Jogo #{$index}";
            $labels[] = strlen($gameName) > 15 ? substr($gameName, 0, 15) . '...' : $gameName;
            $plays[] = $stat->plays;
            $revenue[] = $stat->total_amount;
        }

        return [
            'datasets' => [
                [
                    'label' => '🎯 Número de Jogadas',
                    'data' => $plays,
                    'backgroundColor' => array_slice($backgroundColors, 0, count($plays)),
                    'borderColor' => array_slice($borderColors, 0, count($plays)),
                    'borderWidth' => 3,
                    'hoverOffset' => 15,
                ],
            ],
            'labels' => $labels,
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
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { 
                            return context.label + ": " + context.parsed + " jogadas"; 
                        }',
                    ],
                ],
            ],
            'cutout' => '50%',
            'maintainAspectRatio' => false,
        ];
    }
}
