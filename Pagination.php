<?php

namespace Tiloweb\PaginationBundle;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Pagination
{
    /**
     * @param QueryBuilder $query
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public static function paginate($dql, int $page = 1, int $max = 10) {
        $query = $dql->getQuery();

        $query->setFirstResult(($page - 1) * $max);
        $query->setMaxResults($max);

        return new Paginator($query);
    }
}
