<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 2/18/2016
 * Time: 12:23 PM
 */
namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class AgentRepository extends Repository {

    public function model()
    {
        return 'App\Agent';
    }

}