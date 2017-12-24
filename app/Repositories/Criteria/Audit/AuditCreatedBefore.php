<?php namespace App\Repositories\Criteria\Audit;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use DateTime;

class AuditCreatedBefore extends Criteria {

    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @param $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply( $model, Repository $repository )
    {
        $model = $model->where('created_at', '<', $this->date->format('Y-m-d'));
        return $model;
    }

}
