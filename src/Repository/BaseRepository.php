<?php

namespace App\Repository;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BaseRepository extends DocumentRepository implements Paginable
{

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria, ?array $sort = null, $limit = null, $skip = null): array
    {
        return parent::findBy(array_merge(['isActive' => true], $criteria), $sort, $limit, $skip);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria): ?object
    {
        return parent::findOneBy(array_merge(['isActive' => true], $criteria));
    }

    /**
     * Bind params into entity
     * @param $entity
     * @param array $params
     * @param array $attributesToBind
     * @return mixed
     */
    public function bind($entity, array $params, array $attributesToBind = [])
    {
        foreach ($params as $field=>$value) {
            if (!in_array($field, $attributesToBind)) {
                continue;
            }
            if (method_exists($entity, Inflector::camelize("set_{$field}"))) {
                call_user_func_array([$entity, Inflector::camelize("set{$field}")], [$value]);
            }
        }

        return $entity;
    }


    /**
     * @inheritDoc
     */
    public function paginate(Request $request): ?Paginator
    {
        $sql = $this->createQueryBuilder()
            ->where(sprintf('isActive = %s', 1))
            ->setQueryArray($request->query->get('filter', []))
            ->sort($request->query->get('sort', 'id'), $request->request->get('direction', 'DESC'))
            ->skip($request->query->getInt('offset', 0))
            ->limit($request->query->getInt('limit', 20))
        ;

        return new Paginator($sql, $fetchJoinCollection = true);
    }
}