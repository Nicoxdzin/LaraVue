<?php

namespace App\Traits;

trait FillsAttributes
{
    public function fill(array $attributes): self
    {
        foreach ($attributes as $attribute => $value) {
            if (!property_exists($this, $attribute)) {
                continue;
            }

            $this->{$attribute} = $value;
        }

        return $this;
    }
}
