<?php

namespace App\Service;

class Hydrate
{
    public function useHydrate(array $data): void
    {
        foreach ($data as $property => $value) {
            $setter = 'set' . ucfirst($property);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }
}

