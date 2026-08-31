<?php

declare(strict_types=1);

return [
    'navigation' => ['group' => 'Media'],
    'label' => 'Aws Test',
    'plural_label' => 'Aws Test (Plurale)',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        's3_results' => ['label' => 's3_results', 'placeholder' => 's3_results', 'helper_text' => 's3_results', 'description' => 's3_results'],
        'cloudfront_url' => ['label' => 'cloudfront_url', 'placeholder' => 'cloudfront_url', 'helper_text' => 'cloudfront_url', 'description' => 'cloudfront_url'],
        'cloudfront_results' => ['label' => 'cloudfront_results', 'placeholder' => 'cloudfront_results', 'helper_text' => 'cloudfront_results', 'description' => 'cloudfront_results'],
        'iam_user' => ['label' => 'iam_user', 'placeholder' => 'iam_user', 'helper_text' => 'iam_user', 'description' => 'iam_user'],
        'iam_results' => ['label' => 'iam_results', 'placeholder' => 'iam_results', 'helper_text' => 'iam_results', 'description' => 'iam_results'],
        'full_results' => ['label' => 'full_results', 'placeholder' => 'full_results', 'helper_text' => 'full_results', 'description' => 'full_results'],
        'aws_config' => ['label' => 'aws_config', 'placeholder' => 'aws_config', 'helper_text' => 'aws_config', 'description' => 'aws_config'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Aws Test'],
        'edit' => ['label' => 'Modifica Aws Test'],
        'delete' => ['label' => 'Elimina Aws Test'],
        'test_s3_connection' => ['label' => 'test_s3_connection', 'icon' => 'test_s3_connection', 'tooltip' => 'test_s3_connection'],
        'test_s3_permissions' => ['label' => 'test_s3_permissions', 'icon' => 'test_s3_permissions', 'tooltip' => 'test_s3_permissions'],
        'test_file_operations' => ['label' => 'test_file_operations', 'icon' => 'test_file_operations', 'tooltip' => 'test_file_operations'],
        'test_cloudfront_config' => ['label' => 'test_cloudfront_config', 'icon' => 'test_cloudfront_config', 'tooltip' => 'test_cloudfront_config'],
        'test_signed_urls' => ['label' => 'test_signed_urls', 'icon' => 'test_signed_urls', 'tooltip' => 'test_signed_urls'],
        'test_iam_credentials' => ['label' => 'test_iam_credentials', 'icon' => 'test_iam_credentials', 'tooltip' => 'test_iam_credentials'],
        'test_iam_policies' => ['label' => 'test_iam_policies', 'icon' => 'test_iam_policies', 'tooltip' => 'test_iam_policies'],
        'run_full_diagnostic' => ['label' => 'run_full_diagnostic', 'icon' => 'run_full_diagnostic', 'tooltip' => 'run_full_diagnostic'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
    ],
    'sections' => [
        'S3 Connection Test' => ['label' => 'S3 Connection Test', 'heading' => 'S3 Connection Test'],
        'CloudFront Test' => ['label' => 'CloudFront Test', 'heading' => 'CloudFront Test'],
        'IAM Permissions Test' => ['label' => 'IAM Permissions Test', 'heading' => 'IAM Permissions Test'],
        'Complete Diagnostic' => ['label' => 'Complete Diagnostic', 'heading' => 'Complete Diagnostic'],
    ],
];
