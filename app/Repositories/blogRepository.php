<?php

namespace App\Repositories;

use App\Models\blog;

class blogRepository
{
    public function getAll()
    {
        return blog::all();
    }

    public function find($id)
    {
        return blog::findOrFail($id);
    }

    public function create(array $data)
    {
        return blog::create($data);
    }

    public function update($id, array $data)
    {
        $model = blog::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = blog::findOrFail($id);
        $model->delete();
        return true;
    }
}