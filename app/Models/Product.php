<?php

namespace App\Models;

use App\Services\ExternalLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'description',
    ];

    protected static function booted(): void
    {
        // CREATE
        static::created(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            ExternalLogger::send('product.created', [
                'user_id' => Auth::id(),
                'data'    => [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'sku'         => $product->sku,
                    'price'       => $product->price,
                    'stock'       => $product->stock,
                    'description' => $product->description,
                ],
            ]);
        });

        // UPDATE → WAJIB before & after
        static::updating(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            // simpan data lama sebelum diupdate
            $product->old_attributes_for_log = $product->getOriginal();
        });

        static::updated(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            $before = $product->old_attributes_for_log ?? $product->getOriginal();
            $after  = $product->getAttributes();

            ExternalLogger::send('product.updated', [
                'user_id' => Auth::id(),
                'before'  => $before,
                'after'   => $after,
            ]);
        });

        // DELETE
        static::deleted(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            ExternalLogger::send('product.deleted', [
                'user_id' => Auth::id(),
                'id'      => $product->id,
                'name'    => $product->name,
                'sku'     => $product->sku,
            ]);
        });
    }
}
