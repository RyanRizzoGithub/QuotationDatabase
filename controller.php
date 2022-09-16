<?php
// This file contains a bridge between the view and the model and redirects back to the proper page
// with after processing whatever form this code absorbs. This is the C in MVC, the Controller.
//
// Authors: Rick Mercer and Ryan Rizzo
//  
session_start (); // Not needed until a future iteration

require_once './DatabaseAdaptor.php';

$theDBA = new DatabaseAdaptor();

if (isset ( $_GET ['todo'] ) && $_GET ['todo'] === 'getQuotes') {
    $arr = $theDBA->getAllQuotations();
    unset($_GET ['todo']);
    echo getQuotesAsHTML ( $arr );
}

if (isset ( $_GET ['quote'] ) && isset ( $_GET ['author'])){
    $theDBA->addQuote($_GET ['quote'], $_GET ['author']);
    header ( "Location: view.php"); 
}

if (isset ( $_POST ['update'] ) ) {
    
    $clickedName = $_POST ['update'];
    $ID = $_POST ['ID'];    
    if ($clickedName === 'increase') {
        $theDBA->raiseRating ( $ID ); 
        header ( "Location: view.php");
    }
    if ($clickedName === 'decrease') {
        $theDBA->lowerRating ($ID);
        header ( "Location: view.php");
    }
    if ($clickedName === 'delete'){
        $theDBA->deleteQuote ($ID);
        header ( "Location: view.php");
    }
}
    
function getQuotesAsHTML($arr) {
    $result = '';
    foreach ($arr as $quote) {
        $result .= '<div class="container">';
        $result .= '"' . $quote ['quote'] . '"<br>';
        $result .= '<p class="author">';
        $result .= '&nbsp;&nbsp;--' . $quote['author'] . '<br></p>';
        $result .= '<form action="controller.php" method="post">';
        $result .= '<input name="ID" type="hidden" value="' . $quote['id'] . '">&nbsp;&nbsp;&nbsp;';
        $result .= '<button name="update" value="increase">+</button>';
        $result .= '&nbsp;<span id="rating"> ' . $quote['rating'] . '</span>&nbsp;&nbsp;&nbsp;';
        $result .= '<button name="update" value="decrease">-</button>&nbsp;&nbsp;';
        $result .= '<button name="update" value="delete">Delete</button></form></div>';
    }
    return $result;
}
?>