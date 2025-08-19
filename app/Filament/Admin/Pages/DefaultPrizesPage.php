<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use App\Services\PrizeService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\HtmlString;

class DefaultPrizesPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static string $view = 'filament.admin.pages.default-prizes';

    protected static ?string $navigationLabel = 'Prêmios Padrão';

    protected static ?string $modelLabel = 'Prêmios Padrão';

    protected static ?string $title = 'Gerenciar Prêmios Padrão';

    protected static ?string $slug = 'premios-padrao';

    protected static ?string $navigationGroup = 'Configurações';

    /**
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public ?array $data = [];
    public Setting $setting;

    public function mount(): void
    {
        $this->setting = Setting::first() ?? new Setting();
        
        $currentPrizes = $this->setting->premios_padrao ? json_decode($this->setting->premios_padrao, true) : [];
        
        // Se não há prêmios salvos, usar os padrão do sistema
        if (empty($currentPrizes)) {
            $currentPrizes = PrizeService::getSystemDefaultPrizes();
        }

        $this->form->fill([
            'premios_padrao' => $currentPrizes
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Prêmios Padrão do Sistema')
                    ->description('Configure os prêmios que aparecerão por padrão nos jogos. Estes prêmios serão usados quando não houver prêmios específicos configurados no jogo.')
                    ->schema([
                        Repeater::make('premios_padrao')
                            ->label('Lista de Prêmios')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nome do Prêmio')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Ex: Nota R$ 10'),

                                        Select::make('type')
                                            ->label('Tipo')
                                            ->required()
                                            ->options([
                                                'money' => 'Dinheiro',
                                                'product' => 'Produto Físico',
                                            ])
                                            ->default('money'),

                                        TextInput::make('value')
                                            ->label('Valor de Exibição')
                                            ->required()
                                            ->placeholder('Ex: R$ 10,00')
                                            ->maxLength(50),
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('cash_value')
                                            ->label('Valor em Dinheiro')
                                            ->numeric()
                                            ->default(0)
                                            ->step(0.01)
                                            ->placeholder('0.00')
                                            ->helperText('Valor que será creditado na carteira (apenas para prêmios do tipo dinheiro)'),

                                        TextInput::make('probability')
                                            ->label('Probabilidade (%)')
                                            ->numeric()
                                            ->required()
                                            ->default(10)
                                            ->step(0.1)
                                            ->suffix('%')
                                            ->helperText('Probabilidade de aparecer na cartela'),
                                    ]),

                                FileUpload::make('cover')
                                        ->label('🖼️ Capa Principal')
                                        ->image()
                                        ->imageEditor()
                                        ->imageCropAspectRatio('1:1')
                                        ->imageResizeTargetWidth(322)
                                        ->imageResizeTargetHeight(322)
                                        ->directory('games/covers')
                                        ->visibility('public')
                                        ->columnSpanFull()
                                        ->helperText('📐 322x322px | JPG, PNG, WEBP | Max: 2MB')
                                        ->required(),

                                Textarea::make('description')
                                    ->label('Descrição')
                                    ->maxLength(500)
                                    ->rows(2)
                                    ->placeholder('Descrição opcional do prêmio')
                                    ->helperText('Descrição adicional do prêmio (opcional)'),
                            ])
                            ->columns(1)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Novo Prêmio')
                            ->addActionLabel('Adicionar Prêmio')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
                            ->defaultItems(0)
                            ->grid(1)
                    ])
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Salvar Configurações')
                ->icon('heroicon-o-check')
                ->color('success')
                ->keyBindings(['mod+s'])
                ->action('save'),

            \Filament\Actions\Action::make('restore_defaults')
                ->label('Restaurar Padrões')
                ->color('gray')
                ->icon('heroicon-o-arrow-path')
                ->action('restoreDefaults')
                ->requiresConfirmation()
                ->modalHeading('Restaurar Prêmios Padrão')
                ->modalDescription('Tem certeza que deseja restaurar os prêmios padrão do sistema? Isso irá sobrescrever todas as configurações atuais.')
                ->modalSubmitActionLabel('Sim, restaurar'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Se não existe um setting, criar um novo
            if (!$this->setting->exists) {
                $this->setting = Setting::create([]);
            }

            // Salvar os prêmios padrão
            $this->setting->update([
                'premios_padrao' => json_encode($data['premios_padrao'] ?? [])
            ]);

            // Limpar cache se necessário
            Cache::forget('default_prizes');

            Notification::make()
                ->title('Prêmios padrão salvos com sucesso!')
                ->success()
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }

    public function restoreDefaults(): void
    {
        try {
            $defaultPrizes = PrizeService::getSystemDefaultPrizes();

            $this->form->fill([
                'premios_padrao' => $defaultPrizes
            ]);

            // Salvar automaticamente
            if (!$this->setting->exists) {
                $this->setting = Setting::create([]);
            }

            $this->setting->update([
                'premios_padrao' => json_encode($defaultPrizes)
            ]);

            Cache::forget('default_prizes');

            Notification::make()
                ->title('Prêmios padrão restaurados com sucesso!')
                ->success()
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }
}
