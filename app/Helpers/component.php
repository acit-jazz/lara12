<?php

use Illuminate\Support\Facades\Auth;

function components()
{
    if (Auth::check() && str(request()->path())->startsWith('backend')) {
        $components = [
            ['title' => 'Banner', 'value' => 'InputBanners'],
            ['title' => 'Card Slider', 'value' => 'InputCardSlider'],
            ['title' => 'Product Features', 'value' => 'InputProductFeatures'],
            ['title' => 'Latest News', 'value' => 'InputLatestNews'],
            ['title' => 'Call to Action', 'value' => 'InputCallToAction'],
            ['title' => 'Product Carousel', 'value' => 'InputProductCarousel'],
            ['title' => 'Two Colom', 'value' => 'InputTwoColom'],
            ['title' => 'Logo', 'value' => 'InputLogo'],
        ];
        return $components;
    }
    return [];

}
