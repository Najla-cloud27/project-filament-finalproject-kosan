<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Manajemen Booking';
    protected static ?string $navigationLabel = 'Data Booking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Booking')
                    ->schema([

                        Select::make('user_id')
                            ->label('Penyewa')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('room_id')
                            ->label('Kamar')
                            ->relationship('room', 'name')
                            ->searchable()
                            ->required(),

                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->default('BK-' . strtoupper(uniqid()))
                            ->required(),

                        TextInput::make('duration_in_months')
                            ->label('Durasi (bulan)')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        DatePicker::make('planned_check_in_date')
                            ->label('Rencana Check In')
                            ->required(),

                        DatePicker::make('selesai_booking')
                            ->label('Selesai Booking')
                            ->nullable(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pembayaran_tertunda' => 'Pembayaran Tertunda',
                                'dikonfirmasi' => 'Dikonfirmasi',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('pembayaran_tertunda')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('room.name')
                    ->label('Kamar')
                    ->searchable(),

                Tables\Columns\TextColumn::make('duration_in_months')
                    ->label('Durasi (bulan)'),


                Tables\Columns\TextColumn::make('planned_check_in_date')
                    ->label('Rencana Check In')
                    ->date(),

                Tables\Columns\TextColumn::make('selesai_booking')
                    ->label('Selesai Booking')
                    ->date(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pembayaran_tertunda',
                        'success' => 'dikonfirmasi',
                        'info'    => 'selesai',
                        'danger'  => 'dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
