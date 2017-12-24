<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 2/18/2016
 * Time: 12:27 PM
 */
namespace App\Repositories\Criteria\Customer;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class CustomersByNameAscending extends Criteria {

    /**
     * @param $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply( $model, Repository $repository )
    {
        $model = $model->orderBy('name','ASC');
        return $model;
    }

}