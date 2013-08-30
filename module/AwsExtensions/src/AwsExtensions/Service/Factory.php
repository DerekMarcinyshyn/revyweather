<?php
/**
 * Factory method for AwsExtension Manager Service
 *
 */

namespace AwsExtensions\Service;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    AwsExtensions\View\Helper\S3Iterator;

class Factory implements FactoryInterface {

    /**
     * Factory used to instantiate a S3 Iterator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return S3Iterator|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $parentLocator = $serviceLocator->getServiceLocator();
        $s3Client = $parentLocator->get('Aws')->get('S3');

        return new S3Iterator($s3Client);
    }
}