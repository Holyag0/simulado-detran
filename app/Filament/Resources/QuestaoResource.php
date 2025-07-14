<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestaoResource\Pages;
use App\Filament\Resources\QuestaoResource\RelationManagers;
use App\Models\Questao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestaoResource extends Resource
{
    protected static ?string $model = Questao::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Questões';
    protected static ?string $modelLabel = 'Questão';
    protected static ?string $pluralModelLabel = 'Questões';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('simulado_id')
                    ->label('Simulado')
                    ->relationship('simulado', 'titulo')
                    ->required(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('simulado.titulo')
                    ->label('Simulado')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('simulado_id')
                    ->label('Simulado')
                    ->relationship('simulado', 'titulo'),
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Status')
                    ->placeholder('Todas')
                    ->trueLabel('Ativas')
                    ->falseLabel('Inativas'),
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

    public static function getRelations(): array
    {
        return [
            // Você pode adicionar RelationManagers aqui se quiser ver respostas da questão, por exemplo
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestaos::route('/'),
            'create' => Pages\CreateQuestao::route('/create'),
            'edit' => Pages\EditQuestao::route('/{record}/edit'),
        ];
    }
}
