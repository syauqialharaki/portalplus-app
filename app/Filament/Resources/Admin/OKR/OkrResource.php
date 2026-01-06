<?php

namespace App\Filament\Resources\Admin\OKR;

use App\Filament\Resources\Admin\OKR\OkrResource\Pages;
use App\Models\Admin\Master\Unit;
use App\Models\Admin\Master\User;
use App\Models\OKR\Okr;
use App\Models\OKR\Period;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OkrResource extends Resource
{
    protected static ?string $model = Okr::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'OKR';
    protected static ?string $navigationLabel = 'OKR';
    protected static ?string $modelLabel = 'OKR';
    protected static ?string $pluralModelLabel = 'OKR';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('institution_id')
                ->default(fn () => auth()->user()?->institution_id),

            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    Forms\Components\Section::make('OKR')
                        ->columnSpan(2)
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Judul')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('alignment_okr_id')
                                ->label('Align ke OKR lain')
                                ->searchable()
                                ->options(fn () => Okr::query()
                                    ->when(auth()->user()?->institution_id, fn ($q, $inst) => $q->where('institution_id', $inst))
                                    ->pluck('title', 'id'))
                                ->placeholder('Tidak ada'),
                        ]),

                    Forms\Components\Section::make('Meta')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\Select::make('period_id')
                                ->label('Periode OKR')
                                ->required()
                                ->searchable()
                                ->options(fn () => Period::query()
                                    ->when(auth()->user()?->institution_id, fn ($q, $inst) => $q->where('institution_id', $inst))
                                    ->pluck('name', 'id')),

                            Forms\Components\Fieldset::make('Owner')
                                ->schema([
                                    Forms\Components\Radio::make('owner_type')
                                        ->label('Tipe Owner')
                                        ->options([
                                            User::class => 'Individu',
                                            Unit::class => 'Unit',
                                        ])
                                        ->default(User::class)
                                        ->reactive(),

                                    Forms\Components\Select::make('owner_id')
                                        ->label('Owner')
                                        ->searchable()
                                        ->options(function (callable $get) {
                                            $ownerType = $get('owner_type') ?: User::class;
                                            $inst = auth()->user()?->institution_id;
                                            if ($ownerType === Unit::class) {
                                                return Unit::query()->when($inst, fn ($q) => $q->where('institution_id', $inst))->pluck('name', 'id');
                                            }
                                            return User::query()->when($inst, fn ($q) => $q->where('institution_id', $inst))->pluck('name', 'id');
                                        })
                                        ->required(),
                                ])
                                ->columns(1),

                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'pending_approval' => 'Menunggu Persetujuan',
                                    'active' => 'Aktif',
                                    'paused' => 'Ditunda',
                                    'completed' => 'Selesai',
                                    'archived' => 'Arsip',
                                ])
                                ->default('active'),

                            Forms\Components\Slider::make('confidence_score')
                                ->label('Confidence')
                                ->minValue(0)
                                ->maxValue(1)
                                ->step(0.05)
                                ->default(0.7)
                                ->helperText('0-1, gunakan slider untuk estimasi keyakinan'),

                            Forms\Components\TextInput::make('weight')
                                ->label('Bobot')
                                ->numeric()
                                ->minValue(0.1)
                                ->maxValue(10)
                                ->step(0.1)
                                ->default(1),
                        ]),
                ]),

            Forms\Components\Section::make('Key Results')
                ->columnSpanFull()
                ->schema([
                    Forms\Components\Repeater::make('keyResults')
                        ->relationship()
                        ->defaultItems(0)
                        ->orderable()
                        ->schema([
                            Forms\Components\Hidden::make('institution_id')
                                ->default(fn () => auth()->user()?->institution_id),

                            Forms\Components\TextInput::make('title')
                                ->label('Judul KR')
                                ->required(),

                            Forms\Components\Select::make('metric_type')
                                ->label('Jenis Metrik')
                                ->options([
                                    'number' => 'Number',
                                    'percentage' => 'Percentage',
                                    'binary' => 'Binary',
                                    'currency' => 'Currency',
                                ])
                                ->default('number'),

                            Forms\Components\TextInput::make('target')
                                ->label('Target')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('current')
                                ->label('Saat ini')
                                ->numeric()
                                ->default(0),

                            Forms\Components\TextInput::make('unit')
                                ->label('Unit')
                                ->maxLength(50),

                            Forms\Components\TextInput::make('weight')
                                ->label('Bobot')
                                ->numeric()
                                ->minValue(0.1)
                                ->maxValue(10)
                                ->step(0.1)
                                ->default(1),

                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'not_started' => 'Belum mulai',
                                    'on_track' => 'On track',
                                    'at_risk' => 'Berisiko',
                                    'off_track' => 'Off track',
                                    'done' => 'Selesai',
                                ])
                                ->default('on_track'),

                            Forms\Components\Slider::make('confidence_score')
                                ->label('Confidence')
                                ->minValue(0)
                                ->maxValue(1)
                                ->step(0.05)
                                ->default(0.7),

                            Forms\Components\DatePicker::make('due_date')
                                ->label('Jatuh tempo')
                                ->closeOnDateSelection(),
                        ])
                        ->columns(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('period.name')
                    ->label('Periode')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'info' => 'pending_approval',
                        'primary' => 'active',
                        'gray' => 'paused',
                        'success' => 'completed',
                        'secondary' => 'archived',
                    ]),

                Tables\Columns\TextColumn::make('confidence_score')
                    ->label('Confidence')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2)),

                Tables\Columns\TextColumn::make('owner_type')
                    ->label('Owner')
                    ->formatStateUsing(fn ($state) => class_basename($state)),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOkrs::route('/'),
            'create' => Pages\CreateOkr::route('/create'),
            'view' => Pages\ViewOkr::route('/{record}'),
            'edit' => Pages\EditOkr::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->check(), function ($query) {
                $query->where('institution_id', auth()->user()->institution_id);
            });
    }
}
