<?php require_once("conexao.php");?>

<?php 
// Função de Login
	session_start();

    if(isset($_GET["action"]) && $_GET["action"]=='login'){
        if(isset($_POST["email"]) && isset($_POST["senha"])){

            $email = $_POST["email"];
            $senha = $_POST["senha"];
            
            $senhaMD5 = md5("754f9968bf5f5f68d7dea029889b7415".$senha);
            
            $login = "SELECT * FROM usuarios WHERE email = '$email' AND senha='$senhaMD5'";
            
            $acesso = mysqli_query($conn,$login);

            if(!$acesso){
                die("Falha na consulta ao banco.");
            }
            
            $informacao = mysqli_fetch_assoc($acesso);
            
            if(empty($informacao)){
                $mensagem = "Email ou Senha Incorretos!";
            }else{
                $_SESSION["usuarioLogado"] = $informacao["idusuario"];			
                header("location: index.php");
            }
        }
    }
?>
<?php
// Função de Cadastro
    if(isset($_GET["action"]) && $_GET["action"]=='cadastro'){
        if(isset($_POST['nome']) 
            && isset($_POST['email']) 
                && isset($_POST['senha'])){

            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $senhaMD5 = md5("754f9968bf5f5f68d7dea029889b7415".$senha);

            $checkEmailSQL = "SELECT * FROM usuarios WHERE email = '$email'";
            $checkEmailQ = mysqli_query($conn,$checkEmailSQL);
            if(mysqli_num_rows($checkEmailQ)>0){
                $mensagem = "Email já Cadastrado!";
            }else{
                $cadastroSQL = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome','$email','$senhaMD5')";

                $cadastroQ = mysqli_query($conn,$cadastroSQL);
                if(!$cadastroQ){
                    $mensagem = "Erro ao realizar Cadastro!";
                }else{

                    $idUsuarioSQL = "SELECT idusuario FROM usuarios WHERE email = '$email'";
                    $idUsuarioQ = mysqli_query($conn,$idUsuarioSQL);
        
                    if(!$idUsuarioQ){
                        die("Falha na consulta ao banco.");
                    }
                    $idUsuario = mysqli_fetch_assoc($idUsuarioQ);

                    $_SESSION["usuarioLogado"] = $idUsuario["idusuario"];

                    header("location: index.php");
                }
            }
        }
    }
?>

