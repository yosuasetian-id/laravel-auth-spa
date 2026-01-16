<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {

            $parts = parse_url($url);

            $verifyEmailUrl =
                'http://localhost:5173/verify-email'
                . '?id=' . $notifiable->getKey()
                . '&hash=' . sha1($notifiable->getEmailForVerification())
                . '&' . $parts['query'];

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->greeting('Hello ' . $notifiable->username)
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email', $verifyEmailUrl)
                ->line('This link will expire in 60 minutes.')
                ->line('If you did not create an account, no action required.');
        });
    }
}
