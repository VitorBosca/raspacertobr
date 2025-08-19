<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Filament\Admin\Widgets\StatsUserDetailOverview;
use App\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\Page;

class DetailUser extends Page
{

    protected static ?string $title = '📊 Analytics do Usuário';

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.admin.resources.user-resource.pages.detail-user';

    public User $record;
    public ?array $data = [];

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * @return int|string|array
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

    /**
     * @return array
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return Component
     */
    protected function getTemplateSection(): Component
    {
        return Section::make('📋 Informações Detalhadas do Usuário')
            ->description('Dados completos e estatísticas do usuário')
            ->icon('heroicon-o-user-circle')
            ->schema([
                ViewField::make('preview.default')
                    ->view('filament.admin.resources.user-resource.pages.detail-info'),
            ]);
    }

    /**
     * @return string[]
     */
    public function getWidgets(): array
    {
        return array(
            // Widget principal de estatísticas
            StatsUserDetailOverview::make([
                'record' => $this->record,
            ]),
            
            // Widget de depósitos e transações
            \App\Filament\Admin\Resources\UserResource\Widgets\DepositsOverview::make([
                'record' => $this->record,
            ]),
            
            // Widget de apostas do usuário
            \App\Filament\Admin\Resources\UserResource\Widgets\MyBetsTableWidget::make([
                'record' => $this->record,
            ]),
            
            // Widget de indicações de depósitos
            UserResource\Widgets\DepositsIndicationsOverview::make([
                'record' => $this->record,
            ]),
            
            // Widget de tabela de indicações
            UserResource\Widgets\IndicationsTableWidget::make([
                'record' => $this->record,
            ]),
        );
    }

    /**
     *
     * @dev venixplataformas - Meu instagram
     * @return array
     */
    protected function getFormActions(): array
    {
        return [

        ];
    }

    /**
     * @return array|\Filament\Widgets\WidgetConfiguration[]|string[]
     */
    protected function getFooterWidgets(): array
    {
        return [

        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
            '2xl' => 4,
        ];
    }
}
