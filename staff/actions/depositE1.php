<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
  include_once("../../core/settings.php");
//Retrieve necessary values:
$amount= (double)$_POST["Amount"];
$resultID = mysql_query("SELECT MAX(ID) FROM transactions");
$rowID = mysql_fetch_row($resultID);
$prevID = $rowID[0];
$resultPrevBal = mysql_query("SELECT Balance FROM companyAccounts WHERE ID = 3");
$rowPrevBal = mysql_fetch_row($resultPrevBal);
$prevBal = $rowPrevBal[0];
$newBal = $prevBal + $amount;
$resultPrevID = mysql_query("SELECT Transaction_ID FROM e1Trans WHERE ID = '$rowID[0]'");
$rowPrevID = mysql_fetch_row($resultPrevID);


//Define inserted variables
$newID = $prevID + 1;
$from = "Entertainment1";
$to = "CashBox";

//Query Deposit transactions
$transactionDetailQuery = mysql_query("INSERT INTO transactions (ID, Description, Company_Acquiring, Company_Selling, Amount, Type) VALUES (NULL, 'Deposit', '$from', '$to', '$amount', 'Deposit')");

//Query Deposit e1Trans
$depositQuery = "INSERT INTO e1Trans (ID, Transaction_ID, Description, Company_Acquiring, Company_Selling, Amount, Previous_Balance, New_Balance, Type) VALUES (NULL, '$newID', 'Deposit', '$from', '$to', '$amount', '$prevBal', '$newBal', 'Deposit')";
$deposit = mysql_query($depositQuery);

//Query Deposit cashBoxTrans

$cashIDRes = mysql_query("SELECT MAX(ID) FROM cashBoxTrans");
$cashIDRow = mysql_fetch_row($cashIDRes);
$cashID = $cashIDRow[0];
$prevCashRes = mysql_query("SELECT NewBalance FROM cashBoxTrans WHERE ID = $cashID");
$prevCashRow = mysql_fetch_row($prevCashRes);
$prevCash = $prevCashRow[0];

$newCash = $prevCash + $amount;
$toCB = "Cash Box";

$cbTransQuery = mysql_query("INSERT INTO cashBoxTrans (ID, TransID, `From`, `To`, Amount, PreviousBalance, NewBalance) VALUES (NULL, '$newID','$from', 'CB', '$amount', '$prevCash','$newCash')");

//Query Account Balance
$balanceQuery = mysql_query("UPDATE companyAccounts SET PreviousBalance = $prevBal, Balance = $newBal WHERE ID = 3");

if($deposit && $cbTransQuery && $balanceQuery){
            header("location:../bankE1.php?deposit=e1#bTrans");
        }
        else
        {
            die (mysql_error());
            header("location:../bankE1.php?deposit=error#bTrans");
        }



?>