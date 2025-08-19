<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Filament\Resources\GameResource\RelationManagers;
use App\Models\Game;
use App\Models\Provider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Gerenciar Jogos';

    protected static ?string $modelLabel = 'Jogo';

    protected static ?string $pluralModelLabel = 'Jogos';

    protected static ?string $navigationGroup = 'Catálogo de Jogos';

    /**
     * @dev @venixplataformas
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('🎮 Básico')
                        ->description('Informações principais')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('game_name')
                                                ->label('🎯 Nome da Raspadinha')
                                                ->placeholder('Ex: Raspadinha Premium')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                                    if ($operation !== 'create') {
                                                        return;
                                                    }
                                                    $set('game_id', \Str::slug($state));
                                                    $set('game_code', 'RASP_' . strtoupper(\Str::slug($state, '_')));
                                                })
                                                ->maxLength(191)
                                                ->columnSpan(2),
                                            
                                            Forms\Components\TextInput::make('game_id')
                                                ->label('🆔 ID Único')
                                                ->placeholder('id-unico-raspadinha')
                                                ->required()
                                                ->alphaDash()
                                                ->unique(ignoreRecord: true)
                                                ->helperText('Gerado automaticamente')
                                                ->maxLength(191),
                                            
                                            Forms\Components\TextInput::make('game_code')
                                                ->label('📝 Código')
                                                ->placeholder('RASP_CODIGO')
                                                ->required()
                                                ->alphaDash()
                                                ->unique(ignoreRecord: true)
                                                ->helperText('Gerado automaticamente')
                                                ->maxLength(191),
                                                
                                            Forms\Components\Select::make('game_type')
                                                ->label('🎲 Tipo')
                                                ->options([
                                                    'raspadinha' => '🎫 Raspadinha',
                                                    'slot' => '🎰 Slot',
                                                    'cassino' => '🃏 Cassino',
                                                    'esportes' => '⚽ Esportes',
                                                    'arcade' => '🕹️ Arcade',
                                                    'mesa' => '🎲 Mesa',
                                                ])
                                                ->required()
                                                ->native(false)
                                                ->searchable()
                                                ->default('raspadinha')
                                                ->live(),
                                                
                                            Forms\Components\TextInput::make('views')
                                                ->label('👁️ Views Inicial')
                                                ->numeric()
                                                ->default(0)
                                                ->minValue(0)
                                                ->suffix('views'),
                                        ]),
                                        
                                    Forms\Components\Textarea::make('description')
                                        ->label('📄 Descrição')
                                        ->placeholder('Descreva a raspadinha de forma atrativa...')
                                        ->rows(3)
                                        ->maxLength(300)
                                        ->helperText('Máximo 300 caracteres')
                                        ->columnSpanFull(),
                                ])
                        ]),

                    Forms\Components\Wizard\Step::make('🖼️ Visual')
                        ->description('Imagem e aparência')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make('🎨 Design')
                                ->schema([
                                    Forms\Components\FileUpload::make('cover')
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
                                        
                                    Forms\Components\Grid::make(3)
                                        ->schema([
                                            Forms\Components\ColorPicker::make('primary_color')
                                                ->label('🎨 Cor Principal')
                                                ->default('#3B82F6'),
                                                
                                            Forms\Components\ColorPicker::make('secondary_color')
                                                ->label('🌈 Cor Secundária')
                                                ->default('#EF4444'),
                                                
                                            Forms\Components\Select::make('theme')
                                                ->label('🎭 Tema')
                                                ->options([
                                                    'classic' => '🎫 Clássico',
                                                    'modern' => '✨ Moderno',
                                                    'neon' => '🌟 Neon',
                                                    'gold' => '🏆 Dourado',
                                                ])
                                                ->default('classic')
                                                ->native(false),
                                        ]),
                                ])
                        ]),

                    Forms\Components\Wizard\Step::make('⚙️ Config')
                        ->description('Configurações do jogo')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Section::make('🎯 Configurações')
                                ->schema([
                                    Forms\Components\Grid::make(4)
                                        ->schema([
                                            Forms\Components\Toggle::make('status')
                                                ->label('✅ Ativo')
                                                ->default(true)
                                                ->inline(false),
                                                
                                            Forms\Components\Toggle::make('show_home')
                                                ->label('🏠 Home')
                                                ->inline(false),
                                                
                                            Forms\Components\Toggle::make('is_featured')
                                                ->label('⭐ Destaque')
                                                ->inline(false),
                                                
                                            Forms\Components\Toggle::make('only_demo')
                                                ->label('🎯 Demo')
                                                ->inline(false),
                                        ]),
                                        
                                    Forms\Components\Fieldset::make('💰 Valores')
                                        ->schema([
                                            Forms\Components\Grid::make(3)
                                                ->schema([
                                                    Forms\Components\TextInput::make('valor')
                                                        ->label('💰 Preço')
                                                        ->numeric()
                                                        ->step(0.01)
                                                        ->prefix('R$')
                                                        ->default(1.00),

                                                    /*Forms\Components\TextInput::make('min_bet')
                                                        ->label('💰 Min')
                                                        ->numeric()
                                                        ->step(0.01)
                                                        ->prefix('R$')
                                                        ->default(1.00),
                                                        
                                                    Forms\Components\TextInput::make('max_bet')
                                                        ->label('💎 Max')
                                                        ->numeric()
                                                        ->step(0.01)
                                                        ->prefix('R$')
                                                        ->default(100.00),*/
                                                ]),
                                        ]),
                                ])
                        ]),

                    Forms\Components\Wizard\Step::make('🎁 Prêmios')
                        ->description('Configure prêmios e chances')
                        ->icon('heroicon-o-gift')
                        ->schema([
                            Forms\Components\Section::make('🏆 Prêmios')
                                ->schema([
                                    Forms\Components\Placeholder::make('prizes_info')
                                        ->label('💡 Dica')
                                        ->content('A soma das probabilidades deve ser 100% para equilibrio do jogo.')
                                        ->columnSpanFull(),
                                        
                                    Forms\Components\Repeater::make('premios')
                                        ->label('🎁 Lista de Prêmios')
                                        ->schema([
                                            Forms\Components\Grid::make(4)
                                                ->schema([
                                                    Forms\Components\TextInput::make('name')
                                                        ->label('🏷️ Nome')
                                                        ->placeholder('Ex: iPhone 15')
                                                        ->required()
                                                        ->maxLength(100)
                                                        ->columnSpan(2),
                                                        
                                                    Forms\Components\Select::make('type')
                                                        ->label('💰 Tipo')
                                                        ->options([
                                                            'money' => '💵 Dinheiro',
                                                            'product' => '📦 Produto',
                                                            'bonus' => '🎉 Bônus',
                                                            'points' => '⭐ Pontos'
                                                        ])
                                                        ->required()
                                                        ->live()
                                                        ->native(false),
                                                        
                                                    Forms\Components\TextInput::make('value')
                                                        ->label('💲 Valor')
                                                        ->placeholder('R$ 10,00')
                                                        ->required()
                                                        ->maxLength(30),
                                                ]),
                                                
                                            Forms\Components\Grid::make(4)
                                                ->schema([
                                                    Forms\Components\TextInput::make('cash_value')
                                                        ->label('💸 R$')
                                                        ->numeric()
                                                        ->step(0.01)
                                                        ->prefix('R$')
                                                        ->visible(fn ($get) => in_array($get('type'), ['money', 'bonus']))
                                                        ->required(fn ($get) => $get('type') === 'money'),
                                                        
                                                    Forms\Components\TextInput::make('points_value')
                                                        ->label('⭐ Pts')
                                                        ->numeric()
                                                        ->suffix('pts')
                                                        ->visible(fn ($get) => $get('type') === 'points')
                                                        ->required(fn ($get) => $get('type') === 'points'),
                                                        
                                                    Forms\Components\TextInput::make('probability')
                                                        ->label('🎯 %')
                                                        ->numeric()
                                                        ->step(0.1)
                                                        ->suffix('%')
                                                        ->required(),
                                                        
                                                    Forms\Components\TextInput::make('quantity')
                                                        ->label('📦 Qtd')
                                                        ->numeric()
                                                        ->default(1)
                                                        ->suffix('un'),
                                                ]),
                                                
                                            Forms\Components\Grid::make(2)
                                                ->schema([
                                                    Forms\Components\FileUpload::make('image')
                                                        ->label('🖼️ Imagem')
                                                        ->image()
                                                        ->imageResizeTargetWidth(100)
                                                        ->imageResizeTargetHeight(100)
                                                        ->directory('games/covers'),
                                                        
                                                    Forms\Components\Textarea::make('description')
                                                        ->label('📝 Descrição')
                                                        ->rows(2)
                                                        ->maxLength(150),
                                                ]),
                                        ])
                                        ->collapsible()
                                        ->collapsed()
                                        ->itemLabel(fn (array $state): ?string => 
                                            ($state['name'] ?? 'Prêmio') . 
                                            (isset($state['probability']) ? " ({$state['probability']}%)" : '')
                                        )
                                        ->addActionLabel('➕ Novo Prêmio')
                                        ->reorderable()
                                        ->cloneable()
                                        ->deleteAction(
                                            fn ($action) => $action
                                                ->requiresConfirmation()
                                                ->modalHeading('🗑️ Remover')
                                                ->modalSubmitActionLabel('Remover')
                                        )
                                        ->columnSpanFull()
                                        ->minItems(1)
                                        ->maxItems(15),
                                        
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('add_default_prizes')
                                            ->label('🎯 Prêmios Padrão')
                                            ->icon('heroicon-m-sparkles')
                                            ->color('primary')
                                            ->size('sm')
                                            ->action(function ($set, $get) {
                                                $current = $get('premios') ?? [];
                                                $defaults = \App\Models\Setting::getDefaultPrizes();
                                                array_walk($defaults, fn(&$item) => $item['image'] = null);
                                                $set('premios', array_merge($current, $defaults));
                                            }),
                                            
                                        Forms\Components\Actions\Action::make('calc_probability')
                                            ->label('🧮 Calcular')
                                            ->icon('heroicon-m-calculator')
                                            ->color('warning')
                                            ->size('sm')
                                            ->action(function ($get) {
                                                $prizes = $get('premios') ?? [];
                                                $total = collect($prizes)->sum('probability');
                                                
                                                if ($total > 100) {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('⚠️ Atenção!')
                                                        ->body("Total: {$total}% (excede 100%)")
                                                        ->warning()
                                                        ->send();
                                                } elseif ($total < 100) {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('📊 Info')
                                                        ->body("Total: {$total}% (faltam " . (100 - $total) . "%)")
                                                        ->info()
                                                        ->send();
                                                } else {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('✅ Perfeito!')
                                                        ->body("Total: {$total}% (100% completo)")
                                                        ->success()
                                                        ->send();
                                                }
                                            }),
                                    ])->columnSpanFull(),
                                ])
                        ]),
                ])
                ->skippable()
                ->persistStepInQueryString()
                ->columnSpanFull()
            ]);
    }

    /**
     * @param Table $table
     * @return Table
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->label('🖼️ Capa')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl('/assets/images/no-game.png')
                    ->extraImgAttributes(['loading' => 'lazy']),
                
                Tables\Columns\TextColumn::make('game_name')
                    ->label('🎮 Nome do Jogo')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->color('primary')
                    ->description(fn (Game $record): string => $record->description ? substr($record->description, 0, 50) . '...' : 'Sem descrição'),

                Tables\Columns\TextColumn::make('game_type')
                    ->label('🎯 Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'raspadinha' => 'success',
                        'slot' => 'warning',
                        'cassino' => 'danger',
                        'esportes' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'raspadinha' => '🎫 Raspadinha',
                        'slot' => '🎰 Slot',
                        'cassino' => '🃏 Cassino',
                        'esportes' => '⚽ Esportes',
                        'arcade' => '🕹️ Arcade',
                        'mesa' => '🎲 Mesa',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('premios_count')
                    ->label('🎁 Prêmios')
                    ->getStateUsing(function (Game $record): string {
                        $premios = $record->premios;
                        $count = is_array($premios) ? count($premios) : 0;
                        return $count > 0 ? "{$count}" : '0';
                    })
                    ->badge()
                    ->icon('heroicon-m-gift')
                    ->color(fn (string $state): string => $state === '0' ? 'gray' : 'success')
                    ->formatStateUsing(fn (string $state): string => $state === '0' ? 'Sem prêmios' : "{$state} prêmio(s)"),

                Tables\Columns\ToggleColumn::make('status')
                    ->label('✅ Ativo')
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),
                
                Tables\Columns\ToggleColumn::make('show_home')
                    ->afterStateUpdated(function ($record, $state) {
                        if($state == 1) {
                            $record->update(['status' => 1]);
                        }
                    })
                    ->label('🏠 Home')
                    ->onIcon('heroicon-m-home')
                    ->offIcon('heroicon-m-home')
                    ->onColor('primary')
                    ->offColor('gray'),
                
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('⭐ Destaque')
                    ->onIcon('heroicon-m-star')
                    ->offIcon('heroicon-m-star')
                    ->onColor('warning')
                    ->offColor('gray'),
                
                Tables\Columns\TextColumn::make('views')
                    ->label('👁️ Views')
                    ->icon('heroicon-o-eye')
                    ->numeric()
                    ->formatStateUsing(fn (Game $record): string => number_format($record->views))
                    ->sortable()
                    ->color('info')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('📅 Criado em')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->color('gray'),

                // Colunas ocultas por padrão para não sobrecarregar a visualização
                Tables\Columns\TextColumn::make('game_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('🆔 Game ID')
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('game_code')
                    ->label('📝 Código')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('📄 Descrição')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('technology')
                    ->label('⚙️ Tecnologia')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->badge(),
                    
                Tables\Columns\IconColumn::make('has_lobby')
                    ->label('🏛️ Lobby')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('is_mobile')
                    ->label('📱 Mobile')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('has_freespins')
                    ->label('🎰 Free Spins')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('has_tables')
                    ->label('🎲 Tables')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                    
                Tables\Columns\ToggleColumn::make('only_demo')
                    ->label('🎯 Demo')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('🔄 Atualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->preload()
                    ->multiple()
                    ->label('Categorias')
                    ->indicator('Categoria')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('provider')
                    ->relationship('provider', 'name')
                    ->label('Provedor')
                    ->indicator('Provedor')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('game_type')
                    ->label('🎯 Tipo de Jogo')
                    ->options([
                        'raspadinha' => '🎫 Raspadinha',
                        'slot' => '🎰 Slot',
                        'cassino' => '🃏 Cassino',
                        'esportes' => '⚽ Esportes',
                        'arcade' => '🕹️ Arcade',
                        'mesa' => '🎲 Mesa',
                    ])
                    ->indicator('Tipo'),

                Tables\Filters\SelectFilter::make('has_premios')
                    ->label('Status dos Prêmios')
                    ->options([
                        'with_premios' => '🎁 Com prêmios',
                        'without_premios' => '❌ Sem prêmios'
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['value'])) {
                            if ($data['value'] === 'with_premios') {
                                return $query->whereNotNull('premios')->where('premios', '!=', '[]')->where('premios', '!=', '');
                            } elseif ($data['value'] === 'without_premios') {
                                return $query->where(function ($q) {
                                    $q->whereNull('premios')
                                      ->orWhere('premios', '[]')
                                      ->orWhere('premios', '');
                                });
                            }
                        }
                        return $query;
                    })
                    ->indicator('Prêmios'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status do Jogo')
                    ->boolean()
                    ->trueLabel('✅ Ativo')
                    ->falseLabel('❌ Inativo')
                    ->placeholder('🔍 Todos'),

                Tables\Filters\TernaryFilter::make('show_home')
                    ->label('Exibir na Home')
                    ->boolean()
                    ->trueLabel('🏠 Sim')
                    ->falseLabel('❌ Não')
                    ->placeholder('🔍 Todos'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Destaques')
                    ->boolean()
                    ->trueLabel('⭐ Destaque')
                    ->falseLabel('❌ Normal')
                    ->placeholder('🔍 Todos'),

                Tables\Filters\Filter::make('views_range')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('views_from')
                                    ->label('Visualizações mínimas')
                                    ->numeric()
                                    ->placeholder('Ex: 100'),
                                Forms\Components\TextInput::make('views_until')
                                    ->label('Visualizações máximas')
                                    ->numeric()
                                    ->placeholder('Ex: 10000'),
                            ])
                    ])
                    ->query(function ($query, array $data): Builder {
                        return $query
                            ->when(
                                $data['views_from'],
                                fn ($query, $value): Builder => $query->where('views', '>=', $value),
                            )
                            ->when(
                                $data['views_until'],
                                fn ($query, $value): Builder => $query->where('views', '<=', $value),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['views_from'] ?? null) {
                            $indicators['views_from'] = 'Views ≥ ' . number_format($data['views_from']);
                        }
                        if ($data['views_until'] ?? null) {
                            $indicators['views_until'] = 'Views ≤ ' . number_format($data['views_until']);
                        }
                        return $indicators;
                    }),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
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
                    Tables\Actions\Action::make('manage_prizes')
                        ->label('🎁 Gerenciar Prêmios')
                        ->icon('heroicon-m-gift')
                        ->color('success')
                        ->url(fn (Game $record): string => route('filament.admin.resources.games.edit', $record) . '#premios')
                        ->visible(fn (Game $record): bool => $record->game_type === 'raspadinha'),
                    Tables\Actions\ReplicateAction::make()
                        ->label('📋 Duplicar')
                        ->icon('heroicon-m-document-duplicate')
                        ->color('gray')
                        ->excludeAttributes(['game_id', 'game_code'])
                        ->mutateRecordDataUsing(function (array $data): array {
                            $data['game_name'] = $data['game_name'] . ' (Cópia)';
                            $data['status'] = false;
                            $data['show_home'] = false;
                            $data['is_featured'] = false;
                            return $data;
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->label('🗑️ Excluir')
                        ->icon('heroicon-m-trash')
                        ->color('danger'),
                ])
                ->label('⚙️ Ações')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-m-trash')
                        ->color('danger'),
                    
                    Tables\Actions\BulkAction::make('activate_games')
                        ->label('✅ Ativar Jogos')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Ativar Jogos Selecionados')
                        ->modalDescription('Tem certeza que deseja ativar todos os jogos selecionados?')
                        ->modalSubmitActionLabel('Sim, ativar')
                        ->action(function($records) {
                            $records->each->update(['status' => 1]);
                        }),
                    
                    Tables\Actions\BulkAction::make('deactivate_games')
                        ->label('❌ Desativar Jogos')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Desativar Jogos Selecionados')
                        ->modalDescription('Tem certeza que deseja desativar todos os jogos selecionados?')
                        ->modalSubmitActionLabel('Sim, desativar')
                        ->action(function($records) {
                            $records->each(function($record) {
                                $record->update(['status' => 0, 'show_home' => 0]);
                            });
                        }),
                    
                    Tables\Actions\BulkAction::make('feature_games')
                        ->label('⭐ Destacar Jogos')
                        ->icon('heroicon-m-star')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Destacar Jogos Selecionados')
                        ->modalDescription('Os jogos selecionados serão marcados como destaque.')
                        ->action(function($records) {
                            $records->each->update(['is_featured' => 1]);
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('🎮 Criar Primeira Raspadinha')
                    ->icon('heroicon-m-plus')
                    ->color('primary'),
            ])
            ->emptyStateHeading('🎫 Nenhuma raspadinha encontrada')
            ->emptyStateDescription('Comece criando sua primeira raspadinha no sistema. Configure prêmios, imagens e probabilidades!')
            ->emptyStateIcon('heroicon-o-gift')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->recordTitleAttribute('game_name')
            ->searchOnBlur()
            ->deferLoading();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\GameResource\Pages\ListGames::route('/'),
            'create' => \App\Filament\Admin\Resources\GameResource\Pages\CreateGame::route('/create'),
            'edit' => \App\Filament\Admin\Resources\GameResource\Pages\EditGame::route('/{record}/edit'),
        ];
    }
}
