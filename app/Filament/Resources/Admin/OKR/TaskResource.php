<?php

namespace App\Filament\Resources\Admin\OKR;

use App\Filament\Resources\Admin\OKR\TaskResource\Pages;
use App\Models\Admin\Master\User;
use App\Models\OKR\KeyResult;
use App\Models\OKR\Okr;
use App\Models\OKR\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'OKR';
    protected static ?string $navigationLabel = 'Task';
    protected static ?string $modelLabel = 'Task';
    protected static ?string $pluralModelLabel = 'Tasks';
    protected static ?int $navigationSort = 20;

    public static function statusOptions(): array
    {
        return [
            'backlog' => 'Backlog',
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'blocked' => 'Blocked',
            'done' => 'Done',
            'cancelled' => 'Cancelled',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('institution_id')
                ->default(fn () => auth()->user()?->institution_id),

            Forms\Components\TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->columnSpanFull(),

            Forms\Components\Select::make('okr_id')
                ->label('OKR')
                ->searchable()
                ->options(fn () => Okr::query()
                    ->when(auth()->user()?->institution_id, fn ($q, $inst) => $q->where('institution_id', $inst))
                    ->pluck('title', 'id'))
                ->reactive(),

            Forms\Components\Select::make('key_result_id')
                ->label('Key Result')
                ->searchable()
                ->options(fn (callable $get) => KeyResult::query()
                    ->when($get('okr_id'), fn ($q, $okr) => $q->where('okr_id', $okr))
                    ->pluck('title', 'id')),

            Forms\Components\Select::make('assignee_id')
                ->label('Penanggung jawab')
                ->searchable()
                ->options(fn () => User::query()
                    ->when(auth()->user()?->institution_id, fn ($q, $inst) => $q->where('institution_id', $inst))
                    ->pluck('name', 'id')),

            Forms\Components\DatePicker::make('due_date')
                ->label('Jatuh tempo')
                ->closeOnDateSelection(),

            Forms\Components\Select::make('priority')
                ->label('Prioritas')
                ->options([
                    'low' => 'Rendah',
                    'medium' => 'Sedang',
                    'high' => 'Tinggi',
                    'critical' => 'Kritis',
                ])
                ->default('medium'),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options(self::statusOptions())
                ->default('todo'),

            Forms\Components\TextInput::make('progress')
                ->label('Progress (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),

            Forms\Components\Toggle::make('is_blocked')
                ->label('Terblokir?')
                ->inline(false)
                ->default(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('okr.title')
                    ->label('OKR')
                    ->limit(30),

                Tables\Columns\TextColumn::make('keyResult.title')
                    ->label('Key Result')
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->colors([
                        'success' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'critical',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'backlog',
                        'secondary' => 'todo',
                        'info' => 'in_progress',
                        'danger' => 'blocked',
                        'success' => 'done',
                        'warning' => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh tempo')
                    ->date(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'board' => Pages\TaskBoard::route('/board'),
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
