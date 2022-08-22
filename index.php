<?php
session_start();
//Limpando o buffer de saída, para evitar o erro de redirecionamento
ob_start();
include_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>SIB - Login</title>
  </head>
  <body>
    <div class="container-sm">
    <div id="container-imagem">
    
    <?php
      //Exemplo para criptografar a senha
      //echo password_hash(123456, PASSWORD_DEFAULT);
    ?>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if(!empty($dados['SendLogin'])){
      //var_dump($dados);
      $query_usuario = "SELECT id, nome, email, senha_usuario
                        FROM usuarios
                        WHERE email =:email
                        LIMIT 1";
      $result_usuario = $conn->prepare($query_usuario);
      $result_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
      $result_usuario->execute();

      //Se a quantidade de usuários for diferente de zero, é porque ele achou um usuário
      if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
          $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
          //var_dump($row_usuario);

          //Verificando se a senha que o usuário está enviando é igual a senha cadastrada no BD
          if($dados['senha_usuario'] == $row_usuario['senha_usuario']){
            $_SESSION['id'] = $row_usuario['id'];
            $_SESSION['nome'] = $row_usuario['nome'];
            header("Location: cadastro-usuarios.php");
          }else{
            var_dump("password_verify- Campo senha:".$dados['senha_usuario']."Senha do banco:".$row_usuario['senha_usuario'].", São iguais?");
            var_dump("Resultado: ".password_verify($dados['senha_usuario'], $row_usuario['senha_usuario']));
            var_dump("Hash da Senha: ".password_hash($row_usuario['senha_usuario'],PASSWORD_DEFAULT));
            $hash = password_hash($row_usuario['senha_usuario'],PASSWORD_DEFAULT);
            var_dump("...");
            var_dump("password_verify- Campo senha:".$dados['senha_usuario']."Senha com hash do banco:".$hash.", São iguais?");
            var_dump(password_verify($dados['senha_usuario'], $hash));

            $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: email ou senha inválidos!</p>";
          }
      }else{
          $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: email ou senha inválidos!</p>";
      }
    }
    if(isset($_SESSION['msg'])){
      echo $_SESSION['msg'];
      unset ($_SESSION['msg']);
    }
    ?>

    <div class="mx-auto" style="width: 400px;">
        <form method="POST" action="">
            <div class="shadow-lg p-3 mb-5 bg-body rounded">
                <div id="logo_sib">
                  <img src="./images/logo_sib.png" class="img-fluid" alt="logo do Sib">
                </div>
                <div id="inputs-labels">
                <div class="mb-3 row">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Digite o email do usuário"
                        value="<?php if(isset($dados['email'])){echo $dados['email'];} ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="exampleInputPassword1" class="form-label">Senha</label>
                    <div class="col-sm-12">
                        <input type="password" class="form-control" id="exampleInputPassword1" name="senha_usuario" placeholder="Digite a senha do usuário"
                        value="<?php if(isset($dados['senha_usuario'])){echo $dados['senha_usuario'];} ?>" required>
                    </div>
                </div>
                </div>
                <div class="d-grid gap-2">
                    <input type="submit" class="btn btn-primary" value="Entrar" name="SendLogin">
                </div>
                <p class="h6">ESQUECEU SUA SENHA? <a href="#">CLIQUE AQUI</a></p>
                <div id="footer"></div>
            </div>
        </form>
    </div>
    </div>  
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>