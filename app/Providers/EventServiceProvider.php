<?php

namespace App\Providers;

use App\Events\SendOrderStatusToWebhook;
use App\Listeners\SendOrderStatusToWebhookListener;
use App\Models\Item;
use App\Models\User;
use App\Events\SendSmsCode;
use App\Events\SendOrderSms;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Events\SendOrderGotSms;
use App\Observers\ItemObserver;
use App\Observers\UserObserver;
use App\Events\SendOrderGotMail;
use App\Events\SendOrderGotPush;
use App\Events\SendResetPassword;
use Illuminate\Auth\Events\Registered;
use App\Events\SendOrderDeliveryBoySms;
use App\Events\SendOrderDeliveryBoyMail;
use App\Events\SendOrderDeliveryBoyPush;
use App\Listeners\SendSmsCodeNotification;
use App\Listeners\SendOrderSmsNotification;
use App\Listeners\SendOrderMailNotification;
use App\Listeners\SendOrderPushNotification;
use App\Listeners\SendOrderGotSmsNotification;
use App\Listeners\SendOrderGotMailNotification;
use App\Listeners\SendOrderGotPushNotification;
use App\Listeners\SendResetPasswordNotification;
use App\Listeners\SendOrderDeliveryBoySmsNotification;
use App\Listeners\SendOrderDeliveryBoyMailNotification;
use App\Listeners\SendOrderDeliveryBoyPushNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class               => [
            SendEmailVerificationNotification::class
        ],
        SendResetPassword::class        => [
            SendResetPasswordNotification::class
        ],
        SendSmsCode::class              => [
            SendSmsCodeNotification::class
        ],
        SendOrderMail::class            => [
            SendOrderMailNotification::class
        ],
        SendOrderSms::class             => [
            SendOrderSmsNotification::class
        ],
        SendOrderPush::class            => [
            SendOrderPushNotification::class
        ],
        SendOrderDeliveryBoyMail::class => [
            SendOrderDeliveryBoyMailNotification::class
        ],
        SendOrderDeliveryBoySms::class  => [
            SendOrderDeliveryBoySmsNotification::class
        ],
        SendOrderDeliveryBoyPush::class => [
            SendOrderDeliveryBoyPushNotification::class
        ],
        SendOrderGotMail::class         => [
            SendOrderGotMailNotification::class
        ],
        SendOrderGotSms::class         => [
            SendOrderGotSmsNotification::class
        ],
        SendOrderGotPush::class         => [
            SendOrderGotPushNotification::class
        ],
        SendOrderStatusToWebhook::class => [
            SendOrderStatusToWebhookListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Item::observe(ItemObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
