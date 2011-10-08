<?php
/*-----------------------------------*\
|  By: Jacob Fischer                  |
|  Date: 9/13/11                      |
|  Email: jacob.t.fischer@gmail.com   |
|  Web: http://jacobfischer.me/       |
\*-----------------------------------*/

function get_car_infos($item_ids)
{
    // the eBay appis to use
    include('app_id.php');
    
    $i = 0; // return_info index
    $count = count($item_ids);
    $return_info = array();
    
    // because we can only request 20 items at a time break up the requests
    for($itteration = 0; $itteration < $count; $itteration += 19)
    {
        $api_call = "http://open.api.ebay.com/shopping?callname=GetMultipleItems&responseencoding=XML&version=735&";
        $api_call .= "appid=" . $app_id . "&siteid=0&IncludeSelector=ItemSpecifics&";
        $api_call .= "ItemID=";
        
        // append item IDs
        for($current_index = $itteration; $current_index < $itteration + 19; $current_index++)
        {
            
            if($current_index  >= $count)
            {
                break;
            }
            
            $api_call .= $item_ids[$current_index] . ",";
        }
        
        // load the response from the response XML
        $response = simplexml_load_file($api_call);
        
        // if the response is valid
        if ($response)
        {
            foreach($response->Item as $Item)
            {
                if (!empty($Item->ItemSpecifics->NameValueList))
                {
                    $return_info[$i] = array();
                    
                    foreach($Item->ItemSpecifics->NameValueList as $name_value_pair)
                    {
                        // if the value is not empty or eBay's empty ( "-" )
                        if($name_value_pair->Value != "" && $name_value_pair->Value != "-")
                        {
                            $return_info[$i][(string)$name_value_pair->Name] = (string)$name_value_pair->Value;
                        }
                    }
                }
                
                $i++;
            }
        }
        
        unset($response);
    }
    
    return $return_info;
}

