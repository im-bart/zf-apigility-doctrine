Roll'n API
==============================

This modification to zfcampus/zf-apigility-skeleton implements 
zfcampus/zf-apigility-doctrine with ORM and sqlite.

To modify the doctrine api configuration modify the ```rollnapi[api_entities]``` in ```config/autoload/rollnapi.global.php```.  These arrays are sent to ```ZF\\Apigility\\Doctrine\\Admin\\Model\\DoctrineRestServiceResource``` to create the api.

To build the API enable development mode then run ```bin/apirebuild```


There are additional parameters specific to zf-apigility-doctrine:

- object_manager: key of an ObjectManager in the ServiceManager
- hydrator: (optional) key of an hydrator in the HydratorManager
- query_provider: (optional) Key of a service in ZfCollectionQueryManager
- listeners: (optional) Array of keys that are ListenerAggregateInterface in the ServiceManager
- class:  (optional) a string to use another resource-class instead of the key of the configuration

- entity_class: The doctrine ORM entity / ODM document as string
- object_manager: key of an ObjectManager in the ServiceManager
- by_value: true / false
- strategies: (optional) Array of keys that are Hydrator\Strategy\StrategyInterface in the ServiceManager
- use_generated_hydrator: (optional) true / false: This will use the generated hydrators from ODM\MongoDB

