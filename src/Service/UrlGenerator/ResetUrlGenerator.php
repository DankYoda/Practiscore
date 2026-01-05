<?php
declare(strict_types=1);

namespace App\Service\UrlGenerator;

use League\Uri\UriTemplate;

readonly class ResetUrlGenerator {
    function __construct(private ClientUrlGenerator $urlGenerator) {}

    /**
     * Generate a UriTemplate based on the context of the request.
     *
     * `$this->generator->generate()->expand(['userId' => $userId, 'token' => $token]);`
     *
     * @return UriTemplate
     * @see https://uri.thephpleague.com/uri/7.0/uri-template/
     */
    public function generate(): UriTemplate {
        return new UriTemplate("{$this->urlGenerator->getClientUrl()}#/forgot/set?userId={userId}&token={token}");
    }
}
