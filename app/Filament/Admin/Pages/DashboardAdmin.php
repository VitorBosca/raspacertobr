<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\PerformanceMetrics;
use App\Filament\Admin\Widgets\RevenueChart;
use App\Filament\Admin\Widgets\UsersGrowthChart;
use App\Filament\Admin\Widgets\PopularGamesChart;
use App\Filament\Admin\Widgets\FinancialFlowChart;
use App\Livewire\AdminWidgets;
use App\Livewire\WalletOverview;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

use App\Filament\Admin\Widgets\ReportsTableWidget;

class DashboardAdmin extends \Filament\Pages\Dashboard
{
    use HasFiltersForm, HasFiltersAction;

    /**
     * @dev @venixplataformas
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }


    /**
     * @return string|\Illuminate\Contracts\Support\Htmlable|null
     */
    public function getSubheading(): string| null|\Illuminate\Contracts\Support\Htmlable
    {
        $roleName = 'Admin';
        if(auth()->user()->hasRole('afiliado')) {
            $roleName = 'Afiliado';
        }

        $currentHour = now()->format('H');
        $greeting = $currentHour < 12 ? '🌅 Bom dia' : ($currentHour < 18 ? '☀️ Boa tarde' : '🌙 Boa noite');

        return "{$greeting}, {$roleName}! 🚀 Bem-vindo ao seu painel de controle.";
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('📊 Filtros de Período')
                    ->description('Selecione o período para análise dos dados')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('📅 Data Inicial')
                            ->default(now()->subDays(30))
                            ->native(false),
                        DatePicker::make('endDate')
                            ->label('📅 Data Final')
                            ->default(now())
                            ->native(false),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    /**
     * @return array|\Filament\Actions\Action[]|\Filament\Actions\ActionGroup[]
     */
    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->label('📊 Filtros')
                ->icon('heroicon-o-funnel')
                ->form([
                    DatePicker::make('startDate')
                        ->label('📅 Data Inicial')
                        ->default(now()->subDays(30))
                        ->native(false),
                    DatePicker::make('endDate')
                        ->label('📅 Data Final')
                        ->default(now())
                        ->native(false),
                ])
                ->color('primary'),
        ];
    }


    /**
     * @return string[]
     */
    public function getWidgets(): array
    {
        return [
            // 🚀 Métricas em tempo real
            PerformanceMetrics::class,
            
            // 📊 Estatísticas principais  
            StatsOverview::class,
            
            // 📈 Gráficos principais em destaque (layout expandido)
            RevenueChart::class,
            UsersGrowthChart::class,
            FinancialFlowChart::class,
            PopularGamesChart::class,
            
            // 💼 Dados específicos
            AdminWidgets::class,
            WalletOverview::class,
            
            // 📋 Relatórios
            ReportsTableWidget::class,
        ];
    }

    /**
     * @return array
     */
    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
        ];
    }
}
