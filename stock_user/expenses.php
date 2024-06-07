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
    <link rel="stylesheet" href="../styles/styles.css"> 
    <link rel="stylesheet" href="../styles/coming.css">
    <title>Заказы</title>
</head>
<body class="table-body">
    <header class="table-header">
        <a href="./stock.php" class="structure table-button">Состав склада</a>
        <a href="./coming.php" class="entry table-button">Поставки</a>
        <a href="./expenses.php" class="orders table-button">Заказы</a>
    </header>
    <div class="table-main">
        <div class="scroll-table">
            <table>
                <thead>
                    <tr>
                        <th>Идетификационный номер</th>
                        <th>Дата</th>
                        <th>Идетификационный номер товара</th>
                        <th>Количество</th>
                        <th>Статус</th>
                    </tr>
                </thead>
            </table>	
            <div class="scroll-table-body">
                <table>
                    <tbody>
                        <?php
                            $coming = mysqli_query($conn, "SELECT * FROM expenses 
                                                          WHERE done = 0;");
                            while ($row = mysqli_fetch_array($coming))
                            {
                                echo "<tr>";
                                echo"<td>".$row['id']."</td>";
                                echo"<td>".$row['date']."</td>";
                                echo"<td>".$row['product_id']."</td>";
                                echo"<td>".$row['count']."</td>";
                                echo"<td><button class='button_open' id=".$row['id'].">Собрать</button></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>	
        </div>
        <div class="extra-window">
            <span class="expense_num"></span>
            <button class="button_close">Заказ собран</button>
            <table id="temp-tbl" class="table-extra" style="display:none">
                
                <thead>
                <tr>
                    <th>Секция</th>
                    <th>Количество</th>
                    <th>Общий вес</th>
                </tr>
                <thead>
                <tbody class="tmp_body">
                
                </tbody>
                
            </table>
         </div>
    </div>
    <script>
        $(document).ready(function(){

        const buttonOpen = document.querySelectorAll('.button_open');
        
        const hideTable = document.querySelector('#temp-tbl')
        const buttonClose = document.querySelector('.button_close')

        let comId;
        buttonOpen.forEach((open) =>{
            open.addEventListener('click', function(){
                comId = open.getAttribute('id');
                // alert(comId);
                
                $.ajax({
                url: "get_temporary_table_expenses.php?id=" + comId
                }).done(function(response){
                    if (response == "err")
                    {
                        alert('Недостаточно товаров на складе для выполнения заказа');
                    }
                    else
                    {
                        const infoSpan = document.querySelector(".expense_num");
                        infoSpan.innerText = "Заказ номер " + comId;
                        const hideTableBody = document.querySelector('.tmp_body');
                        hideTableBody.innerHTML = response;
                        hideTable.style.display = 'table'
                        buttonClose.style.display = 'inline-block'
                    }
                    // alert(response)
                });

            })
        })
    

        buttonClose.addEventListener('click', function(){
                $.ajax({
                    url: "done_expenses.php?id=" + comId,
                    async: false 
                }).done(function(response){
                    alert(response);    
                });

            // buttonClose.style.display = 'none'
            // hideTable.style.display = 'none'
            location.reload();
        })
        })
        </script> 
    </main>
</body>
</html>