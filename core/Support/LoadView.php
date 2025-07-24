<?php

namespace Core\Support;

use stdClass;

class LoadView
{
    /**
     * @param $view
     * @param $data
     * @param $loadHtml
     * @return false|mixed|string
     */
    public static function View($view, $data, $loadHtml): mixed
    {
        /*this is for original data get from pagination data*/
        $originalData = self::extractDataIfExistPagination($data);

        /*this is for getting pagination links var from original pagination data*/
        $paginateData = self::extractPaginationData($data);

        extract($originalData);  /*convert array key as variable here*/
        extract($paginateData); /*convert array key as variable here*/

        if ($loadHtml) {
            /**
             * Loading view for pdf etc
             */
            ob_start();
            require_once(root_path . "/views/" . makeView($view) . ".php");
            $res = ob_get_contents();
            ob_end_clean();

            return $res;
        }

        return require_once(root_path . "/views/" . makeView($view) . ".php");
    }

    /**
     * @param $data
     * @return mixed
     */
    private static function extractDataIfExistPagination($data): mixed
    {
        $result = [];
        foreach ($data as $key => $d) {
            if (! is_object($d)) {
                if (isset($d['data'])) {
                    $result[$key] = $d['data'];
                } elseif (isset($d['simple']['data'])) {
                    $result[$key] = $d['simple']['data'];
                } else {
                    $result = $data;
                }
            } else {
                $result = $data;
            }
        }

        return $result;
    }

    /**
     * @param $data
     * @return array
     */
    private static function extractPaginationData($data): array
    {
        $response = [];
        $result['render'] = new stdClass();
        foreach ($data as $key => $d) {
            if (! is_object($d)) {
                if (isset($d['data'])) {
                    $response[$key] = $d['data']; //assign data before pagination and unset
                    unset($d['data']);
                    /*here call pagination function to render pagination html*/

                    $result['render']->links = pagination((object)$d);

                } elseif (isset($d['simple']['data'])) {
                    $response[$key] = $d['simple']['data']; //assign data before pagination and unset
                    unset($d['simple']['data']);
                    /*here call pagination function to render pagination html*/
                    $result['render']->links = simplePagination((object)$d['simple']);
                } else {
                    $response[$key] = $d;
                }
            } else{
                $response = [];
            }
        }

        return array_merge($result,$response);
    }
    
}