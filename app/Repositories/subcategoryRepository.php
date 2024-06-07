<?php

namespace App\Repositories;

use App\Models\subcategory;

class subcategoryRepository
{
    public function getAll()
    {
        return subcategory::all();
    }

    public function find($id)
    {
        return subcategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return subcategory::create($data);
    }

    public function update($id, array $data)
    {
        $model = subcategory::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = subcategory::findOrFail($id);
        $model->delete();
        return true;
    }
}