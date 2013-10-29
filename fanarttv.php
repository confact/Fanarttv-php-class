<?php

/**
 * Fanart.tv 
 * PHP class - wrapper to fanart-tv's API
 * API Documentation - http://fanart.tv/api-docs/movie-api/
 * 
 * @author    confact <hakan@dun.se>
 * @copyright 2013 confact
 * @date 2013-09-11
 * @release <0.0.1>
 * 
 */
class Fanarttv
{

    /**
     * The cosntructor setting the config variables
     */
    public function __construct()
    {
        $this->apikey = "YOUR-API";
        $this->server = "http://api.fanart.tv/webservice/";
        $this->type = "JSON";


        $this->_obj = & get_instance();
        // Cache need to be fixed
        $this->_obj->load->driver('cache');
    }

    /**
     * Getting movie pictures
     * 
     * @param string $imdb
     * @param string $type
     * 
     * @return array
     */
    public function getMoviePicturesByImdb($imdb, $type = "moviethumb")
    {
        $fanart = $this->_call("movie/" . $imdb);
        if ($fanart != null) {
            $firstkey = key($fanart);
            $fanart = $fanart[$firstkey];

            if (isset($fanart[$type])) {
                return $fanart[$type];
            } else {
                return $fanart;
            }
        } else {
            return array();
        }
    }

    /**
     * Getting tv show pictures
     * 
     * @param string $imdb
     * @param string $type
     * 
     * @return array
     */

    public function getTVPicturesByImdb($imdb, $type = "tvthumb")
    {
        $fanart = $this->_call("series/" . $imdb);
        if ($fanart != null) {
            $firstkey = key($fanart);
            $fanart = $fanart[$firstkey];

            if (isset($fanart[$type])) {
                return $fanart[$type];
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * The fucntion making all the work using curl to call
     * 
     * @param string $path
     * @param string $method
     * 
     * @return array
     */

    private function _call($path, $method = 'GET')
    {
        $url = $this->server . '/' . $this->apikey . "/" . $path . "/" . $this->type;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return json_decode($response, true);
    }

}
