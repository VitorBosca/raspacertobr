<?php

namespace App\Filament\Admin\Resources;

use App\Models\Setting;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SettingResource extends Resource
{

    protected Setting $record;

    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = '⚙️ Configurações';

    protected static ?string $modelLabel = 'Configuração';

    protected static ?string $pluralModelLabel = 'Configurações';

    protected static ?string $navigationGroup = '🛠️ Sistema';

    /**
     * @dev @venixplataformas
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * @dev venixplataformas - Meu instagram
     * @return void
     */
    public function mount(): void
    {
        $this->record = Setting::first();
    }

    /**
     * @param Model $record
     * @return FilamentPageSidebar
     */
    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle('⚙️ Configurações do Sistema')
            ->setDescription('Gerencie todas as configurações da plataforma')
            ->setNavigationItems([
                PageNavigationItem::make('🏠 Configurações Gerais')
                    ->translateLabel()
                    ->url(static::getUrl('index'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.index');
                    }),

                PageNavigationItem::make('💎 Sistema VIP')
                    ->translateLabel()
                    ->url(static::getUrl('bonus', ['record' => $record->id]))
                    ->icon('heroicon-o-star')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.bonus');
                    }),

                PageNavigationItem::make('🔄 Rollover')
                    ->translateLabel()
                    ->url(static::getUrl('rollover', ['record' => $record->id]))
                    ->icon('heroicon-o-arrow-path')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.rollover');
                    }),

                PageNavigationItem::make('📊 Taxas & Comissões')
                    ->translateLabel()
                    ->url(static::getUrl('fee', ['record' => $record->id]))
                    ->icon('heroicon-o-chart-pie')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.fee');
                    }),

                PageNavigationItem::make('⚖️ Limites')
                    ->translateLabel()
                    ->url(static::getUrl('limit', ['record' => $record->id]))
                    ->icon('heroicon-o-scale')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.limit');
                    }),

                PageNavigationItem::make('💳 Métodos de Pagamento')
                    ->translateLabel()
                    ->url(static::getUrl('payment', ['record' => $record->id]))
                    ->icon('heroicon-o-credit-card')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.payment');
                    }),

                PageNavigationItem::make('🌐 Gateways')
                    ->translateLabel()
                    ->url(static::getUrl('gateway', ['record' => $record->id]))
                    ->icon('heroicon-o-server')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.gateway');
                    }),

                PageNavigationItem::make('🤝 Sistema de Afiliados')
                    ->translateLabel()
                    ->url(static::getUrl('affiliate', ['record' => $record->id]))
                    ->icon('heroicon-o-user-group')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.affiliate');
                    }),
            ]);
    }

    /**
     *
     * @dev venixplataformas
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('🌐 Informações Básicas')
                        ->description('Configure dados fundamentais da plataforma')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Section::make('🏢 Dados da Empresa')
                                ->description('Informações principais da sua plataforma')
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('site_name')
                                                ->label('🌐 Nome do Site')
                                                ->placeholder('Exemplo: Raspadinha VIP')
                                                ->required()
                                                ->maxLength(255),
                                                
                                            Forms\Components\TextInput::make('site_url')
                                                ->label('🔗 URL Principal')
                                                ->placeholder('https://seusite.com')
                                                ->url()
                                                ->required(),
                                        ]),
                                        
                                    Forms\Components\Textarea::make('site_description')
                                        ->label('📄 Descrição da Plataforma')
                                        ->placeholder('Descreva sua plataforma de apostas...')
                                        ->rows(3)
                                        ->maxLength(500)
                                        ->helperText('Máximo 500 caracteres')
                                        ->columnSpanFull(),
                                ])
                        ]),

                    Forms\Components\Wizard\Step::make('🎨 Aparência')
                        ->description('Personalize a identidade visual')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make('🖼️ Identidade Visual')
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\FileUpload::make('logo')
                                                ->label('📷 Logo Principal')
                                                ->image()
                                                ->imageEditor()
                                                ->imageCropAspectRatio('3:1')
                                                ->imageResizeTargetWidth(300)
                                                ->imageResizeTargetHeight(100)
                                                ->directory('settings/logos')
                                                ->visibility('public')
                                                ->helperText('📐 300x100px | PNG recomendado'),
                                                
                                            Forms\Components\FileUpload::make('favicon')
                                                ->label('🔖 Favicon')
                                                ->image()
                                                ->imageResizeTargetWidth(32)
                                                ->imageResizeTargetHeight(32)
                                                ->directory('settings/favicons')
                                                ->visibility('public')
                                                ->helperText('📐 32x32px | ICO ou PNG'),
                                        ]),
                                ])
                        ]),

                    Forms\Components\Wizard\Step::make('⚙️ Sistema')
                        ->description('Configurações técnicas e operacionais')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Section::make('🔧 Configurações do Sistema')
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\Toggle::make('maintenance_mode')
                                                ->label('🔧 Modo Manutenção')
                                                ->helperText('Ativar quando precisar fazer manutenções'),
                                                
                                            Forms\Components\Toggle::make('registration_enabled')
                                                ->label('👥 Permitir Novos Registros')
                                                ->helperText('Habilitar cadastro de novos usuários')
                                                ->default(true),
                                        ]),
                                ])
                        ]),
                ])
                ->skippable()
                ->persistStepInQueryString()
                ->columnSpanFull()
            ]);
    }

    /**
     * @dev venixplataformas
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('site_name')
                    ->label('🌐 Nome do Site')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('site_description')
                    ->label('📝 Descrição')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\ToggleColumn::make('maintenance_mode')
                    ->label('🔧 Manutenção')
                    ->onIcon('heroicon-m-wrench-screwdriver')
                    ->offIcon('heroicon-m-check-circle')
                    ->onColor('warning')
                    ->offColor('success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('📅 Criado em')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('maintenance_mode')
                    ->label('Modo Manutenção')
                    ->boolean()
                    ->trueLabel('🔧 Em Manutenção')
                    ->falseLabel('✅ Ativo')
                    ->placeholder('🔍 Todos'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('👁️ Visualizar')
                        ->icon('heroicon-m-eye')
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->label('✏️ Editar')
                        ->icon('heroicon-m-pencil-square')
                        ->color('warning'),
                ])
                ->label('⚙️ Ações')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('⚙️ Criar Configuração Inicial')
                    ->icon('heroicon-m-plus')
                    ->color('primary'),
            ])
            ->emptyStateHeading('⚙️ Nenhuma configuração encontrada')
            ->emptyStateDescription('Configure sua plataforma para começar a operar. Defina informações básicas, limites e integrações!')
            ->emptyStateIcon('heroicon-o-cog-6-tooth')
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->searchOnBlur()
            ->deferLoading();
    }

    /**
     * @return array|\Filament\Resources\RelationManagers\RelationGroup[]|\Filament\Resources\RelationManagers\RelationManagerConfiguration[]|string[]
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @dev venixplataformas
     * @return array|\Filament\Resources\Pages\PageRegistration[]
     */
    public static function getPages(): array
    {

        return [
            'index' => \App\Filament\Admin\Resources\SettingResource\Pages\DefaultSetting::route('/'),
            'limit' => \App\Filament\Admin\Resources\SettingResource\Pages\LimitSetting::route('/limit/{record}'),
            'bonus' => \App\Filament\Admin\Resources\SettingResource\Pages\BonusSetting::route('/bonus/{record}'),
            'rollover' => \App\Filament\Admin\Resources\SettingResource\Pages\RolloverSetting::route('/rollover/{record}'),
            'details' => \App\Filament\Admin\Resources\SettingResource\Pages\DefaultSetting::route('/details/{record}'),
            'fee' => \App\Filament\Admin\Resources\SettingResource\Pages\FeeSetting::route('/fee/{record}'),
            'payment' => \App\Filament\Admin\Resources\SettingResource\Pages\PaymentSetting::route('/payment/{record}'),
            'affiliate' => \App\Filament\Admin\Resources\SettingResource\Pages\AffiliatePage::route('/affiliate/{record}'),
            'gateway' => \App\Filament\Admin\Resources\SettingResource\Pages\GatewayPage::route('/gateway/{record}'),
        ];
    }
}
