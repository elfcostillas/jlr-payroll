<?php

namespace App\Mappers\Frontiers;

interface MapperInterface {

    public function insertValid(array $request);

    public function updateValid(array $request);

    public function destroy(array $request);

}