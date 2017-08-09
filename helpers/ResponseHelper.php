<?php

class ResponseHelper
{

    public static function formatGetResponse($data, $format)
    {
        $output = array();
        if (empty($data)) return $output;
        switch ($format) {
            case 'csv':
                $output = fopen('php://output', 'w');
                fputcsv($output, array('Country', 'Event', 'Total'));
                foreach ($data as $row) {
                    fputcsv($output,
                        array($row['country'], $row['action'], $row['total']));
                }
                break;
            case 'json':
                foreach ($data as $item) {
                    $output[$item['country']][$item['action']] = $item['total'];
                }
                return json_encode($output);
                break;
            default:
                return $data;
                break;
        }

        return $output;
    }

    public static function formatPostResponse($result)
    {
        if ($result) {
            http_response_code(204);
        } else {
            http_response_code(400);
            return false;
        }
    }

    public static function formatError($message)
    {
        return json_encode(['error' => $message]);
    }

}
