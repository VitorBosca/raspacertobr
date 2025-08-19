<?php

namespace App\Filament\Admin\Resources\UserResource\Widgets;

use App\Models\AffiliateHistory;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class IndicationsTableWidget extends BaseWidget
{
    public User $record;

    public function mount($record)
    {
        $this->record = $record;
    }

    protected static ?string $heading = 'Todas Indicações';

    protected static ?int $navigationSort = -1;

    protected int | string | array $columnSpan = 'full';


    /**
     * @param $record
     * @return string
     */
    private function prepareDeposit($record) : string
    {
        $totalDeposits = \Helper::amountFormatDecimal(Deposit::where('user_id', $record->user_id)->sum('amount')); /// soma de todos os Depositos
        $totalDepositsConfirmed = \Helper::amountFormatDecimal(Deposit::where('user_id', $record->user_id)->where('status', 1)->sum('amount')); /// quantidade de depositos confirmados
        $totalDepositsUnConfirmed = \Helper::amountFormatDecimal(Deposit::where('user_id', $record->user_id)->where('status', 0)->sum('amount')); /// quantidade de depositos não confirmado
        $qtyDeposits = Deposit::where('user_id', $record->user_id)->where('status', 1)->count(); /// total de depositos confirmados

        return "Depositos: $totalDeposits - Confirmados: $totalDepositsConfirmed - Pendentes: $totalDepositsUnConfirmed - Qtd. Depositos: $qtyDeposits";
    }

    /**
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        /// ->where('commission_type', 'cpa')
        return $table
            ->query(AffiliateHistory::query()->where('inviter', $this->record->id)->where('commission_type', 'cpa'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable(),
                Tables\Columns\TextColumn::make('losses')
                    ->formatStateUsing(fn (AffiliateHistory $record): string => 'Qtd: '.$record->losses.' - Total: R$'.$record->losses_amount)
                    ->label('Perdas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->formatStateUsing(fn (AffiliateHistory $record): string => $this->prepareDeposit($record))
                    ->label('Perdas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commission_type')
                    ->label('Tipo de Comissão')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('commission_paid')
                    ->label('Comissão')
                    ->money('BRL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Data Inicial'),
                        DatePicker::make('created_until')->label('Data Final'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Criação Inicial ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Criação Final ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ;
    }


    /**
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
