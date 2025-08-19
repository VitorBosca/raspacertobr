<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = '⚡ Atividade Recente';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::with(['user'])
                    ->latest()
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('👤 Usuário')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->user?->name)
                    ->formatStateUsing(fn ($state) => $state ? "🎮 {$state}" : '👤 ---'),
                    
                Tables\Columns\TextColumn::make('game')
                    ->label('🎲 Jogo')
                    ->limit(25)
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->game_uuid) {
                            return "🎯 " . substr($record->game_uuid, 0, 8) . "...";
                        }
                        return $state ? "🎲 {$state}" : '🎮 Raspadinha';
                    }),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('📊 Tipo')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'bet' => '🎲 Aposta',
                            'win' => '🏆 Ganhou',
                            'loss' => '💔 Perdeu',
                            'deposit' => '💰 Depósito',
                            'withdrawal' => '💸 Saque',
                            'scratch_card' => '🎫 Raspadinha',
                            default => ucfirst($state)
                        };
                    })
                    ->colors([
                        'primary' => 'bet',
                        'success' => 'win',
                        'danger' => 'loss',
                        'warning' => ['deposit', 'withdrawal'],
                        'info' => 'scratch_card',
                    ]),
                    
                Tables\Columns\TextColumn::make('amount')
                    ->label('💵 Valor')
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                    ->alignEnd()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('🕐 Quando')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('d/m/Y H:i:s'))
                    ->sortable(),
            ])
            ->striped()
            ->paginated(false)
            ->poll('15s')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('📭 Nenhuma atividade recente')
            ->emptyStateDescription('Quando houver movimentações, elas aparecerão aqui')
            ->emptyStateIcon('heroicon-o-clock');
    }
}
