<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        //Verificacion de email de registro
        VerifyEmail::$toMailCallback = function ($notifiable, $verificationUrl) {
            return (new MailMessage)
            ->subject(Lang::get('Verificación de correo electrónico'))
            ->line(Lang::get('Haz clic en el botón de abajo para verificar tu dirección de correo electrónico.'))
            ->action(Lang::get('Verificar mi correo'), $verificationUrl)
            ->line(Lang::get('Si no creaste una cuenta en paul.teran.com, no es necesario realizar ninguna otra acción.'));
        };

        VerifyEmail::$createUrlCallback = function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 15)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        };
    }


}
