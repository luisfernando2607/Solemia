<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    protected $table = 'restaurant_settings';

    protected $fillable = [
        'trade_name', 'legal_name', 'ruc', 'address', 'phone', 'email',
        'logo_path', 'currency', 'timezone', 'date_format', 'decimal_separator',
        'tax_rate', 'service_charge_active', 'service_charge_rate',
        'tip_suggestions', 'sri_environment', 'sri_certificate_path',
        'sri_certificate_pass', 'sri_taxpayer_type',
        'kds_alert_minutes', 'session_timeout_json',
    ];

    protected function casts(): array
    {
        return [
            'tax_rate' => 'decimal:2',
            'service_charge_rate' => 'decimal:2',
            'tip_suggestions' => 'json',
            'session_timeout_json' => 'json',
            'service_charge_active' => 'boolean',
        ];
    }

    private static ?self $cached = null;

    public static function current(): self
    {
        if (self::$cached === null) {
            self::$cached = self::first() ?? self::factory()->make();
        }
        return self::$cached;
    }

    public static function flushCache(): void
    {
        self::$cached = null;
    }
}
