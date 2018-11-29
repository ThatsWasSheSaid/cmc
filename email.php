<?php
    header('Access-Control-Allow-Origin: *');
    require_once("plugins/PHPMailer-master/src/PHPMailer.php");
    require_once("plugins/PHPMailer-master/src/Exception.php");
    require_once("plugins/PHPMailer-master/src/SMTP.php");
    set_time_limit(3600);
    



//Configurações

    //configurações do usuario e servidor de email
    $host = 'smtpout.secureserver.net';
    $porta = 80;
//caso o servidor necessite de usuario/senha
    $autenticar = true;
    $usuario = 'contato@avannt.net';
    $senha = 'Aezakmi3#';
//configurações do Email
    $rementente = 'no-reply@avannt.net';
    $reply = 'no-reply@avannt.net';
//domino/ip do site (para o uso das imagens do site como o logo)
    $dominioDoSite = 'http://127.0.0.1/aula/cmc';
//fim das Configurações








if(isset($_GET['email'])){
    if($_GET['tipo']=='cadastro'){
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = $host;
        $mail->SMTPAuth = $autenticar;
        $mail->Username = $usuario;
        $mail->Password = $senha;
        $mail->Port = $porta;
        $mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

        $mail->setFrom($rementente);
        $mail->addReplyTo($reply);
        $mail->addAddress($_GET['email']);
        $mail->isHTML(true);

        $mail->Subject = 'Cadastro realizado com sucesso!';
        
        $tituloEmail = 'Cadastro realizado com sucesso!';
        $textoEmail = '<h2>Parabens! seu cadastro foi realizado com sucesso!</h2>';

        $mail->Body = "<html>
        <!-- CORPO -->
        <body style='height: 100%;background-color: rgb(247, 247, 247);'>
            <!-- CONTEUDO INTERNO -->
            <div class='content' style='background-color:white;width: 90%;height: 97%;border-color: rgb(189, 189, 189);border-width: 1px;border-style:  solid;margin: 4%;text-align:center;'>
                <!-- LOGO -->
                <img src='{$dominioDoSite}/_assets/img/logo.png' style='padding: 5%;width: 32%;'>
                <!-- TITULO -->
                <div style='background-color: #ffc107;height: 150px;'>
                    <!-- H1 TITULO -->
                    <h1 style='font-family: Helvetica,Arial,sans-serif;font-size: 25px;color: white;margin: 0px;padding: 60px;height: calc(100% - 120px);'>       {$tituloEmail}</h1>
                </div>
                <!-- Conteudo -->
                <div style='font-family: Helvetica,Arial,sans-serif;padding: 75px 70px;color: #004548;'>
                    {$textoEmail}
                </div>
            </div>
        </body>
    </html>";

        if(!$mail->send()) {
            echo 'Não foi possível enviar a mensagem.<br>';
            echo 'Erro: ' . $mail->ErrorInfo;
        } else {
            header("location: index.php?error=success#cadastro");
        }
    }elseif ($_GET['tipo']=='senha') {

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = $host;
        $mail->SMTPAuth = $autenticar;
        $mail->Username = $usuario;
        $mail->Password = $senha;
        $mail->Port = $porta;
        $mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

        $mail->setFrom($rementente);
        $mail->addReplyTo($reply);
        $mail->addAddress($_GET['email']);
        $mail->isHTML(true);

        $token = $_GET['token'];
        $link = "$dominioDoSite/login.php?action=trocaSenha&token=$token&email=".$_GET['email'];
        echo $link;

        $mail->Subject = 'Recuperação de Senha.';
        $tituloEmail = 'Recuperação de Senha.';
        $textoEmail = "Recebemos a sua solicitação para recuperação da senha, caso você não tenha solicitado Ignore este Email <br>
                        Caso tenha solicitado <a href='{$link}'>Clique Aqui</a> ou cole este link no seu navegador: {$link}";

        $mail->Body = "<html>
        <!-- CORPO -->
        <body style='height: 100%;background-color: rgb(247, 247, 247);'>
            <!-- CONTEUDO INTERNO -->
            <div class='content' style='background-color:white;width: 90%;height: 97%;border-color: rgb(189, 189, 189);border-width: 1px;border-style:  solid;margin: 4%;text-align:center;'>
                <!-- LOGO -->
                <img src='{$dominioDoSite}/_assets/img/logo.png' style='padding: 5%;width: 32%;'>
                <!-- TITULO -->
                <div style='background-color: #ffc107;height: 150px;'>
                    <!-- H1 TITULO -->
                    <h1 style='font-family: Helvetica,Arial,sans-serif;font-size: 25px;color: white;margin: 0px;padding: 60px;height: calc(100% - 120px);'>       {$tituloEmail}</h1>
                </div>
                <!-- Conteudo -->
                <div style='font-family: Helvetica,Arial,sans-serif;padding: 75px 70px;color: #004548;'>
                    {$textoEmail}
                </div>
            </div>
        </body>
    </html>";

        if(!$mail->send()) {
            echo 'Não foi possível enviar a mensagem.<br>';
            echo 'Erro: ' . $mail->ErrorInfo;
            header("location: login.php?action=recuperacao&msg=<p style='color: red;'>Email com link de Recuperação não enviado!</p>");
        } else {
            header("location: login.php?action=recuperacao&msg=Email com link de Recuperação enviado!");
        }
    }else{
        echo "tipo não definido";
    }
}else{
    die('Erro ao enviar email: Email não informado!');
}


?>