<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingResource\Pages;
use App\Models\Bill;
use App\Models\User;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;

class BillingResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Manajemen Keuangan';
    protected static ?string $navigationLabel = 'Billing / Tagihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Tagihan')
                    ->schema([

                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('booking_id')
                            ->label('Booking')
                            ->relationship('booking', 'booking_code')
                            ->searchable()
                            ->nullable(),

                        TextInput::make('bill_code')
                            ->label('Kode Tagihan')
                            ->placeholder('BILL-XYZ123')
                            ->required(),

                        TextInput::make('total_amount')
                            ->label('Total Pembayaran')
                            ->numeric()
                            ->prefix('Rp ')
                            ->required(),

                        TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->placeholder('Transfer Bank, Dana, Gopay, dll')
                            ->nullable(),

                        TextInput::make('payment_gateway')
                            ->label('Payment Gateway')
                            ->placeholder('Midtrans, Xendit, Manual')
                            ->nullable(),

                        Select::make('status')
                            ->label('Status Pembayaran')
                            ->options([
                                'belum_dibayar' => 'Belum Dibayar',
                                'verifikasi_tertunda' => 'Verifikasi Tertunda',
                                'dibayar' => 'Dibayar',
                                'terlambat' => 'Terlambat',
                            ])
                            ->default('belum_dibayar')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bill_code')->label('Kode Tagihan')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('booking.booking_code')->label('Kode Booking')->searchable(),
                Tables\Columns\TextColumn::make('total_amount')->money('IDR'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'belum_dibayar',
                        'info' => 'verifikasi_tertunda',
                        'success' => 'dibayar',
                        'danger' => 'terlambat',
                    ])
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBillings::route('/'),
            'create' => Pages\CreateBilling::route('/create'),
            'edit' => Pages\EditBilling::route('/{record}/edit'),
        ];
    }
}
