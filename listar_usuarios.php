<?php
   // Database Connection
   include_once 'conexao.php';

   //Valor de leitura
   $draw = $_POST['draw'];
   $row = $_POST['start'];
   $rowperpage = $_POST['length']; //Exibir linhas por página
   $columnIndex = $_POST['order'][0]['column']; //Indice da coluna
   $columnName = $_POST['columns'][$columnIndex]['data']; //Nome da coluna
   $columnSortOrder = $_POST['order'][0]['dir']; //Ordem asc ou desc
   $searchValue = $_POST['search']['value']; //Valor de pesquisa

   $searchArray = array();

   //Pesquisa
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (nome LIKE :nome OR
           email LIKE :email OR
           perfil LIKE :perfil) ";
      $searchArray = array(
           'nome'=>"%$searchValue%",
           'email'=>"%$searchValue%",
           'perfil'=>"%$searchValue%"
      );
   }

   //Número total de registros sem filtro
   $stmt = $conn->prepare("SELECT COUNT(*) AS qtd_total_usuarios FROM usuarios");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['qtd_total_usuarios'];

   //Número total de registros com filtro
   $stmt = $conn->prepare("SELECT COUNT(*) AS qtd_usuarios_com_filtro FROM usuarios WHERE 1 ".$searchQuery);
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['qtd_usuarios_com_filtro'];

   //Buscar registros
   $stmt = $conn->prepare("SELECT * FROM usuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit, :offset");

   //Registros vinculados
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search, PDO::PARAM_STR);
   }

   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {
      $data[] = array(
         "id"=>$row["id"],
         "nome"=>$row['nome'],
         "email"=>$row['email'],
         "perfil"=>$row['perfil']
      );
   }

   //Resposta
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => intval($totalRecords),
      "iTotalDisplayRecords" => intval($totalRecordwithFilter),
      "aaData" => $data
   );
   //var_dump($response);
   echo json_encode($response);