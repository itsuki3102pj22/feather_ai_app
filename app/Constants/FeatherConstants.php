<?php

namespace App\Constants;

class FeatherConstants
{
    // 羽毛種
    const FEATHER_TYPES = [
        'ホワイトダック',
        'グレーダック',
        'ホワイトグース',
        'グレーグース',
    ];

    // 産地
    const ORIGINS = [
        '中国',
        'フランス',
        'ロシア',
        'イタリア',
        'ウクライナ',
        'ポーランド',
    ];

    // ダウン比率
    const DOWN_RATIOS = [50, 70, 75, 80, 85, 90, 93, 95];

    /**
     * 羽毛種バリデーションルール
     */
    public static function featherTypeRule(): string
    {
        return 'required|in:' . implode(',', self::FEATHER_TYPES);
    }

    /**
     * 産地バリデーションルール
     */
    public static function originRule(): string
    {
        return 'required|in:' . implode(',', self::ORIGINS);
    }

    /**
     * ダウン比率バリデーションルール
     */
    public static function downRatioRule(): string
    {
        return 'required|numeric|in:' . implode(',', self::DOWN_RATIOS);
    }
}
