<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2013 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\Apigility\Doctrine\Admin\Model;

use ZF\Apigility\Admin\Model\RestServiceEntity;

class DoctrineRestServiceEntity extends RestServiceEntity
{
    protected $hydratorName;

    protected $objectManager;

    protected $byValue = true;

    protected $hydratorStrategies = array();

    protected $useGeneratedHydrator = true;

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);

        foreach ($data as $key => $value) {
            $key = strtolower($key);
            $key = str_replace('_', '', $key);
            switch ($key) {
                case 'hydratorname':
                    $this->hydratorName = $value;
                    break;
                case 'objectmanager':
                    $this->objectManager = $value;
                    break;
                case 'byvalue':
                    $this->byValue = $value;
                    break;
                case 'hydratorstrategies':
                    $this->hydratorStrategies = $value;
                    break;
                case 'usegeneratedhydrator':
                    $this->useGeneratedHydrator = $value;
                    break;
            }
        }
    }

    public function getArrayCopy()
    {
        $data = parent::getArrayCopy();
        $data['hydrator_name']      = $this->hydratorName;
        $data['object_manager']     = $this->objectManager;
        $data['by_value']   = $this->byValue;
        $data['hydrator_strategies'] = $this->hydratorStrategies;
        $data['use_generated_hydrator'] = $this->useGeneratedHydrator;

        return $data;
    }
}
