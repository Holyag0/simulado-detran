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

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Resultados Alunos';
    protected static ?string $modelLabel = 'Resultado Aluno';
    protected static ?string $pluralModelLabel = 'Resultados Alunos';
    protected static ?string $navigationGroup = 'Relatórios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Resultado')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Aluno')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Forms\Components\Select::make('simulado_id')
                            ->label('Simulado')
                            ->relationship('simulado', 'titulo')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pontuacao')
                                    ->label('Pontuação (%)')
                                    ->placeholder('0.00')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01),
                                
                                Forms\Components\TextInput::make('acertos')
                                    ->label('Acertos')
                                    ->placeholder('0')
                                    ->numeric()
                                    ->minValue(0),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('erros')
                                    ->label('Erros')
                                    ->placeholder('0')
                                    ->numeric()
                                    ->minValue(0),
                                
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'em_andamento' => 'Em Andamento',
                                        'finalizada' => 'Finalizada',
                                        'abandonada' => 'Abandonada',
                                    ])
                                    ->default('em_andamento')
                                    ->required(),
                            ]),
                        
                        Forms\Components\DateTimePicker::make('iniciado_em')
                            ->label('Iniciado em')
                            ->default(now()),
                        
                        Forms\Components\DateTimePicker::make('finalizado_em')
                            ->label('Finalizado em'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Aluno')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('simulado.titulo')
                    ->label('Simulado')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('pontuacao')
                    ->label('Pontuação (%)')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('acertos')
                    ->label('Acertos')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('erros')
                    ->label('Erros')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'em_andamento' => 'warning',
                        'finalizada' => 'success',
                        'abandonada' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'em_andamento' => 'Em Andamento',
                        'finalizada' => 'Finalizada',
                        'abandonada' => 'Abandonada',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('iniciado_em')
                    ->label('Iniciado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('finalizado_em')
                    ->label('Finalizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'em_andamento' => 'Em Andamento',
                        'finalizada' => 'Finalizada',
                        'abandonada' => 'Abandonada',
                    ]),
                
                Tables\Filters\SelectFilter::make('simulado_id')
                    ->label('Simulado')
                    ->relationship('simulado', 'titulo')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
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
