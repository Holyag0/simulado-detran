<?php

namespace App\Filament\Resources\SimuladoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestoesRelationManager extends RelationManager
{
    protected static string $relationship = 'questoes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('pergunta')
                    ->label('Pergunta')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('alternativa_a')
                    ->label('Alternativa A')
                    ->required(),
                Forms\Components\TextInput::make('alternativa_b')
                    ->label('Alternativa B')
                    ->required(),
                Forms\Components\TextInput::make('alternativa_c')
                    ->label('Alternativa C')
                    ->required(),
                Forms\Components\TextInput::make('alternativa_d')
                    ->label('Alternativa D')
                    ->required(),
                Forms\Components\Select::make('resposta_correta')
                    ->label('Resposta Correta')
                    ->options([
                        'a' => 'A',
                        'b' => 'B',
                        'c' => 'C',
                        'd' => 'D',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('explicacao')
                    ->label('Explicação (opcional)')
                    ->rows(2),
                Forms\Components\Toggle::make('ativo')
                    ->label('Ativa')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pergunta')
                    ->label('Pergunta')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('alternativa_a')->label('A'),
                Tables\Columns\TextColumn::make('alternativa_b')->label('B'),
                Tables\Columns\TextColumn::make('alternativa_c')->label('C'),
                Tables\Columns\TextColumn::make('alternativa_d')->label('D'),
                Tables\Columns\TextColumn::make('resposta_correta')
                    ->label('Correta')
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativa')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
