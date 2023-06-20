<?php 
/**
 * date format Y-m-d
 *
 * @param string $date
 * @return void date with Y-m-d format
 */
if ( !function_exists('ymd') ) {
    function ymd($date)
    {
        if($date) {
            return date('Y-m-d', strtotime($date));
        }

        return NULL;
    }
}

/**
 * return view
 */
if( !function_exists('viewPath') ) {
        function viewPath($fileName, $data) 
        {
            return view($fileName, ($data));
        }
 }