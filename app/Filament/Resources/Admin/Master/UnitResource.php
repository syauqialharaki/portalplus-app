<?php

namespace App\Filament\Resources\Admin\Master;

use App\Filament\Resources\Admin\Master\UnitResource\Pages;
use App\Filament\Resources\Admin\Master\UnitResource\RelationManagers;
use App\Models\Admin\Master\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Satuan Kerja';
    protected static ?string $modelLabel = 'Satuan Kerja';
    protected static ?string $pluralModelLabel = 'Satuan Kerja';
    protected static ?string $slug = 'satuan-kerja';
    protected static ?int $navigationSort = 1000;

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Satuan Kerja')
                ->schema([
                    Forms\Components\Select::make('institution_id')
                        ->label('Institusi')
                        ->relationship('institution', 'name')
                        ->searchable()
                        ->required()
                        ->placeholder('Pilih Institusi'),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Satuan Kerja')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('code')
                        ->label('Kode')
                        ->maxLength(20),

                    Forms\Components\Select::make('parent_id')
                        ->label('Induk Unit')
                        ->relationship('parent', 'name')
                        ->searchable()
                        ->placeholder('Tidak ada induk'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(false)
                    ->alignCenter()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Induk Unit')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('institution.code')
                    ->label('Institusi')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                Tables\Filters\SelectFilter::make('institution_id')
                    ->label('Institusi')
                    ->relationship('institution', 'name')
                    ->searchable(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Contoh log, jika ingin mencatat penghapusan
                        // activity()->causedBy(auth()->user())->performedOn($record)->log('Menghapus Unit');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Satuan Kerja')
                    ->modalDescription(fn($record) => "Yakin ingin menghapus unit {$record->name}?")
                    ->modalSubmitActionLabel('Ya, hapus')
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                // Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    // 🔗 RELASI (opsional, kosongkan dulu)
    public static function getRelations(): array
    {
        return [];
    }

    // 📄 HALAMAN
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
