<?php

namespace App\Traits\Entity;

use App\Entity\Client;

trait CurrentClientTrait
{
    public function setCurrentClient(Client $object): self
    {
        if (method_exists($this, 'setClient')) {
            $this->setClient($object);
        }

        return $this;
    }
}