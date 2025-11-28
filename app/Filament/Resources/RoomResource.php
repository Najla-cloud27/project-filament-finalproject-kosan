<?php
namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Room;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    protected static ?string $model           = Room::class;
    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Rooms';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Section::make('Informasi Kamar')
                    ->description('Lengkapi detail kamar dengan benar.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->label('Nama Kamar')
                                ->helperText('Masukkan nama kamar, misal: Kamar Deluxe'),

                            Textarea::make('description')
                                ->label('Deskripsi Kamar')
                                ->helperText('Deskripsikan kamar secara singkat'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('price')
                                ->numeric()
                                ->required()
                                ->label('Harga')
                                ->helperText('Masukkan harga kamar dalam angka'),

                            TextInput::make('size')
                                ->required()
                                ->label('Ukuran')
                                ->helperText('Masukkan ukuran kamar, misal: 3x4 m'),
                        ]),
                    ]),

                Section::make('Status & Fasilitas')
                    ->description('Pilih status kamar dan fasilitas yang tersedia.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options([
                                    'tersedia'      => 'Tersedia',
                                    'terisi'        => 'Terisi',
                                    'pemeliharaan'  => 'Pemeliharaan',
                                    'sudah_dipesan' => 'Sudah Dipesan',
                                ])
                                ->required()
                                ->label('Status Kamar'),

                            MultiSelect::make('fasilitas')
                                ->label('Fasilitas Kamar')
                                ->options([
                                    'ac'     => 'AC',
                                    'tv'     => 'TV',
                                    'wifi'   => 'WiFi',
                                    'kulkas' => 'Kulkas',
                                    'lemari' => 'Lemari',
                                    'kursi'  => 'Kursi',
                                    'meja'   => 'Meja',
                                    'kasur'  => 'Kasur',
                                ])
                                ->placeholder('Pilih fasilitas')
                                ->helperText('Pilih fasilitas yang tersedia di kamar'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('stok')
                                ->options([
                                    'tersedia'       => 'Tersedia',
                                    'tidak_tersedia' => 'Tidak Tersedia',
                                ])
                                ->required()
                                ->label('Stok'),
                        ]),
                    ]),

                Section::make('Gambar & Icon')
                    ->description('Unggah gambar kamar dan icon jika diperlukan.')
                    ->schema([
                        FileUpload::make('main_image_url')
                            ->label('Gambar Kamar')
                            ->disk('public')
                            ->image()
                            ->maxSize(4096)
                            ->required()
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file): string {
                                $name = $file->hashName();
                                $file->storeAs('rooms', $name, 'public');
                                return $name;
                            })
                            ->helperText('Ukuran gambar maksimal 4MB'),

                        TextInput::make('icon_svg')
                            ->label('Icon SVG')
                            ->helperText('Masukkan kode SVG jika ada (opsional)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Kamar')->sortable(),
                TextColumn::make('status')->label('Status'),
                TextColumn::make('description')->label('Deskripsi'),
                BadgeColumn::make('fasilitas')
                    ->label('Fasilitas')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            return is_array($decoded) ? implode(', ', $decoded) : $state;
                        }
                        return is_array($state) ? implode(', ', $state) : '';
                    }),
                ImageColumn::make('main_image_url')
                    ->label('Gambar Makanan')
                    ->getStateUsing(fn($record) => asset('storage/rooms/' . $record->main_image_url))
                    ->square()
                    ->width(60)
                    ->height(60), TextColumn::make('icon_svg')->label('Icon SVG'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit'   => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
