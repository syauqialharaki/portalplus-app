<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.profile';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $title = 'Profil Saya';
    protected static ?string $slug = 'profile';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Foto Profil')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Foto')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('avatars')
                            ->maxSize(2048)
                            ->helperText('Upload foto profil (maks 2MB)'),
                    ]),

                Forms\Components\Section::make('Informasi Pribadi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true, table: 'users', column: 'email')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(20),
                    ]),

                Forms\Components\Section::make('Ubah Password')
                    ->description('Kosongkan jika tidak ingin mengubah password')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrated(false),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        // Validate current password if changing password
        if (! empty($data['new_password'])) {
            if (empty($data['current_password'])) {
                Notification::make()
                    ->title('Password saat ini harus diisi')
                    ->danger()
                    ->send();
                return;
            }

            if (! Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Password saat ini salah')
                    ->danger()
                    ->send();
                return;
            }

            $user->password = $data['new_password'];
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->avatar = $data['avatar'] ?? null;
        $user->save();

        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }
}
