<?php

if (!function_exists('getPlatformIcon')) {

    function getPlatformIcon($p) {
        $icons = [
            'twitter'   => "M23.953 4.57a10 ...",
            'facebook'  => "M22.676 0H1.324 ...",
            'instagram' => "M12 2.163c3.204 ...",
            'linkedin'  => "M20.447 20.452h ...",
            'youtube'   => "M23.498 6.186a3.016 ...",
            'tiktok'    => "M12.525.02c1.31-.02 ..."
        ];

        return $icons[$p] ?? '';
    }

}

if (!function_exists('bio_domain')) {

    function bio_domain()
    {
        return rtrim(config('app.url'), '/');
    }

}
