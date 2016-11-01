<?php

namespace ZF\Apigility\Doctrine\Server\Paginator\Adapter;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Class DoctrineOrmAdapter
 *
 * @package ZF\Apigility\Doctrine\Server\Paginator\Adapter
 */
class DoctrineOrmAdapter extends Paginator implements AdapterInterface
{
    public $cache = array();
    /**
     * @param $offset
     * @param $itemCountPerPage
     *
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        if (array_key_exists($offset, $this->cache)
            && array_key_exists($itemCountPerPage, $this->cache[$offset])
        ) {
            return $this->cache[$offset][$itemCountPerPage];
        }

        $this->getQuery()->setFirstResult($offset);
        $this->getQuery()->setMaxResults($itemCountPerPage);

        /**
         * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html#first-and-max-result-items-dql-query-only
         */
        if (strstr($this->getQuery()->getDql(), 'INNER JOIN')) {
            $this->getQuery()->setMaxResults(null);
        }

        if (! array_key_exists($offset, $this->cache)) {
            $this->cache[$offset] = [];
        }

        $this->cache[$offset][$itemCountPerPage] = $this->getQuery()->getResult();

        return $this->cache[$offset][$itemCountPerPage];
    }
}
