<?php
declare(strict_types=1);

namespace App\Service\UrlGenerator;


use Symfony\Component\HttpFoundation\RequestStack;

readonly class ClientUrlGenerator {


    public function __construct(private RequestStack $requestStack,
                                private string       $clientUrl) {}

    /**
     * Gets the Client base url according to info obtained from the request.
     */
    public function getClientUrl(): string {
        if ($this->clientUrl) return $this->clientUrl;
        $request = $this->requestStack->getCurrentRequest();

        $origin = $request->getSchemeAndHttpHost();
        if ($origin === "http://localhost:8000" && !str_ends_with($request->getBaseUrl(), '/server/public/index.php'))
            $origin = "http://localhost:4200";

        $path = $request->getBaseUrl() . "../../../../client/public/";
        if (!str_ends_with($request->getBaseUrl(), '/server/public/index.php'))
            $path = "/";

        return $origin . $path;
    }
}
