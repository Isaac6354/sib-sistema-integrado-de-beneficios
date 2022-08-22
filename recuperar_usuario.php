<?php
   // Database Connection
   include_once 'conexao.php';

   //Valor de leitura
   $id = $_GET['id'];

   //Buscar usuario
   $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");

   $stmt->bindParam(1, $id);
   $stmt->execute();
   $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

   foreach ($results as $row){
      $data = array(
         "id"=>$row["id"],
         "nome"=>$row['nome'],
         "email"=>$row['email'],
         "perfil"=>$row['perfil'],
         "senha_usuario"=>$row['senha_usuario']
      );
   }

   //Resposta
   echo json_encode($data);