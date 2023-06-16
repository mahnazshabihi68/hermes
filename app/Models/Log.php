<?php


/******************************************************************************
 *                                                                            *
 *  This project is not free and has business trademarks.                     *
 *  Ali Khedmati | +989122958172 | Ali@khedmati.ir                            *
 *  Copyright (c)  2020-2022, Ali Khedmati Co.                                *
 *                                                                            *
 ******************************************************************************/

namespace App\Models;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends BaseModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array|string[]
     */
    protected $guarded = ['id'];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
