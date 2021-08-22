<?php

namespace App;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CollectionExample
{
    public function example()
    {
        return $this->combine();
    }

    public function combine()
    {
        $keys =  collect(['benitto', 'feproniya']);
        return $keys->combine(['brother', 'sister']);
    }

    public function chunk()
    {
        return collect([1, 2, 3, 4, 5, 6, 7, 8])->chunk(3)->reverse();
    }

    public function collapse()
    {
        return collect([
            [1, 3, 4],
            [6, 6, 4],
            ['name' => ['beni' => ['age' => 22]]],
            [10, 20]
        ])->collapse();
    }

    public function median()
    {
        return collect([
            ['price' => 10000, 'tax' => 500, 'active' => true],
            ['price' => 25000, 'tax' => 700, 'active' => true],
            ['price' => 30000, 'tax' => 800, 'active' => false],
            ['price' => 30000, 'tax' => 800, 'active' => false],
        ])->median('price');
    }

    public function max()
    {
        return collect([
            ['price' => 10000, 'tax' => 500, 'active' => true],
            ['price' => 20000, 'tax' => 700, 'active' => true],
            ['price' => 30000, 'tax' => 800, 'active' => false],
        ])->max(function ($value) {
            if (!$value['active']) return null;
            return $value['price'] + $value['tax'];
        });
    }

    public function average()
    {
        return collect([
                ['price' => 10000, 'tax' => 500, 'active' => false],
                ['price' => 20000, 'tax' => 700, 'active' => false],
                ['price' => 30000, 'tax' => 900, 'active' => true],
            ]
        )->average(function ($value) {
            if (!$value['active']) return null;
            return $value['price'] + $value['tax'];
        });
    }

    public function toJson()
    {
        return collect(['product' => 'apples', 'price' => 45])->toJson(JSON_PRETTY_PRINT);
    }

    public function toArray()
    {
        return collect([
            collect([1, 2, 3, 4]),
            collect([1, 2, 3, 4]),
        ])->toArray();
    }

    public function times()
    {
        return Collection::times(3, function ($value) {
            return collect([1, 2, 3 => 'be ni']);
        })->toArray();
    }

    public function dump()
    {
        return collect([1, 2, 3, 4])->reverse()->dump()->map(function ($value) {
            return $value * 10;
        })->dump()->reverse()
            ->first();
    }

    // The difference between map and each is map return new collection but each only
    // helps to do some logic for each item like saving to db
    public function each()
    {
        return collect([
            ['banana', 42, 'california'],
            ['apple', 20, 'florida'],
            ['coconut', 50, 'Tessa'],
        ])->eachSpread(function ($product, $quanity, $location) {
            dump("we have {$quanity} {$product} in our {$location} store");
        });
    }

    public function filter()
    {
        return collect([1, 2, 3, 0, null, 6, 8, 10])->filter(function ($value) {
            return ($value % 2 == 0);
        });
    }

    public function unwrap()
    {
        return $this->mergeArray(collect([1, 2, 3, 4]), 'string', ['string', 2, 3], 'benitto');
    }

    public function mergeArray(...$arrays)
    {
        return collect($arrays)->flatMap(function ($item) {
            return Arr::wrap(Collection::unwrap($item));
        })->all();
    }
}