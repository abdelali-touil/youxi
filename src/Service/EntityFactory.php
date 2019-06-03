<?php
namespace App\Service;

class EntityFactory {


    public function bind(Request $request, Object $entity): EntityFactory {

        


        return $this;
    }

    public function create(): ?any {


        return new Object();
    }
}