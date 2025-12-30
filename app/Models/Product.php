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

    /**
     * TEMP PROPERTY (tidak akan disimpan DB)
     */
    protected array $oldAttributesForLog = [];

    protected static function booted(): void
    {
        // CREATE
        static::created(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            ExternalLogger::send('DATA_CREATE', [
                'user_id' => Auth::id(),
                'data'    => [
                    'resource'     => 'product',
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'sku'          => $product->sku,
                    'price'        => $product->price,
                    'stock'        => $product->stock,
                    'description'  => $product->description,
                ],
                'ip' => request()?->ip(),
            ]);
        });

        // UPDATE -> WAJIB before & after
        static::updating(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            // simpan data lama sebelum update (ke property biasa)
            $product->oldAttributesForLog = $product->getOriginal();
        });

        static::updated(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            $before = $product->oldAttributesForLog ?: $product->getOriginal();
            $after  = $product->getAttributes();

            ExternalLogger::send('DATA_UPDATE', [
                'user_id' => Auth::id(),
                'before'  => array_merge(['resource' => 'product'], $before),
                'after'   => array_merge(['resource' => 'product'], $after),
                'ip'      => request()?->ip(),
            ]);
        });

        // DELETE
        static::deleted(function (Product $product) {
            if (app()->runningInConsole()) {
                return;
            }

            ExternalLogger::send('DATA_DELETE', [
                'user_id' => Auth::id(),
                'id'      => $product->id, // wajib sesuai rules
                'ip'      => request()?->ip(),
                'resource'=> 'product',
                'name'    => $product->name,
                'sku'     => $product->sku,
            ]);
        });
    }
}
