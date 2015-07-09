<?php

namespace elsearch;

use yii\base\Component;
use yii\helpers\Json;


class Elasticsearch extends Component
{
    public $index;
    public $type;

    public static function createDocument($node_address, $index, $type, $data, $id = null, $options = [])
    {
        if (empty($data)) {
            $body = '{}';
        } else {
            $body = is_array($data) ? Json::encode($data) : $data;
        }
        if ($id !== null) {
            $query = curl_init('http://' . $node_address . '/' . $index . '/' . $type . '/' . $id . '?' . $options);
            curl_setopt($query, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($query, CURLOPT_POSTFIELDS, $body);
            curl_setopt($query, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($query, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($body))
            );

            $result = curl_exec($query);

            return $result;

        } else {
            $query = curl_init('http://' . $node_address . '/' . $index . '/' . $type . '/' . $id . '?' . $options);
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
}
