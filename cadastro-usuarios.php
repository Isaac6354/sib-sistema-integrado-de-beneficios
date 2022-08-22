<?php
session_start();
ob_start();
include_once 'conexao.php';

//Quando o usuário não estiver logado, redireciono ele para minha index.php
if ((!isset($_SESSION['id'])) and (!isset($_SESSION['nome']))) {
    $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Necessário realizar o login para acessar a página!</p>";
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico">
    <link rel="stylesheet" type="text/css" href="./css/cadastro-usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>SIB - Cadastro Usuários</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container-fluid bg-primary bg-gradient rounded-3 text-white" style="padding: 10px;margin-top: 2%;">
                    <div class="row">
                        <div class="col fw-bold">
                            Cadastro de Usuários
                        </div>
                        <div class="col">
                            <p class="text-end">Bem vindo <?php echo $_SESSION['nome']; ?>!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <div class="container-fluid bg-light" style="padding: 20px;">
                    <div>
                        <div class="alert alert-success alert-dismissible" id="sucesso" style="display:none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        </div>
                        <form method="POST" action="save.php" id="form">
                            <input class="form-control" type="hidden" id="id" name="id">
                            <div class="row">
                                <div class="col">
                                    <label for="nome">Nome</label>
                                    <input class="form-control" type="text" id="nome" name="nome" required>
                                </div>
                                <div class="col">
                                    <label for="email">Email</label>
                                    <input class="form-control" type="text" id="email" name="email" required>
                                </div>
                                <div class="col">
                                    <label for="senha_usuario">Senha</label>
                                    <input class="form-control" type="password" id="senha_usuario" name="senha_usuario" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="perfil">Perfil</label>
                                    <input class="form-control" type="text" id="perfil" name="perfil" required>
                                </div>
                                <div class="col d-grid gap-2">
                                    <button type="submit" style="height: 40px;margin-top: 5%;" class="btn btn-primary" id="btnSubmit">Cadastrar</button>
                                    <button type="button" style="height: 40px;margin-top: 5%; display: none;" class="btn btn-primary" id="btnUpdateSubmit">Atualizar</button>
                                </div>
                                <div class="col d-grid gap-2">
                                    <button type="button" style="height: 40px;margin-top: 5%; display: none;" class="btn btn-secondary" id="btnCancelUpdate">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col">
                    <div class="container-fluid">
                        <h3>Listar Usuários</h3>
                        <br>
                        <table id="listar-usuario" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Perfil</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de confirmação para exclusão de usuário -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Excluir Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja excluir este usuário? Esta ação não poderá ser revertida!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDelete" class="btn btn-danger">Sim</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmação que o usuário foi excluído -->
        <div class="modal fade" id="successModalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Usuário Removido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Usuário removido com sucesso!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmação que o usuário foi atualizado -->
        <div class="modal fade" id="successModalUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Usuário Atualizado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Usuário atualizado com sucesso!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="./js/cadastro-usuarios.js"></script>
        <script src="./js/scripts.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                const urlParams = new URLSearchParams(window.location.search);
                // acessamos o valor que desejamos
                const myParam = urlParams.get("redirect");
                if (myParam) {
                    $("#successModalDelete").modal('show');
                }

                const urlParamsAfterUpdate = new URLSearchParams(window.location.search);

                // acessamos o valor que desejamos
                const myParamAfterUpdate = urlParamsAfterUpdate.get("redirectAfterUpdate");
                if (myParamAfterUpdate) {
                    $("#successModalUpdate").modal('show');
                }

                $('#listar-usuario').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "listar_usuarios.php",
                        "type": "POST"
                    },
                    "columns": [{
                            data: "nome"
                        },
                        {
                            data: "email"
                        },
                        {
                            data: "perfil"
                        },
                        {
                            render: function(data, type, row, meta) {
                                return "<button onclick='getUsuarioById(" + row.id + ")' class='btn btn-warning text-white' action='edit'><i class='bi bi-pencil-fill'></i></button>" +
                                    "&nbsp;<button onclick='deleteUsuario(" + row.id + ")' data-bs-toggle='modal' data-bs-target='#exampleModal' class='btn btn-danger text-white' action='delete'><i class='bi bi-trash-fill'></i></button>";
                            }
                        }
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                    }
                });
            });


            /**
             * Inicia o cancelamento da edicao, limpa os campos e retorna o botao de cadastro.
             */
            $("#btnCancelUpdate").click(function() {
                // inicia a manipulação do FORM
                $("#btnSubmit").fadeIn(); // faz aparecer o botao de cadastro.
                $("#btnUpdateSubmit").fadeOut(); // faz desaparecer o botao de update.
                $("#btnCancelUpdate").fadeOut(); // faz desaparecer o botao de cacelar.
                // limpa o preenchimento dos campos
                $("#id").val("").fadeIn();
                $("#nome").val("").fadeIn();
                $("#email").val("").fadeIn();
                $("#perfil").val("").fadeIn();
                $("#senha_usuario").val("").fadeIn();
            });

            /**
             * Método para recuperar um usuário.
             */
            function getUsuarioById(val) {
                // implementar uma chamada ajax para recuperar um usuário pelo id
                $.get("recuperar_usuario.php?id=" + val, function(resultado) {
                    const response = JSON.parse(resultado);

                    // inicia a manipulação do FORM
                    $("#btnSubmit").fadeOut(); // faz desaparecer o botao de cadastro.
                    $("#btnUpdateSubmit").fadeIn(); // faz aparecer o botao de update.
                    $("#btnCancelUpdate").fadeIn(); // faz aparecer o botao de cacelar.
                    // inicia o preenchimento dos campos
                    $("#id").attr("value", response.id).fadeIn();
                    $("#nome").attr("value", response.nome).fadeIn();
                    $("#email").attr("value", response.email).fadeIn();
                    $("#perfil").attr("value", response.perfil).fadeIn();
                    $("#senha_usuario").attr("value", response.senha_usuario).fadeIn();
                })
            }

            /**
             * Método para atualizar um usuário.
             */
            $("#btnUpdateSubmit").click(function() {
                var id = $('#id').val();
                var nome = $('#nome').val();
                var email = $('#email').val();
                var perfil = $('#perfil').val();
                var senha_usuario = $('#senha_usuario').val();
                // AJAX request
                $.ajax({
                    url: 'update.php',
                    type: 'post',
                    data: {
                        id: id,
                        nome: nome,
                        email: email,
                        perfil: perfil,
                        senha_usuario: senha_usuario
                    }
                }).done(function(response, textStatus, jqXHR) {
                    const retorno = JSON.parse(response);
                    if (retorno == 1) {
                        location.href = 'cadastro-usuarios.php?redirectAfterUpdate=true';
                    } else {
                        alert("ID inválido.");
                    }
                });
            });

            /**
            * Método para adicionar um usuário.
            */
            function addUsuario(){
                $("#btnSubmit").click(function(){
                    var nome = $('#nome').val();
                    var email = $('#email').val();
                    var perfil = $('#perfil').val();
                    var senha_usuario = $('#senha_suario').val();
                    
                    if(!nome || !email || !perfil || !senha_usuario){
                        $('.error').show(3000).html("Por favor, preencha todos os campos.").delay(3200).fadeOut(3000);
                    }else{
                    if(nome){
                        var url = 'update.php';
                    }else{
                        var url = 'save.php';
                    }
                    $.post( url, {nome: nome, email: email, perfil: perfil, senha_usuario: senha_usuario})
                        .done(function(data){
                        if(data > 0){
                            $('.success').show(3000).html("Usuário cadastrado com sucesso.").delay(2000).fadeOut(1000);
                        }else{
                            $('.error').show(3000).html("Usuário não cadastrado. Tente novamente!").delay(2000).fadeOut(1000);
                        }
                        setTimeout(function(){
                            window.location.reload(1);
                        }, 15000);
                    });
                    }
                });
            };

            /**
             * Método para deletar um usuário.
             */
            function deleteUsuario(val) {
                $("#confirmDelete").click(function() {
                    $.get("delete.php?id=" + val, function(resultado) {
                        const response = JSON.parse(resultado);
                        if (response == 1) {
                            location.href = 'cadastro-usuarios.php?redirect=true';
                        }
                    })
                });
            }
        </script>
</body>
<footer>
    <a class="btn btn-danger" href="sair.php">Sair</a>
</footer>
</html>