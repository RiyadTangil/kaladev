<?php

namespace App\Services;


use App\Http\Requests\ThemeRequest;
use App\Models\ThemeSetting;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class ThemeService
{
    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('theme')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ThemeRequest $request)
    {

        try {
            Settings::group('theme')->set($request->validated());
            if ($request->theme_frontend_logo) {
                $setting = ThemeSetting::where('key', 'theme_frontend_logo')->first();
                $setting->clearMediaCollection('theme-frontend-logo');
                $setting->addMediaFromRequest('theme_frontend_logo')->toMediaCollection('theme-frontend-logo');
            }
            if ($request->theme_admin_logo) {
                $setting = ThemeSetting::where('key', 'theme_admin_logo')->first();
                $setting->clearMediaCollection('theme-admin-logo');
                $setting->addMediaFromRequest('theme_admin_logo')->toMediaCollection('theme-admin-logo');
            }
            if ($request->theme_favicon_logo) {
                $setting = ThemeSetting::where('key', 'theme_favicon_logo')->first();
                $setting->clearMediaCollection('theme-favicon-logo');
                $setting->addMediaFromRequest('theme_favicon_logo')->toMediaCollection('theme-favicon-logo');
            }
            if ($request->theme_footer_logo) {
                $setting = ThemeSetting::where('key', 'theme_footer_logo')->first();
                $setting->clearMediaCollection('theme-footer-logo');
                $setting->addMediaFromRequest('theme_footer_logo')->toMediaCollection('theme-footer-logo');
            }
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}