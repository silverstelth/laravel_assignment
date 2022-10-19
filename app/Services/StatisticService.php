<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Item;

class StatisticService
{
    public function get($option)
    {
        if ($option == 'count') {
            return Item::count();

        } else if ($option == 'average') {
            return Item::avg('price');

        } else if ($option == 'website') {
            $maxPriceItem = Item::orderBy('price', 'desc')->first();
            return $maxPriceItem->url;

        } else if ($option == 'total') {
            return Item::whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                ->whereDate('created_at', '<', Carbon::now()->endOfMonth())
                ->sum('price');
        }

        return false;
    }
}