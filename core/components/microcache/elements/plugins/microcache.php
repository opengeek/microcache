<?php
/**
 * @var modX $modx
 */
switch ($modx->event->name) {
    case 'OnWebPagePrerender':
        /* Write a static version of the file before caching it in MODX */
        if ($modx->resource->get('cacheable') && $modx->resource->get('published') && $modx->resource->_output != '') {
            /* optionally skip binary content types */
            if (!empty($skipBinaryContentTypes) && $modx->resource->ContentType->get('binary')) break;
            /* skip Resources with a non-empty value for the specified TV */
            if (!empty($skipTV) && $modx->resource->getTVValue($skipTV)) break;
            /* determine if request is from anonymous user */
            $isAnonymous = ($modx->getSessionState() !== modX::SESSION_STATE_INITIALIZED || $modx->user->get('id') == 0);
            /* do not cache if the cacheable content still contains unprocessed tags */
            $matches = array();
            if ((($isAnonymous && !empty($skipIfTagsRemainAnonymous)) || (!$isAnonymous && !empty($skipIfTagsRemain))) && $modx->parser->collectElementTags($modx->resource->_content, $matches)) break;
            /* if specified, limit caching by mime-type */
            if (!empty($mimeTypes)) {
                $validMimeTypes = array_walk(explode(',', strtolower($mimeTypes)), 'trim');
                if (!in_array(strtolower($modx->resource->ContentType->get('mime_type')), $validMimeTypes)) break;
            }
            /* if specified, limit caching by ContentTypes */
            if (!empty($contentTypes)) {
                $validContentTypes = array_walk(explode(',', $contentTypes), 'trim');
                if (!in_array($modx->resource->ContentType->get('id'), $validContentTypes)) break;
            }
            /* set HTTP headers allowing clients and/or upstream cache servers to cache responses */
            $cacheLimiter = $modx->getOption('session_cache_limiter', $scriptProperties, ini_get('session.cache_limiter'), true);
            if (!empty($cacheLimiterTV)) {
                $limiter = $modx->resource->getTVValue($cacheLimiterTV);
                if (!empty($limiter)) $cacheLimiter = $limiter;
            }
            $cacheExpires = (integer) $modx->getOption('session_cache_expire', $scriptProperties, ini_get('session.cache_expire'), true);
            if (!empty($cacheExpireTV)) {
                $expiration = (integer) $modx->resource->getTVValue($cacheExpireTV);
                if (!empty($expiration)) $cacheExpires = $expiration;
            }
            $cacheExpiresSecs = $cacheExpires * 60;
            $headers = array();
            switch ($cacheLimiter) {
                case 'public':
                    $GMT = new DateTimeZone("GMT");
                    $expiresTime = new DateTime("+{$cacheExpires} minutes", $GMT);
                    $expires = $expiresTime->format('D, d M Y H:i:s \G\M\T');
                    $currentTime = new DateTime('now', $GMT);
                    $now = $currentTime->format('D, d M Y H:i:s \G\M\T');
                    $headers = array(
                        "Cache-Control: public, max-age={$cacheExpiresSecs}",
                        "Expires: {$expires}",
                        "Last-Modified: {$now}",
                    );
                    break;
                case 'private':
                    $GMT = new DateTimeZone("GMT");
                    $expires = "Thu, 19 Nov 1981 08:52:00 GMT";
                    $currentTime = new DateTime('now', $GMT);
                    $now = $currentTime->format('D, d M Y H:i:s \G\M\T');
                    $headers = array(
                        "Cache-Control: private, max-age={$cacheExpiresSecs}, pre-check={$cacheExpiresSecs}",
                        "Expires: {$expires}",
                        "Last-Modified: {$now}",
                    );
                    break;
                case 'private_no_expire':
                    $GMT = new DateTimeZone("GMT");
                    $currentTime = new DateTime('now', $GMT);
                    $now = $currentTime->format('D, d M Y H:i:s \G\M\T');
                    $headers = array(
                        "Cache-Control: private, max-age={$cacheExpiresSecs}, pre-check={$cacheExpiresSecs}",
                        "Last-Modified: {$now}",
                    );
                    break;
                case 'no-cache':
                    $expires = "Thu, 19 Nov 1981 08:52:00 GMT";
                    $headers = array(
                        "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0",
                        "Expires: {$expires}",
                        "Pragma: no-cache",
                    );
                    break;
                default:
                    break;
            }
            if (!empty($headers)) {
                header_remove('Pragma');
                foreach ($headers as $header) {
                    header($header);
                }
            }
        }
        break;
}
