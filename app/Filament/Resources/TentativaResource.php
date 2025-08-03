<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TentativaResource\Pages;
use App\Filament\Resources\TentativaResource\RelationManagers;
use App\Models\Tentativa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TentativaResource extends Resource
{
    protected static ?string $model = Tentativa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tentativas';
    protected static ?string $modelLabel = 'Tentativa';
    protected static ?string $pluralModelLabel = 'Tentativas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionadas'),
                ]),
            ]);
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
            'index' => Pages\ListTentativas::route('/'),
            'create' => Pages\CreateTentativa::route('/create'),
            'edit' => Pages\EditTentativa::route('/{record}/edit'),
        ];
    }
}
