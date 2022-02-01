<?php
    // connecting to the session
    session_start();
    require 'vendor/autoload.php';
    $client = new \GuzzleHttp\Client();
    // getting the current card array from the api
    $card_array = $_SESSION['card_array'];
    // getting the current deck id from the api
    $deck_id = $_SESSION['deck_id'];


    // only requesting a single card

    // same logic as the drawtwo page
    // pulling from the same deck but only want one card instead of one
    $response = $client->request('GET', 'https://deckofcardsapi.com/api/deck/'.$deck_id.'/draw/?count=1');
    // turning the JSON into a associate array
    $response_data = json_decode($response->getBody(), TRUE);
    // creating an array to hold the first index card from the api
    // this varaible is appending to the card array
    // need the [] to create an array
    $card_array[] = $response_data['cards'][0];

    // saving the array to the session
    $_SESSION['card_array'] = $card_array;
    // saving the deck id to the session from the response data
    $_SESSION['deck_id'] = $response_data['deck_id'];
    // calling the function to calculate the cards
    $card_total = calc_card_total($card_array);

    function calc_card_total($card_array1){
        // getting the card totals. 
        // only difference between value 1 and value 2 is the ace
        $card_value1=["KING"=>10, "QUEEN"=>10, "JACK"=>10,"ACE"=>1, "2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, "7"=>7, "8"=>8, "9"=>9, "10"=>10 ];
        $card_value2=["KING"=>10, "QUEEN"=>10, "JACK"=>10,"ACE"=>11, "2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, "7"=>7, "8"=>8, "9"=>9, "10"=>10 ];
        $card_total1 = 0;
        $card_total2 = 0;
        $card_face="";
    
        foreach($card_array1 as $card){
            // getting the value from the JSON array
            // getting from each opject
            $card_face = $card['value'];
            $card_total1 = $card_total1 + $card_value1[$card_face];
            $card_total2 = $card_total2 + $card_value2[$card_face];
        }
        if($card_total2 <= 21){
            return $card_total2;
        } else {
            return $card_total1;
        }
     }

    

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- displaying the cards-->
    <?php foreach($card_array as $card) : ?>
        <!-- getting the image from the api-->
       <img src="<?php echo $card['image'];?>">
   <?php endforeach; ?>

   <h1><?php echo "Your card total is $card_total"; ?></h1>

   <?php if($card_total > 21): ?>
       Sorry your total is above 21
       <a href="index.php">Play Again</a>
   <?php elseif($card_total == 21): ?>
       You win, take a trip to Vegas
       <a href="index.php">Play Again</a>
   <?php else: ?>
        Do you feel lucky?
       <a href="drawagain.php">Draw again</a>
   <?php endif; ?>

</body>
</html>