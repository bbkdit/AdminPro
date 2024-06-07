<?php

namespace App\Repositories;

use App\Models\categories;

class categoriesRepository
{
    public function getAll()
    {
        return categories::all();
    }

    public function find($id)
    {
        return categories::findOrFail($id);
    }

    public function create(array $data)
    {
        return categories::create($data);
    }

    public function update($id, array $data)
    {
        $model = categories::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = categories::findOrFail($id);
        $model->delete();
        return true;
    }
}