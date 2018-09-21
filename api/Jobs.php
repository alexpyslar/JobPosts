<?php

/**
 * Class Jobs
 */
class Jobs
{
    /**
     * Get Job post from api
     *
     * @return array|string
     */
    public function getJobs()
    {
        $apiResult = $this->curlRequest();

        if (!$apiResult) {
            return 'Request result status code is not OK';
        }
        if (!$apiResult['success']) {
            return 'Api result is not successful';
        }
        if ($apiResult['numRows'] == 0) {
            return 'Api numRows equal 0';
        }
        if (!$apiResult['data'] || count($apiResult['data']) == 0) {
            return 'There were no rows to output';
        }

        if (count($apiResult['data']) < 7) {
            return $apiResult['data'];
        }

        return array_slice($apiResult['data'], 0, 6);
    }

    /**
     * Perform cUrl request to api endpoint
     *
     * @return bool|mixed
     */
    private function curlRequest()
    {
        $apiUrl = 'https://api.recman.no/v2/get/?' .
            'key=180413023455kb1d5c1223d347c115f78c6daaa9bd426252042511' .
            '&scope=jobPost' .
            '&fields=projectId,name,title,logo,ingress,startDate,endDate';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);

        $server_output = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code == 200) {
            $data = json_decode($server_output, true);
        } else {
            $data = false;
        }

        curl_close($ch);

        return $data;
    }
}