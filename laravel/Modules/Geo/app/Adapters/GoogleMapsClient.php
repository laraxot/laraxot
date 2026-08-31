<?php

declare(strict_types=1);

namespace Modules\Geo\Adapters;

use Modules\Geo\Exceptions\GoogleMaps\GoogleMapsApiException;

/**
 * Adapter per le interazioni con l'API di Google Maps (geocoding, distance matrix, elevation).
 */
class GoogleMapsClient extends GeoHttpClientBase
{
    private const string GEOCODING_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    private const string DISTANCE_MATRIX_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    private const string ELEVATION_URL = 'https://maps.googleapis.com/maps/api/elevation/json';

    /**
     * @return array<string, mixed>
     *
     * @throws GoogleMapsApiException
     */
    public function reverseGeocode(float $latitude, float $longitude): array
    {
        try {
            return $this->makeRequest('GET', self::GEOCODING_URL, [
                'latlng' => "{$latitude},{$longitude}",
                'key' => $this->getApiKey(),
                'language' => 'it',
            ]);
        } catch (\Throwable $e) {
            throw GoogleMapsApiException::requestFailed($e->getMessage());
        }
    }

    /**
     * @param  array<string>  $origins
     * @param  array<string>  $destinations
     * @return array<string, mixed>
     *
     * @throws GoogleMapsApiException
     */
    public function getDistanceMatrix(array $origins, array $destinations): array
    {
        try {
            return $this->makeRequest('GET', self::DISTANCE_MATRIX_URL, [
                'origins' => implode('|', $origins),
                'destinations' => implode('|', $destinations),
                'key' => $this->getApiKey(),
                'language' => 'it',
                'units' => 'metric',
            ]);
        } catch (\Throwable $e) {
            throw GoogleMapsApiException::requestFailed($e->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     *
     * @throws GoogleMapsApiException
     */
    public function getElevation(float $latitude, float $longitude): array
    {
        try {
            return $this->makeRequest('GET', self::ELEVATION_URL, [
                'locations' => "{$latitude},{$longitude}",
                'key' => $this->getApiKey(),
            ]);
        } catch (\Throwable $e) {
            throw GoogleMapsApiException::requestFailed($e->getMessage());
        }
    }

    protected function getServiceName(): string
    {
        return 'google_maps';
    }
}
