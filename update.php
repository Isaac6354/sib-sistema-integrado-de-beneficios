<?php
	// Database Connection
	include_once 'conexao.php';

	$data = array(
		"id" => $_POST["id"],
		"nome" => $_POST['nome'],
		"email" => $_POST['email'],
		"perfil" => $_POST['perfil'],
		"senha_usuario" => $_POST['senha_usuario']
	);

	$sql = "UPDATE usuarios SET nome=:nome, email=:email, senha_usuario=:senha_usuario, perfil=:perfil WHERE id=:id";

	$statement = $conn->prepare($sql);

	$statement->bindParam(':id', $data['id'], PDO::PARAM_INT);
	$statement->bindParam(':nome', $data['nome']);
	$statement->bindParam(':email', $data['email']);
	$statement->bindParam(':senha_usuario', $data['senha_usuario']);
	$statement->bindParam(':perfil', $data['perfil']);

	if ($statement->execute()) {
		//echo "Usuario atualizado com sucesso!";
		$retorno = 1;
	}else{
		$retorno = 0;
	}
	echo json_encode($retorno);