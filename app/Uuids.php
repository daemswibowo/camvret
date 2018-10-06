<?php
namespace App;

trait Uuids
{

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model){
            $model->{$model->getKeyName()} = \Uuid::generate(4)->string;
        });

        static::created(function ($model){
            $model->{$model->getKeyName()} = $model->id;
        });
    }
}
