<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2013 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace Db\Controller;

use Zend\View\Model\ViewModel;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\EntityManager;
use Zend\Console\ColorInterface;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\Configuration\ConfigResource;
use Zend\Config\Writer\PhpArray as PhpArrayWriter;
use Zend\Filter\FilterChain;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Prompt;
use Doctrine\DBAL\DBALException;

class DbApiController extends AbstractActionController
{
    public function apiModuleAction()
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        $moduleName = 'DbApi';
        $objectManagerAlias = 'doctrine.entitymanager.orm_default';
        $routePrefix = 'api';
        $useEntityNamespacesForRoute = true;

        if($this->getServiceLocator()->get('Zend\ModuleManager\ModuleManager')->getModule($moduleName)){
            $this->getConsole()->writeLine(sprintf(
                'The %s is already loaded. If you want to build it again, delete the contents of /modules/%s',
                $moduleName,
                $moduleName
            ), ColorInterface::YELLOW);
            return;
        }

        // Build Module
        $moduleResource = $this->getServiceLocator()->get('ZF\Apigility\Admin\Model\ModuleResource');
        $moduleResource->setModulePath(realpath(__DIR__ . '/../../../../../'));

        $metadata = $moduleResource->create(array(
            'name' =>  $moduleName,
        ));

        return "$moduleName module has been created.\n";
    }

    public function apiAction()
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        if(!$this->getServiceLocator()->get('Zend\ModuleManager\ModuleManager')->getModule('DbApi')){
            $this->getConsole()->writeLine(sprintf(
                'Module %s is not loaded. Did you forget to run "./app build api module" ?',
                $moduleName
            ), ColorInterface::YELLOW);
            return;
        }

        $serviceResource = $this->getServiceLocator()->get('ZF\Apigility\Doctrine\Admin\Model\DoctrineRestServiceResource');
        $config = $this->getServiceLocator()->get('Config');

        foreach ($config['rollnapi']['api_entities'] as $name => $apiConfig) {
            $serviceResource->setModuleName('DbApi');
            $serviceResource->create($apiConfig);
        }

/**
 * This section which generates rpc resources for entites may or may not
 * find a home in the final product so it's left here for now
 *
            foreach ($entityMetadata->associationMappings as $mapping) {
                switch ($mapping['type']) {
                    case 4:
                        $rpcServiceResource = $this->getServiceLocator()->get('ZF\Apigility\Doctrine\Admin\Model\DoctrineRpcServiceResource');
                        $rpcServiceResource->setModuleName($moduleName);
                        $rpcServiceResource->create(array(
                            'service_name' => $resourceName . '' . $mapping['fieldName'],
                            'route' => $mappingRoute = $route . '[/:parent_id]/' . $filter($mapping['fieldName']) . '[/:child_id]',
                            'http_methods' => array(
                                'GET',
                            ),
                            'options' => array(
                                'target_entity' => $mapping['targetEntity'],
                                'source_entity' => $mapping['sourceEntity'],
                                'field_name' => $mapping['fieldName'],
                            ),
                        ));

                        $results[$entityMetadata->name . $mapping['fieldName']] = $mappingRoute;

                        break;
                    default:
                        break;
                }
            }
    */

        return ("\nResources have been created.\n");
    }

    /**
     * @return Console
     */
    protected function getConsole()
    {
        return $this->getServiceLocator()->get('Console');
    }
}

