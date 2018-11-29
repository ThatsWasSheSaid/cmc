<?php
    header('Access-Control-Allow-Origin: *');
    //inports
    require_once('conexao.php');
    //testa se esta logado
    session_start();
    if(!isset($_SESSION['usuarioLogado'])){
        header('location: login.php?action=login');
    }
    header('Content-Type: text/html; charset=utf-8');
?>
<?php
    if(isset($_GET['idprojeto'])){

        $idProjeto = $_GET['idprojeto'];
        $tipo = 'tijolo';

        $pastasSQL = "SELECT * FROM pastas WHERE idprojeto = $idProjeto AND tipo = '$tipo'";
        $pastasQ = mysqli_query($conn,$pastasSQL);

        if(!$pastasQ){
            die("Falha na consulta ao banco. (pastasQ)");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CMC - Projetos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <!-- normalize.css -->
    <link rel="stylesheet" type="text/css" media="screen" href="_css/editaProjeto.css" />
    <!-- main.css -->
    <link rel="stylesheet" type="text/css" media="screen" href="_css/main.css" />
    <!-- editaProjeto.css -->
    <link rel="stylesheet" type="text/css" media="screen" href="_css/normalize.css" />
    <!-- JQuery -->
    <script src="_js/jquery.js"></script>
    <!-- JQuery Validate -->
    <script src="_js/jquery-validator.js"></script>
    <!-- editaProjeto -->
    <script src="_js/editaProjeto.js"></script>
</head>
<body>
<input type="hidden" name="idProjeto" id="idProjeto" value="<?php echo $idProjeto?>">
<?php require_once('pages/navbar.php')?>
    <div class="wrapper">
        <div class="tool-bar">
            <a href="editaProjeto.php?idprojeto=<?php echo $idProjeto?>"><div class="icone tipo-active"">
                <img src="_assets/img/tijolo-laranja.png">
            </div></a>

            <a href="editaPisos.php?idprojeto=<?php echo $idProjeto?>"><div class="icone"">
                <img style="margin-top:18px" src="_assets/img/piso.png">
            </div></a>

            <a href="editaFerragem.php?idprojeto=<?php echo $idProjeto?>"><div class="icone"">
                <img style="margin-top:18px" src="_assets/img/ferragem.png">
            </div></a>

            <a href="montaPDF.php?idprojeto=<?php echo $idProjeto?>"><div style="margin-top: 190px;" class="icone"">
                <img style="margin-top:15px;" src="_assets/img/pdf.png">
            </div></a>
        </div>
        <div class="pastas">
            <div class="cabecalho">Pastas</div>
            <?php while($i = mysqli_fetch_assoc($pastasQ)):?>
                <div class="pasta">
                    <div class="titulo">
                        <span class="arrow_right"></span> 
                        <p><?php echo $i['descricao'];?></p>
                        <div class="btn_excluirPasta"  onclick="excluiPasta(<?php echo $i['idpasta'] ?>)"><?php include('_assets/img/delete.svg')?></div> 
                    </div>
                        <div class="itens">
                            <?php
                                $itensSQL = "SELECT * FROM itens WHERE idpasta = ".$i['idpasta'];
                                $itensQ = mysqli_query($conn,$itensSQL);
                        
                                if(!$itensQ){
                                    die("Falha na consulta ao banco. (itensQ)");
                                }
                            ?>
                            <?php while($j = mysqli_fetch_assoc($itensQ)):?>
                                <div class="item" onclick="buscaDadosItem(<?php echo $j['iditem'];?>);"><?php echo $j['descricao']?></div>
                            <?php endwhile; ?>
                            <div class="item add-item" onclick="addItem(<?php echo $i['idpasta'] ?>)">Adicionar Item +</div>
                        </div>
                </div>
            <?php endwhile; ?>
            <div class="add-pasta" onclick="addPasta()">Adicionar Pasta +</div>
        </div>
        <div class="edit-Tijolo" id="edit-Tijolo">
            <div class="tijolos" id="listaTijolos"></div>
            <form action="" method="post" id="form-tijolo">
                <input type="hidden" name="iditem" id="iditem" value="">
                <input type="hidden" name="idpasta" id="idpasta" value="">
                <input type="hidden" name="descricao" id="descricao" value="">
                <input type="hidden" name="total" id="total" value="">
                <input type="hidden" name="tijolo" id="tijolo" value="">
                <div class="linha">
                    <label for="massa">Espessura da Massa</label>
                    <input type="decimal" name="massa" id="massa" value="">Centimetro
                </div>
                <div class="linha">
                    <label for="altParede">Altura da Parede</label>
                    <input type="decimal" name="altParede" id="altParede" value="">Metros
                </div>
                <div class="linha">
                    <label for="altParede">Comprimento da Parede</label>
                    <input type="decimal" name="comParede" id="compParede" value="">Metros
                </div>
                <div class="linha">
                    <input class="checkbox" type="checkbox" name="deitado" id="deitado">Tijolo Deitado
                </div>
                <button style="background-color: red" id="btn_excluir">Excluir</button>
                <button type="reset">Limpar</button>
                <button style="background-color: green" type="submit">Salvar</button>
            </form>
            <div class="resultado">
                <p>Total: <span id="totalTijolos"></span> Tijolos</p>
                <p>SubTotal: <span id="subTotalTijolos"></span> Tijolos</p>
            </div>
        </div>
    </div>
    <div class="addTijolo" id="addTijolo">
        <div class="addTijolo-form">
            <form id="form-addTijolo">
                <h2>Cadastrar Tijolo</h2>
                <input type="text" name="nomeTijolo" id="nomeTijolo" placeholder="Nome do Tijolo">
                <input type="decimal" name="espessuraTijolo" id="espessuraTijolo" placeholder="Espessura" value="">
                <input type="decimal" name="alturaTijolo" id="alturaTijolo" placeholder="Altura" value="">
                <input type="decimal" name="comprimentoTijolo" id="comprimentoTijolo" placeholder="Comprimento" value="">
                <button type="button" class="danger" onclick="closeTijolo();" id="cancelaTijolo">Cancelar</button>
                <button type="submit" id="cadastrarTijolo">Cadastrar Tijolo</button>
            </form>
        </div>
    </div>

    <div class="addPasta" id="addPasta">
        <div class="addPasta-form">
            <form id="form-addPasta">
                <h2>Cadastrar Pasta</h2>
                <input type="hidden" name="idProjeto" value="<?php echo $idProjeto?>">
                <input type="hidden" name="tipo" value="<?php echo $tipo?>">
                <input type="text" name="nomePasta" id="nomePasta" placeholder="Nome da Pasta">
                <button type="button" class="danger" onclick="closePasta();" id="cancelaPasta">Cancelar</button>
                <button type="submit" id="cadastrarPasta">Cadastrar Pasta</button>
            </form>
        </div>
    </div>

    <div class="addItem" id="addItem">
        <div class="addItem-form">
            <form id="form-addItem">
                <h2>Cadastrar Item</h2>
                <input id="addItemIdPasta" type="hidden" name="idPasta" value="">
                <input type="text" name="nomeItem" id="nomeItem" placeholder="Nome do Item">
                <button type="button" class="danger" onclick="closeItem();" id="cancelaItem">Cancelar</button>
                <button type="submit" id="cadastrarItem">Cadastrar Item</button>
            </form>
        </div>
    </div>
    
</body>
</html>