<?php

namespace App\Filament\Resources\Admin\Master;

use App\Filament\Resources\Admin\Master\InstitutionResource\Pages;
use App\Filament\Resources\Admin\Master\InstitutionResource\RelationManagers;
use App\Models\Admin\Master\Institution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Institusi';
    protected static ?string $modelLabel = 'Institusi';
    protected static ?string $pluralModelLabel = 'Institusi';
    protected static ?string $slug = 'institusi';
    protected static ?int $navigationSort = 999;

    public static function form(Form $form): Form
    {
        return $form->schema([


            Forms\Components\Section::make('Informasi Institusi')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Institusi')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\FileUpload::make('logo')
                        ->label('Unggah Logo')
                        ->directory('logos/institutions')
                        ->image()
                        ->maxSize(2048)
                        ->imageEditor()
                        ->imagePreviewHeight('180px')
                        ->helperText('Unggah logo institusi (maks. 2MB)'),

                    Forms\Components\TextInput::make('code')
                        ->label('Kode')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('email')
                        ->label('Email'),

                    Forms\Components\TextInput::make('phone')
                        ->label('Telepon'),

                    Forms\Components\Textarea::make('address')
                        ->label('Alamat'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])
                ->columns(2),
        ])
            ->columns(2)
        ;
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
                    ->width('80px'),

                // Tables\Columns\ImageColumn::make('logo')
                //     ->label('Logo')
                //     ->alignCenter()
                //     ->square()
                //     ->size(40),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'edit' => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }
}
