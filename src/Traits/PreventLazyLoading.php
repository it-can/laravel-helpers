<?php

namespace ITCAN\LaravelHelpers\Traits;

trait PreventLazyLoading
{
    /**
     * Get a relationship value from a method.
     *
     * @param  string  $method
     * @return mixed
     *
     * @throws \LogicException
     */
    protected function getRelationshipFromMethod($method)
    {
        $whitelist = $this->allowedLazyRelations ?? [];

        if ($this->exists and ! in_array($method, $whitelist)) {
            $model = get_class($this);

            throw new Exception("Tried to load {$method} in {$model}");
        }

        return parent::getRelationshipFromMethod($method);
    }
}
