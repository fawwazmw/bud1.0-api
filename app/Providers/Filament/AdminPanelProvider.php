<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages; // Pastikan ini ada: use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets; // Pastikan ini ada: use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber, // Anda bisa mengganti warna tema
                // 'gray' => Color::Slate,
            ])
            ->brandName('IoT Dashboard Saya') // Nama Dashboard Anda
            // ->favicon(asset('images/custom-favicon.png')) // Jika punya favicon kustom

            // Menemukan Resources, Pages, dan Widgets secara otomatis dari direktori standar
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets') // Ini akan menemukan widget kustom kita

            ->pages([
                Pages\Dashboard::class, // Menggunakan halaman Dashboard bawaan Filament
            ])
            ->widgets([
                // KOSONGKAN array ini untuk menghapus widget global default seperti AccountWidget dan FilamentInfoWidget.
                // Widget kustom kita akan ditampilkan di Pages\Dashboard::class melalui discoverWidgets().
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
        // ->viteTheme('resources/css/filament/admin/theme.css'); // Jika Anda menggunakan Vite & custom theme
    }
}
