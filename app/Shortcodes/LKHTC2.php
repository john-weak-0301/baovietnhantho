<?php

namespace App\Shortcodes;

use Illuminate\Support\Str;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class LKHTC2
{
    public static $instance = 0;

    public function __invoke(ShortcodeInterface $shortcode)
    {
        if (static::$instance >= 1) {
            return;
        }

        static::$instance++;

        $plan = Str::slug($shortcode->getParameter('plan', 'huu-tri'));

        $plans = config('press.objectives');

        if (!array_key_exists($plan, $plans)) {
            $plan = 'giao-duc';
        }

        $isHuuTri = $plan === 'huu-tri';

        $listTargetAmounts = config('press.target_amounts.'.$plan)
            ?: config('press.target_amounts.default');

        return view(
            'shortcodes.lkhtc2',
            compact('plan', 'plans', 'isHuuTri', 'listTargetAmounts')
        );
    }
}
