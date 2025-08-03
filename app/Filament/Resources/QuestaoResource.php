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
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('pergunta')
                    ->label('Pergunta')
                    ->placeholder('Digite a pergunta da questão')
                    ->required()
                    ->rows(3),
                Forms\Components\Section::make('Alternativas')
                    ->description('Marque a checkbox ao lado da alternativa correta')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('alternativa_a')
                                    ->label('Alternativa A')
                                    ->placeholder('Digite a alternativa A')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_a')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'a')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'a');
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_b')
                                    ->label('Alternativa B')
                                    ->placeholder('Digite a alternativa B')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_b')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'b')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'b');
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_c')
                                    ->label('Alternativa C')
                                    ->placeholder('Digite a alternativa C')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_c')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'c')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'c');
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_d')
                                    ->label('Alternativa D')
                                    ->placeholder('Digite a alternativa D')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_d')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'd')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta', 'd');
                                        }
                                    }),
                            ])
                    ]),
                Forms\Components\Hidden::make('resposta_correta')
                    ->default('a'),
                Forms\Components\Textarea::make('explicacao')
                    ->label('Explicação (opcional)')
                    ->placeholder('Explique por que esta é a resposta correta')
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
                Tables\Columns\TextColumn::make('pergunta')
                    ->label('Pergunta')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria.nome')
                    ->label('Categoria')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('simulados_count')
                    ->label('Simulados')
                    ->counts('simulados')
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Apenas Ativas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
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
