<?php
  // Database Connection
	include_once 'conexao.php';

  $id = $_GET['id'];
  
  try {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $retorno = 1;
        } else {
            $retorno = 0;
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
    echo json_encode($retorno);
?>