<?php
//Função de recuperação de senha
    if(isset($_GET["action"]) && $_GET["action"]=='recuperacao'){
        //gera token e envia email
        if(isset($_POST['email']) && !isset($_GET['token'])){

            $email = $_POST['email'];
            $checkEmailSQL = "SELECT * FROM usuarios WHERE email = '$email'";
            $checkEmailQ = mysqli_query($conn,$checkEmailSQL);

            $usuario = mysqli_fetch_assoc($checkEmailQ);
            $email = $usuario['email'];
            $idUsuario = $usuario['idusuario'];
            if(mysqli_num_rows($checkEmailQ)>0){                
                $tokenNovo = sha1(uniqid( mt_rand(), true));
                $dataExpiracaoNova = new DateTime();
                date_modify($dataExpiracaoNova, '+15 minutes');
                $dataExpiracaoNova = $dataExpiracaoNova->format("y-m-d h:i:s");
                
                $tokenSQL = "INSERT INTO `recovery` (`idusuario`, `email`, `token`, `expiraem`) VALUES ('$idUsuario','$email','$tokenNovo','$dataExpiracaoNova')";
                $tokenSQL .= " ON DUPLICATE KEY UPDATE token='$tokenNovo', expiraem='$dataExpiracaoNova'";

                $tokenQ = mysqli_query($conn,$tokenSQL);
                if(!$tokenQ){
                    die("Erro no banco de dados");
                }
                header('location: email.php?email='.$email.'&tipo=senha&token='.$tokenNovo); 
            }else{
                $mensagem = "Email não Cadastrado!";
            }
        }elseif (isset($_GET['msg'])) {
            $mensagem = "<p style='color: green;'>".$_GET['msg']."</p>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CMC - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="stylesheet" type="text/css" media="screen" href="_css/login.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="_css/normalize.css" />
    <script src="_js/jquery.js"></script>
    <script src="_js/login.js"></script>
    <script src="_js/jquery-validator.js"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Formulario de Login -->
        <?php if(isset($_GET['action']) && $_GET['action'] == 'login'): ?>
            <div class="login-wrapper">
            <a href="login.php?action=login"><img class="logo" src="_assets/img/logo.png" alt="Logo"></a>
                <div class="login">
                    <form action="login.php?action=login" method="post" id="form-login">
                        <input type="email" name="email" id="email" placeholder="Email">
                        <input type="password" name="senha" id="senha" placeholder="Senha">
                        <button type="submit">Entrar</button>
                    </form>
                    <a class="esqueciASenha" href="login.php?action=recuperacao">Esqueci a senha!</a>
                    <p class="msg" id="msg"><?php if(isset($mensagem)){echo $mensagem;}?></p>
                </div>
                <p class="ou">OU</p>
                <div class="cadastro">
                    <a href="login.php?action=cadastro"><button>Cadastrar-se</button></a>
                </div>
            </div>
        <?php endif;?>
        <!-- !Formulario de Login -->
        <!-- Formulario de Cadastro -->
        <?php if(isset($_GET['action']) && $_GET['action'] == 'cadastro'): ?>
            <div class="login-wrapper">
            <a href="login.php?action=login"><img class="logo" src="_assets/img/logo.png" alt="Logo"></a>
                <div class="login">
                    <form action="login.php?action=cadastro" method="post" id="form-cadastro">
                        <input type="text" name="nome" id="nome" placeholder="Nome">
                        <input type="email" name="email" id="email" placeholder="Email">
                        <input type="password" name="senha" id="senha" placeholder="Senha">
                        <input type="password" name="senha2" id="senha2" placeholder="Repita a Senha">
                        <button type="submit">Cadastrar-se</button>
                    </form>
                    <p class="error" id="msg"><?php if(isset($mensagem)){echo $mensagem;}?></p>
                </div>
            </div>
        <?php endif;?>
        <!-- !Formulario de Login -->
        <!-- Formulario de Recuperação de Senha -->
        <?php if(isset($_GET['action']) && $_GET['action'] == 'recuperacao'): ?>
            <div class="login-wrapper">
            <a href="login.php?action=login"><img class="logo" src="_assets/img/logo.png" alt="Logo"></a>
                <div class="login">
                    <form action="login.php?action=recuperacao" method="post" id="form-recuperacao">
                        <input type="email" name="email" id="email" placeholder="Email">
                        <button type="submit">Recuperar Senha</button>
                    </form>
                    <p class="msg" id="msg"><?php if(isset($mensagem)){echo $mensagem;}?></p>
                </div>
            </div>
        <?php endif;?>
        <!-- !Formulario de Recuperação de Senha -->
        <!-- Formulario de Recuperação de Senha -->
        <?php
        // Função de troca de senha
            if(isset($_GET["action"]) && $_GET["action"]=='trocaSenha'){
                if(isset($_GET['token']) && isset($_GET['email'])){

                    //mostra tela de troca

                    $token = $_GET['token'];
                    $email = $_GET['email'];
            
                    $tokenSQL = "SELECT * FROM `recovery` WHERE email = '$email' AND token='$token'";
                    $tokenQ = mysqli_query($conn,$tokenSQL);
        
                    if(!$tokenQ){
                        die("Falha na consulta ao banco.");
                    }
                    
                    $token = mysqli_fetch_assoc($tokenQ);

                    if(!empty($token)){
                        $dataHoje = new DateTime();
                        $dataHoje = $dataHoje->format("y-m-d h:i:s");

                        if(strtotime($token['expiraem'])>strtotime($dataHoje)){
                            ?>
                            <div class="login-wrapper">
                                <a href="login.php?action=login"><img class="logo" src="_assets/img/logo.png" alt="Logo"></a>
                                <div class="login">
                                    <form action="login.php?action=trocaSenha" method="post" id="form-trocaSenha">
                                        <input type="hidden" name="email" id="email" value="<?php echo $token['email'];?>">
                                        <input type="password" name="senha" id="senha" placeholder="Senha">
                                        <input type="password" name="senha2" id="senha2" placeholder="Repita a Senha">
                                        <button type="submit">Atualizar Senha</button>
                                    </form>
                                    <p class="error" style="color:green" id="msg">Token Válido!</p>
                                </div>
                            </div>
                            <?php
                        }else{
                            //Token Expirado
                            ?>
                            <div class="login-wrapper">
                                <a href="login.php?action=login"><img class="logo" src="_assets/img/logo.png" alt="Logo"></a>
                                <div class="login">
                                    <p class="error" id="msg">Token Expirado!</p>
                                </div>
                            </div>
                            <?php
                        }
                    }else{
                        //Token Invalido
                        ?>
                            <div class="login-wrapper">
                                <a href="login.php?action=login"><img class="logo" src="_assets/img/logo.png" alt="Logo"></a>
                                <div class="login">
                                    <p class="error" id="msg">Token Inválido!</p>
                                </div>
                            </div>
                        <?php
                    }
                }else{
                    //troca a senha (no Banco)
                    if(isset($_POST['email']) && isset($_POST['senha'])){

                        $email = $_POST['email'];
                        $senha = $_POST['senha'];
                        $senhaMD5 = md5("754f9968bf5f5f68d7dea029889b7415".$senha);

                        $idUsuarioSQL = "SELECT idusuario FROM usuarios WHERE email = '$email'";
                        $idUsuarioQ = mysqli_query($conn,$idUsuarioSQL);
                    
                        if(!$idUsuarioQ){
                            die("Falha na consulta ao banco.");
                        }

                        $idUsuario = mysqli_fetch_assoc($idUsuarioQ);

                        $updateSenhaSQL = "UPDATE `usuarios` SET `senha`= '$senhaMD5' WHERE idusuario=".$idUsuario['idusuario'];
                        $updateSenhaQ = mysqli_query($conn,$updateSenhaSQL);
                    
                        if(!$updateSenhaQ){
                            die("Falha na consulta ao banco.");
                        }else{

                        $updateTokenSQL = "UPDATE `recovery` SET `token`= 'a' WHERE idusuario=".$idUsuario['idusuario'];
                        echo $updateTokenSQL;
                        $updateTokenQ = mysqli_query($conn,$updateTokenSQL);
                        
                        if(!$updateTokenQ){
                            die("Falha na consulta ao banco.");
                        }

                            header('location: login.php?action=login'); 
                        }
                    }
                }
            }
        ?>
        <!-- !Formulario de Recuperação de Senha -->
    </div>
</body>
</html>