<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * This constant is used for a pending order
     */
    const PENDING_STATUS = "1";

    /**
     * This constant is used for a approved order
     */
    const DECLINED_STATUS = "2";

    /**
     * This constant is used for a approved order
     */
    const APPROVED_STATUS = "3";

    /**
     * This constant is used for a order create type
     */
    const CREATE_TYPE = "create";

    /**
     * This constant is used for a order update type
     */
    const UPDATE_TYPE = "update";

    /**
     * This constant is used for a order update type
     */
    const DELETE_TYPE = "delete";

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => AsCollection::class,
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
