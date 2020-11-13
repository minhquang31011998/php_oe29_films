<?php
namespace App\Repositories\Type;

interface TypeRepositoryInterface
{
    public function getTypes($request);

    public function storeType($request);

    public function updateType($request, $typeId);

    public function deleteType($typeId);

    public function getCountOfMovieFromType();
}
