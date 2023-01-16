<?php

use Headless\GraphQL\GraphQL;

rex_extension::register('PACKAGES_INCLUDED', function (\rex_extension_point $ep) {
    if (rex_request('headless-graphql', 'string', null) !== null) {
        GraphQL::registerEndpoint();
    }
});
