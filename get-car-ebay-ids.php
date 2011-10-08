<?php
/*-----------------------------------*\
|  By: Jacob Fischer                  |
|  Date: 9/13/11                      |
|  Email: jacob.t.fischer@gmail.com   |
|  Web: http://jacobfischer.me/       |
\*-----------------------------------*/

function get_car_ebay_ids($number_of_results_times_100 = 1, $keywords = "" , $years = '2011')
{   
    // The appid to use
    include('app_id.php');    
     
    /* Settings
    **********************************************/
    $ebay_motors_id = "6001"; 
    /*********************************************/
    
    $return_ids = array();
    $i = 0;

    // because the eBay API returns results in paging we will have to make multiple calls for however many they want
    for($current_page = 0; $current_page < $number_of_results_times_100; $current_page++)
    {
        // build the eBay REST-full call
        $api_call="http://svcs.ebay.com/services/search/FindingService/v1?";
        $api_call .= "OPERATION-NAME=findItemsAdvanced&";
        $api_call .= "SERVICE-VERSION=1.11.0&";
        $api_call .= "SECURITY-APPNAME=" . $app_id . "&";
        $api_call .= "RESPONSE-DATA-FORMAT=XML&";
        $api_call .= "REST-PAYLOAD&";
        $api_call .= "categoryId=" . $ebay_motors_id . "&";
        
        if($keywords != "")
        {
            $api_call .= "keywords=" . urlencode($keywords) . "&";
        }
        if($years != "")
        {
            $api_call .= "aspectFilter.aspectName=Model%20Year&aspectFilter.aspectValueName=" . urlencode($years) . "&";
        }
        $api_call .= "paginationInput.pageNumber=" . ($current_page + 1) . "&";
        $api_call .= "paginationInput.entriesPerPage=100&";
        
        // get the xml resonse from eBay
        $response = simplexml_load_file($api_call);
        
        // and parse it
        if ($response->ack == "Success")
        {
            foreach($response->searchResult->item as $item)
            {
                $return_ids[count($return_ids)] = $item->itemId;
            }
        }
        
        unset($response);
    }
    
    return $return_ids;
}

