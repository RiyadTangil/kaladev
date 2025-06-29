<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ThemeSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = "settings";

    public function getFrontendLogoAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('theme-frontend-logo'))) {
            return asset($this->getFirstMediaUrl('theme-frontend-logo'));
        }
        return asset('images/theme/theme-logo.png');
    }

    public function getAdminLogoAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('theme-admin-logo'))) {
            return asset($this->getFirstMediaUrl('theme-admin-logo'));
        }
        return asset('images/theme/theme-logo.png');
    }

    public function getFaviconLogoAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('theme-favicon-logo'))) {
            return asset($this->getFirstMediaUrl('theme-favicon-logo'));
        }
        return asset('images/theme/theme-favicon-logo.png');
    }

    public function getFooterLogoAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('theme-footer-logo'))) {
            return asset($this->getFirstMediaUrl('theme-footer-logo'));
        }
        return asset('images/theme/theme-footer-logo.png');
    }
}