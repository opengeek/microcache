<?php
/**
 * Default properties for the microcache Plugin
 */
$properties = array(
    array(
        'name' => 'cacheAnonymous',
        'desc' => 'Cache Resources for anonymous requests.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'skipIfTagsRemain',
        'desc' => 'Skip Resources that have tags remaining in the content that is being cached for the Resource.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'skipIfTagsRemainAnonymous',
        'desc' => 'Skip Resources that have tags remaining in the cacheable content when requested by anonymous users.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'skipTV',
        'desc' => 'Skip Resources that have a non-empty value in the Template Variable specified by name. Leave empty to only skip non-cacheable Resources.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'cacheExpireTV',
        'desc' => 'A TV to define Resource specific session_cache_expire values. Leave empty to use the global configuration.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'cacheLimiterTV',
        'desc' => 'A TV to define Resource specific session_cache_limiter values. Leave empty to use the global configuration.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'skipBinaryContentTypes',
        'desc' => 'Skip Resources that have a binary Content Type.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'mimeTypes',
        'desc' => 'If specified and non-empty, only cache Resources with the specified mime-types. Accepts a comma-delimited list of mime-types.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'contentTypes',
        'desc' => 'If specified and non-empty, only cache Resources with the specified ContentType id\'s. Accepts a comma-delimited list of ContentType id\'s.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'session_cache_limiter',
        'desc' => 'If specified and non-empty, sets the headers based on standard session.cache_limiter values. Uses session.cache_limiter ini setting if empty',
        'type' => 'list',
        'options' => array(
            array('text' => 'session.cache_limiter' , 'value' => ''),
            array('text' => 'public', 'value' => 'public'),
            array('text' => 'private', 'value' => 'private'),
            array('text' => 'private_no_expire', 'value' => 'private_no_expire'),
            array('text' => 'no-cache', 'value' => 'no-cache')
        ),
        'value' => 'public',
    ),
    array(
        'name' => 'session_cache_expire',
        'desc' => 'If specified and non-empty, sets the expiration time for the cache entry. Uses session.cache_expire ini setting if empty.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);

return $properties;