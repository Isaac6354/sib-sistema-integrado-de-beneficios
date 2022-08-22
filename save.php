<?php
	// Database Connection
	include_once 'conexao.php';
  
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $perfil = $_POST['perfil'];
  $senha_usuario = $_POST['senha_usuario'];
  
	try {
			$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, perfil, senha_usuario) VALUES (?, ?, ?, ?)");
      $stmt->bindParam(1, $nome);
      $stmt->bindParam(2, $email);
      $stmt->bindParam(3, $perfil);
      $stmt->bindParam(4, $senha_usuario);

      if ($stmt->execute()) {
      	if ($stmt->rowCount() > 0) {
        	echo "Dados cadastrados com sucesso!";
        }else{
          echo "Erro ao tentar fazer o cadastro";
        }
      }else{
            throw new PDOException("Não foi possível executar a declaração sql");
      }
      header("Location: cadastro-usuarios.php");
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
  ?>