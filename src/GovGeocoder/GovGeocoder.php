<?php

namespace App\GovGeocoder;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GovGeocoder
{
    public function __construct(private HttpClientInterface $http)
    {
        
    }

    public function geocodeAddress(string $q): array
    {
        $response = $this->http->request(
            'GET',
            'https://api-adresse.data.gouv.fr/search/',
            [
                'query' => [
                    'q' => $q,
                ]
            ]
        );

        $coords = $response->toArray()['features'][0];

        return $coords;
    }
}