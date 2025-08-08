<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $modelLabel = 'Banner';
    
    protected static ?string $pluralModelLabel = 'Banners';
    
    protected static ?string $navigationGroup = 'Conteúdo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Banner')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3),
                        
                        Forms\Components\TextInput::make('link')
                            ->label('Link (opcional)')
                            ->url()
                            ->placeholder('https://exemplo.com'),
                    ]),
                
                Forms\Components\Section::make('Imagens do Banner')
                    ->description('Faça upload das imagens para diferentes dispositivos')
                    ->schema([
                        Forms\Components\FileUpload::make('imagem_desktop')
                            ->label('Imagem Desktop (1920x400px)')
                            ->image()
                            ->directory('banners')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048),
                        
                        Forms\Components\FileUpload::make('imagem_tablet')
                            ->label('Imagem Tablet (600x200px)')
                            ->image()
                            ->directory('banners')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048),
                        
                        Forms\Components\FileUpload::make('imagem_mobile')
                            ->label('Imagem Mobile (600x200px)')
                            ->image()
                            ->directory('banners')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048),
                    ])
                    ->columns(1),
                
                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('ativo')
                            ->label('Ativo')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('ordem')
                            ->label('Ordem de Exibição')
                            ->numeric()
                            ->default(0)
                            ->helperText('Números menores aparecem primeiro'),
                        
                        Forms\Components\DateTimePicker::make('data_inicio')
                            ->label('Data de Início (opcional)')
                            ->helperText('Deixe vazio para começar imediatamente'),
                        
                        Forms\Components\DateTimePicker::make('data_fim')
                            ->label('Data de Fim (opcional)')
                            ->helperText('Deixe vazio para não ter fim'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem_desktop')
                    ->label('Preview')
                    ->square()
                    ->size(60),
                
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ordem')
                    ->label('Ordem')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('data_inicio')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Imediato')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('data_fim')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sem fim')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('ordem', 'asc');
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
