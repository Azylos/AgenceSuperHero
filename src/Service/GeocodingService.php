<?php 

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodingService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCoordinatesByLocation(string $location): ?array
    {
        $url = 'https://nominatim.openstreetmap.org/search';
        
        $response = $this->client->request('GET', $url, [
            'query' => [
                'q' => $location,
                'format' => 'json',
                'addressdetails' => 1,
            ]
        ]);

        $data = $response->toArray();

        // Si des résultats sont trouvés, on renvoie la latitude et la longitude
        if (!empty($data)) {
            return [
                'latitude' => $data[0]['lat'],
                'longitude' => $data[0]['lon']
            ];
        }

        return null;
    }
}