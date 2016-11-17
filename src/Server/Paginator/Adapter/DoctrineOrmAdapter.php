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

        if (!array_key_exists($offset, $this->cache)) {
            $this->cache[$offset] = [];
        }

        /**
         * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html#first-and-max-result-items-dql-query-only
         */
        if (strstr($this->getQuery()->getDql(), 'INNER JOIN')) {
            $this->getQuery()->setMaxResults(null);
            try {
                /*
                 * We're attemping to iterate the query, which will leave it in an unworkable state even after catching
                 * the exception. So we're cloning before attempting to iterate, then iterate the original if no
                 * exception was thrown.
                 */
                $clone = clone $this->getQuery();
                $clone->setParameters($this->getQuery()->getParameters());
                $clone->iterate();
                $iterableResult = $this->getQuery()->iterate();
            } catch (\Exception $e) {
                /**
                 * We won't be able to iterate, just guess a good maximum (based on itemCountPerPage) and slice the result.
                 */
                $this->getQuery()->setMaxResults($itemCountPerPage * 10); // best guess
                $this->cache[$offset][$itemCountPerPage] = array_slice($this->getQuery()->getResult(), 0, $itemCountPerPage);
                return $this->cache[$offset][$itemCountPerPage];
            }

            $result = [];
            $iteration = 0;
            foreach ($iterableResult as $row) {
                ++ $iteration;
                $result[] = $row[0];
                if ($iteration >= $itemCountPerPage) {
                    $this->cache[$offset][$itemCountPerPage] = $result;
                    return $this->cache[$offset][$itemCountPerPage];
                }
            }

            $this->cache[$offset][$itemCountPerPage] = $result;
            return $result;
        }

        $this->cache[$offset][$itemCountPerPage] = $this->getQuery()->getResult();

        return $this->cache[$offset][$itemCountPerPage];
    }
}
