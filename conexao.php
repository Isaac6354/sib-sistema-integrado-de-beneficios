<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_sib";
$port = 3306;

//Realizando a conexão com o banco de dados
try{
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);
    //echo "Conexão com o banco de dados realizada com sucesso!";
}catch(PDOException $err){
    echo "Erro: Conexão com o banco de dados não realizada com sucesso!. Erro gerado " . $err->getMessage();
}
?>