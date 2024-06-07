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
        <a href="./product_add.php" class="orders table-button">Добавить товар</a>
    </header>
    <section class="filter" styles="padding: 10px">
        <input class="input-name" type="text" placeholder="Название" >
        <input class="input-weight" type="number" placeholder="Вес">
        <input class="input-volume" type="number" placeholder="Объем">
        <button class="add">Добавить</button>
        </section>
       <div class="scroll-table">
            <table>
                <thead>
                    <tr>
                        <th>Идетификационный номер товара</th>
                        <th>Название товара</th>
                        <th>Вес</th>
                        <th>Объем</th>
                    </tr>
                </thead>
            </table>	
            <div class="scroll-table-body">
                <table>
                    <tbody>
                        <?php
                            $products = mysqli_query($conn, "SELECT * FROM products;");
                            while ($row = mysqli_fetch_array($products))
                            {
                                echo "<tr>";
                                echo"<td>".$row['product_id']."</td>";
                                echo"<td>".$row['name']."</td>";
                                echo"<td>".$row['weight']."</td>";
                                echo"<td>".$row['volume']."</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>	
        </div>
    <script>
        $(document).ready(function(){

            const nameInp = document.querySelector('.input-name');
            const weightInp = document.querySelector('.input-weight');
            const volumeOnp = document.querySelector('.input-volume');
            
            const buttonOpen = document.querySelector('.add');
            // alert(buttonOpen);
            // const hideTable = document.querySelector('#temp-tbl');
            // const buttonClose = document.querySelector('.button_close')

            buttonOpen.addEventListener('click', function(){

                let name = nameInp.value;
                let weight = weightInp.value;
                let volume = volumeOnp.value;
                // hideTable.innerHTML = ("get_comings.php?from=" + fromDate + "&to=" + toDate);

                $.ajax({
                url: "add_product.php",
                data: {"name": name, "weight": weight, "volume": volume}
                }).done(function(response){
                    alert(response)
                });

                location.reload();
            })
    
        })
        </script> 
</body>
</html>