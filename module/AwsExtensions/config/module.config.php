<?php

namespace AwsExtensions;

return array(
    'service_manager' => array(
        'factories' => array(
            'Aws' => 'Aws\Factory\AwsFactory'
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'AwsExtensions\View\Helper\S3Iterator'      => 'AwsExtensions\Service\Factory',
        ),

        'aliases' => array(
            'bucketlist'    => 'AwsExtensions\View\Helper\S3Iterator'
        ),
    ),
);