<?php

namespace App\Model\MasterData;

use App\MyModel;
use App\Traits\SearchTraits;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends MyModel
{
    use SearchTraits;
    use SoftDeletes;

    protected $table = 'master_customer_group';
    protected $columns = [
        'id' => [
            'searchable' => false,
            'search_relation' => false,
        ],
        'name' => [
            'searchable' => true,
            'search_relation' => false,
        ],
        'description' => [
            'searchable' => true,
            'search_relation' => false,
        ],
        'created_at' => [
            'searchable' => true,
            'search_relation' => false,
        ],
        'updated_at' => [
            'searchable' => true,
            'search_relation' => false,
        ],
        'created_by_name' => [
            'searchable' => true,
            'search_relation' => true,
            'relation_name' => 'create_by',
            'relation_field' => 'name',
        ],
        'updated_by_name' => [
            'searchable' => true,
            'search_relation' => true,
            'relation_name' => 'update_by',
            'relation_field' => 'name',
        ],
    ];

    public function Customer()
    {
        return $this->hasMany(Customer::class, 'id', 'customer_grup_id');
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
