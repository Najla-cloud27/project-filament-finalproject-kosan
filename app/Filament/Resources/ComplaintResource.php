<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

   protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Complaints';
    protected static ?string $pluralModelLabel = 'Complaints';
    protected static ?string $modelLabel = 'Complaint';

    // Form untuk create / edit
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('room_id')
                    ->label('Room')
                    ->relationship('room', 'room_number')
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Judul Keluhan')
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi Keluhan')
                    ->required(),

                FileUpload::make('image_url')
                    ->label('Bukti Foto')
                    ->directory('complaints')
                    ->image()
                    ->nullable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'dikirim' => 'Dikirim',
                        'diproses' => 'Diproses',
                        'ditolak'  => 'Ditolak',
                        'selesai'  => 'Selesai',
                    ])
                    ->required(),
            ]);
    }

    // Table untuk list di admin
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('image_url')
                    ->label('Foto')
                    ->square(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'dikirim',
                        'info'    => 'diproses',
                        'danger'  => 'ditolak',
                        'success' => 'selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // Pages CRUD
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }
}
