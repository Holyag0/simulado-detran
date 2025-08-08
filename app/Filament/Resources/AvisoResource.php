<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvisoResource\Pages;
use App\Models\Aviso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AvisoResource extends Resource
{
    protected static ?string $model = Aviso::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Avisos';
    protected static ?string $modelLabel = 'Aviso';
    protected static ?string $pluralModelLabel = 'Avisos';
    protected static ?string $navigationGroup = 'Comunicação';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Digite o título do aviso'),
                        
                        Forms\Components\RichEditor::make('conteudo')
                            ->label('Conteúdo')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Digite o conteúdo do aviso'),
                        
                        Forms\Components\Select::make('tipo')
                            ->label('Tipo')
                            ->options([
                                'informacao' => 'Informação',
                                'aviso' => 'Aviso',
                                'erro' => 'Erro',
                                'sucesso' => 'Sucesso',
                            ])
                            ->default('informacao')
                            ->required(),
                        
                        Forms\Components\Select::make('prioridade')
                            ->label('Prioridade')
                            ->options([
                                'baixa' => 'Baixa',
                                'media' => 'Média',
                                'alta' => 'Alta',
                            ])
                            ->default('media')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Configurações de Exibição')
                    ->schema([
                        Forms\Components\Toggle::make('ativo')
                            ->label('Ativo')
                            ->default(true)
                            ->helperText('Se desativado, o aviso não será exibido'),
                        
                        Forms\Components\Toggle::make('mostrar_popup')
                            ->label('Mostrar como Pop-up')
                            ->default(false)
                            ->helperText('Se ativado, o aviso será exibido como pop-up para os usuários'),
                        
                        Forms\Components\Select::make('destinatarios')
                            ->label('Destinatários')
                            ->options([
                                'todos' => 'Todos os usuários',
                                'admin' => 'Apenas Administradores',
                                'aluno' => 'Apenas Alunos',
                            ])
                            ->multiple()
                            ->default(['todos'])
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Período de Exibição')
                    ->schema([
                        Forms\Components\DateTimePicker::make('data_inicio')
                            ->label('Data de Início')
                            ->nullable()
                            ->helperText('Deixe em branco para exibir imediatamente'),
                        
                        Forms\Components\DateTimePicker::make('data_fim')
                            ->label('Data de Fim')
                            ->nullable()
                            ->helperText('Deixe em branco para exibir indefinidamente'),
                    ])->columns(2),

                Forms\Components\Section::make('Personalização')
                    ->schema([
                        Forms\Components\ColorPicker::make('cor_fundo')
                            ->label('Cor de Fundo')
                            ->nullable()
                            ->helperText('Cor de fundo do pop-up (opcional)'),
                        
                        Forms\Components\ColorPicker::make('cor_texto')
                            ->label('Cor do Texto')
                            ->nullable()
                            ->helperText('Cor do texto do pop-up (opcional)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'informacao' => 'info',
                        'aviso' => 'warning',
                        'erro' => 'danger',
                        'sucesso' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('prioridade')
                    ->label('Prioridade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baixa' => 'success',
                        'media' => 'warning',
                        'alta' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('mostrar_popup')
                    ->label('Pop-up')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('destinatarios')
                    ->label('Destinatários')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return collect($state)->map(fn($item) => ucfirst($item))->implode(', ');
                        }
                        return ucfirst($state);
                    }),
                
                Tables\Columns\TextColumn::make('data_inicio')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('data_fim')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'informacao' => 'Informação',
                        'aviso' => 'Aviso',
                        'erro' => 'Erro',
                        'sucesso' => 'Sucesso',
                    ]),
                
                Tables\Filters\SelectFilter::make('prioridade')
                    ->label('Prioridade')
                    ->options([
                        'baixa' => 'Baixa',
                        'media' => 'Média',
                        'alta' => 'Alta',
                    ]),
                
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Apenas Ativos'),
                
                Tables\Filters\TernaryFilter::make('mostrar_popup')
                    ->label('Apenas Pop-ups'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAvisos::route('/'),
            'create' => Pages\CreateAviso::route('/create'),
            'edit' => Pages\EditAviso::route('/{record}/edit'),
        ];
    }
} 