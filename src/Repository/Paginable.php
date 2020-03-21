<?php

namespace App\Repository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface Paginable
{

    /**
     * Query with pagination feature
     * @param Request $request
     * @return mixed
     */
    public function paginate(Request $request): ?Paginator;
}