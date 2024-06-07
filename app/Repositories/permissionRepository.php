<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\BaseRepository;


class PermissionRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'name'
    ];


    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }


    public function model()
    {
        return Permission::class;
    }
}
