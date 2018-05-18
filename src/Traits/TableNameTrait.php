<?php

namespace ITCAN\LaravelHelpers\Traits;

trait TableNameTrait
{
    /**
     * Return table name of model
     *
     * @return string
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
