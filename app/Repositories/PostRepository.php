<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function getAll()
    {
        return Post::all();
    }

    public function find($id)
    {
        return Post::findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update($id, array $data)
    {
        $model = Post::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = Post::findOrFail($id);
        $model->delete();
        return true;
    }
}