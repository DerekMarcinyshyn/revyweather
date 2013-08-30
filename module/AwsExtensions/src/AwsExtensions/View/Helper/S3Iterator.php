<?php
/**
 * S3 Iterator
 *
 * Get and return the objects in a Bucket on AWS S3
 *
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        August 29, 2013
 */

namespace AwsExtensions\View\Helper;

use Aws\Common\Aws,
    Aws\S3\S3Client,
    Aws\View\Exception\InvalidDomainNameException,
    Zend\View\Helper\AbstractHelper;

class S3Iterator extends AbstractHelper {

    /**
     * @var S3Client
     */
    protected $client;

    /**
     * @param S3Client $client
     */
    public function __construct(S3Client $client) {
        $this->client = $client;
    }

    /**
     * Get and return the contents of a S3 Bucket
     *
     * @param string $bucket
     * @return \Guzzle\Service\Resource\ResourceIteratorInterface
     * @throws \Aws\View\Exception\InvalidDomainNameException
     */
    public function __invoke($bucket = '') {

        $bucket = trim($bucket);
        if (empty($bucket)) {
            throw new InvalidDomainNameException('An empty bucket name was given');
        }

        $bucketList = $this->client->getIterator('ListObjects', array(
            'Bucket' => $bucket
        ));

        return $bucketList;
    }
}