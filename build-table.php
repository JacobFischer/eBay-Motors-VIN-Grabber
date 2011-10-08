<?php
/*-----------------------------------*\
|  By: Jacob Fischer                  |
|  Date: 9/13/11                      |
|  Email: jacob.t.fischer@gmail.com   |
|  Web: http://jacobfischer.me/       |
\*-----------------------------------*/

include('get-car-ebay-ids.php');
include('get-car-infos.php');

// Get the parameters from the POST.
$ebay_ids = get_car_ebay_ids($_GET["hundreds"], $_GET["keywords"], $_GET["years"]);

$car_infos = get_car_infos($ebay_ids);
$table_index = 0;

?>

<!DOCTYPE html>
<html>
    <head>
        <title>eBay Motors VIN Grabber</title>
        <link rel=stylesheet href="style.css" type="text/css" media=screen>
    </head>
    <body>
        <div id="main">
            <table>
                <tr>
                    <td class="center">VIN (as Code 39 Barcode)</td>
                    <td class="center">Year</td>
                    <td class="center">Make</td>
                    <td class="center">Model</td>
                    <td class="center">Engine</td>
                    <td class="center">Drivetrain</td>
                    <td class="center">Body type</td>
                </tr>   
<?php           foreach($car_infos as &$car_info): // builds each row of the table by going through the results ?>
<?php           if(isset($car_info['VIN']) && strlen($car_info['VIN']) == 17): //check to make sure it is a valid VIN ?>
                <tr title="Row Number: <?=$table_index ?>">
                    <td>
                        <img src="barcode.php?barcode=<?=strtoupper($car_info['VIN']) ?>&width=400&height=80" alt="<?=strtoupper($car_info['VIN']) ?>" title="<?=strtoupper($car_info['VIN']) ?>" />
                    </td>
                    <td>
                        <?php if(isset($car_info['Year'])) echo (int)$car_info['Year']/10000; ?>
                    </td>
                    <td>
                        <?php if(isset($car_info['Make'])) echo $car_info['Make']; ?>
                    </td>
                    <td>
                        <?php if(isset($car_info['Model'])) echo $car_info['Model']; ?>
                    </td>
                    <td>
                        <?php if(isset($car_info['Engine'])) echo $car_info['Engine']; ?>
                    </td>
                    <td>
                        <?php if(isset($car_info['Drivetrain'])) echo $car_info['Drivetrain']; ?>
                    </td>
                    <td>
                        <?php if(isset($car_info['Body type'])) echo $car_info['Body type']; ?>
                    </td>
                </tr>
<?php           endif; $table_index++; ?>
<?php           endforeach; ?>
            </table>
        </div>
    </body>
</html>
        
