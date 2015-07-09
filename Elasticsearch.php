<?php

namespace elsearch;

use yii\base\Component;
use yii\helpers\Json;


class Elasticsearch extends Component
{
    public $index;
    public $type;

    public static function createDocument($node_address, $index, $type, $raw, $id, $options = [])
    {
        if (empty($raw)) {
            $body = '{}';
        } else {
            $body = is_array($raw) ? Json::encode($raw) : $raw;
        }
        $query = curl_init('http://' . $node_address . '/' . $index . '/' . $type . '/' . $id . '?' . json_encode($options));
        curl_setopt($query, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($query, CURLOPT_POSTFIELDS, $body);
        curl_setopt($query, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($query, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body))
        );
        $result = curl_exec($query);

        return $result;

    }

    public static function updateDocument($node_address, $index, $type, $raw, $id, $options = [])
    {
        if (empty($raw)) {
            $body = '{}';
        } else {
            $body = is_array($raw) ? Json::encode($raw) : $raw;
        }

        $query = curl_init('http://' . $node_address . '/' . $index . '/' . $type . '/' . $id . '?' . json_encode($options));
        curl_setopt($query, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($query, CURLOPT_POSTFIELDS, $body);
        curl_setopt($query, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($query, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body))
        );

        $result = curl_exec($query);

        return $result;
    }

    public static function getGeoDistance($node_address, $index, $type, $lat, $lon, $radius, $options = [])
    {

        $data = [
            "query" => [
                "match_all" => [],
            ],
            "filter" => [
                "geo_distance_range" => [
                    "from" => "0km",
                    "to" => $radius . "km",
                    "location" => [
                        "lat" => $lat,
                        "lon" => $lon
                    ],
                ],
            ],
        ];
        $body = is_array($data) ? Json::encode($data) : $data;

        $query = curl_init('http://' . $node_address . '/' . $index . '/' . $type . '/_search' . '?' . json_encode($options));
        curl_setopt($query, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($query, CURLOPT_POSTFIELDS, $body);
        curl_setopt($query, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($query, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body))
        );

        $result = curl_exec($query);

        return $result;
    }

    public
    static function putMapping($node_address, $index, $type, $raw)
    {
        if (empty($raw)) {
            $body = '{}';
        } else {
            $data = [
                "{$type}" => [
                    'properties' => [$raw]
                ]
            ];
            $body = is_array($data) ? Json::encode($data) : $data;
        }

        $query = curl_init('http://' . $node_address . '/' . $index . '/_mapping/' . $type);
        curl_setopt($query, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($query, CURLOPT_POSTFIELDS, $body);
        curl_setopt($query, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($query, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body))
        );

        $result = curl_exec($query);

        return $result;
    }
}
