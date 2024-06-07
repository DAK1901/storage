<?php
session_start();
$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["passwd"];
$dbname = $_SESSION["dbname"];

$conn = new mysqli($servername, $username, $password, $dbname);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script lang="JavaScript" src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="../fonts/fonts.css" />
    <!-- <link rel="stylesheet" href="../styles/expenses.css"> -->
    <link rel="stylesheet" href="../styles/styles.css"> 
    <link rel="stylesheet" href="../styles/coming.css">
    <title>Заказы</title>
</head>
<body class="table-body">
    <header class="table-header">
        <a href="./stock.php" class="structure table-button">Состав склада</a>
        <a href="./coming.php" class="entry table-button">Поставки</a>
        <a href="./expenses.php" class="orders table-button">Заказы</a>
        <a href="./product_add.php" class="orders table-button">Добавить товар</a>
    </header>
    <main class="table-main">
        <section class="dates">
            <input class="input-from" type="date" placeholder="С:">
            <input class="input-to" type="date" placeholder="По:">
            <button class="search">Поиск</button>
        </section>
        <div class="extra-window">
            <table id="temp-tbl" class="table-extra" style="display:none">
                
                <thead>
                <tr>
                    <th>Идентификатор заказа</th>
                    <th>Дата заказа</th>
                    <th>Идентификатор товара</th>
                    <th>Количество</th>
                    <th>Статус</th>
                </tr>
                <thead>
                <tbody class="tmp_body">
                
                </tbody>
                
            </table>
         </div>
    </main>
    <script>
        $(document).ready(function(){

            const fromInput = document.querySelector('.input-from');
            
            const toInput = document.querySelector('.input-to');
            
            const buttonOpen = document.querySelector('.search');
            // alert(buttonOpen);
            const hideTable = document.querySelector('#temp-tbl');
            // const buttonClose = document.querySelector('.button_close')

            buttonOpen.addEventListener('click', function(){

                let fromDate = fromInput.value;
                let toDate = toInput.value;
                // hideTable.innerHTML = ("get_comings.php?from=" + fromDate + "&to=" + toDate);

                $.ajax({
                url: "get_expenses.php",
                data: {"from": fromDate, "to": toDate }
                }).done(function(response){
                    const hideTableBody = document.querySelector('.tmp_body');
                    hideTableBody.innerHTML = response;
                    // alert(response)
                });

                hideTable.style.display = 'table'
                buttonClose.style.display = 'inline-block'
            })
    
        })
        </script> 
    </main>
</body>
</html>