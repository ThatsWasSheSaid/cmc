$(document).ready( function() {

    $(".titulo").click(function(){
        if($(this).data('clicked')) {
            var cont = $(this).next();
            $(cont).slideUp();
            $(this).find('span').removeClass("arrow_down");
            $(this).find('span').addClass("arrow_right");
            $(this).data('clicked', false);
        }else{
            var cont = $(this).next();
            $(cont).slideDown();
            $(this).find('span').removeClass("arrow_right");
            $(this).find('span').addClass("arrow_down");
            $(this).data('clicked', true);
        }
    });

    $(".item").click(function(){
        if(!$(this).hasClass('active')) {
            $(".item").removeClass("active");
            $(this).addClass("active");
            $(".pasta").removeClass("active-border");
            $(this).parent().parent().addClass("active-border");
        }
    });

    $('#form-item').change(function(){
        calculaMaterial();
    });
    
    //  CADASTRAR PISO
    $("#form-addMaterial").validate({
        rules : {
            nomeMaterial:{
                required:true
            },
            comprimentoMaterial:{
                required:true
            }              
        },
        messages:{
            nomeMaterial:{
                required:"Escolha um nome para o seu Piso!",
            },
            comprimentoMaterial:{
                required:"Informe a comprimento do seu Piso!",
            }
        }
    });

    $("#form-addMaterial").submit(function() {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: 'scripts/ferragem.php?action=addMaterial',
            data: $('#form-addMaterial').serialize(),
            success: function() {
                closeMaterial();
                buscaMateriais();
            }
          });
    });


    //  CADASTRAR PASTA
    $("#form-addPasta").validate({
        rules : {
            nomePasta:{
                required:true
            }             
        },
        messages:{
            nomePasta:{
                required:"Escolha um nome para a sua Pasta!",
            }
        }
    });

    $("#form-addPasta").submit(function() {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: 'scripts/pastas.php?action=addPasta',
            data: $('#form-addPasta').serialize(),
            success: function() {
                location.reload();
            }
          });
    });

        //  CADASTRAR ITEM
        $("#form-addItem").validate({
            rules : {
                nomeItem:{
                    required:true
                }             
            },
            messages:{
                nomeItem:{
                    required:"Escolha um nome para o seu Item!",
                }
            }
        });
    
        $("#form-addItem").submit(function() {
            event.preventDefault();
    
            $.ajax({
                type: "POST",
                url: 'scripts/pastas.php?action=addItem',
                data: $('#form-addItem').serialize(),
                success: function(data) {
                    closeItem();
                    buscaDadosItem(data);
                }
              });
        });

        //SALVAR ITEM
        $("#form-material").validate({
            rules : {
                material:{
                    required:true
                },
                qtdeBarras:{
                    required:true
                },
                comprimentoCol:{
                    required:true
                }          
            },
            messages:{
                material:{
                    required:"Escolha um tipo de Ferragem!",
                },
                qtdeBarras:{
                    required:"Informe a quantidade de barras da viga/coluna!",
                },
                comprimentoCol:{
                    required:"Informe o comprimento da viga/coluna!",
                }
            }
        });
    
        $("#form-item").submit(function() {
            event.preventDefault();
    
            $.ajax({
                type: "POST",
                url: 'scripts/ferragem.php?action=salvarItem',
                data: $('#form-item').serialize(),
                success: function(data) {
                    console.log(data);
                    
                    console.log($('#form-item').serialize());
                    atualizaTotal($('#idProjeto').val());
                }
              });
        });

        $('#btn_excluir').click(function(){
            $.ajax({
                type: "POST",
                url: 'scripts/pisos.php?action=excluirItem',
                data: {iditem : $('#iditem').val()},
                success: function(data) {
                    console.log(data);
                    location.reload();
                }
              });
        });
});

function excluiPasta(idPasta){
    if (confirm("Excluir está pasta apagará todos os itens dentro dela!")) {
        $.ajax({
            type: "POST",
            url: 'scripts/pastas.php?action=excluirPasta',
            data: {idPasta : idPasta},
            success: function() {
                location.reload();
            }
        });
    }
}

function buscaMateriais(){

    $.ajax({
        url: "http://localhost/aula/cmc/scripts/ferragem.php?action=get",
        dataType: "json",
        async: false,
        success: function(json){
            materiais = json;

                $('#listaMateriais').empty();

                for(i=0;i<materiais.length;i++){
                    $('#listaMateriais').append('<div class="material" id="material-'+materiais[i].idmaterial+'" onclick="selectMaterial('+materiais[i].idmaterial+')"></div>');
                    
                    $('#material-'+materiais[i].idmaterial).append('<img class="material-img" id="material-'+materiais[i].idmaterial+'-img" src="_assets/img/ferragem.png" style="margin-top: 15px;">');
                    $('#material-'+materiais[i].idmaterial).append('<p>'+materiais[i].descricao+'  ('+materiais[i].comprimento+'m)</p>');
                    $('#material-'+materiais[i].idmaterial).append('<input type="hidden" name="comprimentoMaterial" id="comprimentoMaterial-'+materiais[i].idmaterial+'" value="'+materiais[i].comprimento+'">');
                }

                $('#listaMateriais').append('<div class="material" id="material-cadastrar" onclick="addMaterial()"></div>');
                $('#material-cadastrar').append('<img class="material-cadastrar-img" id="material-cadastrar-img" src="_assets/img/add.png" style="margin-top: 15px;">');
                $('#material-cadastrar').append('<p>Cadastrar Ferragem</p>');
        },
        complete: function() {
            return true;
        }
    });
}

function selectMaterial(idMaterial){
    $('#material').val(idMaterial);
    $('.material').removeClass('materialSelected');
    $('.material-img').attr('src','_assets/img/piso.png');
    $('#material-'+idMaterial).addClass('materialSelected');
    $('#material-'+idMaterial+'-img').attr('src','_assets/img/piso-laranja.png');

    calculaMaterial();
}

function addMaterial() {
    $('#addMaterial').css('display','block');
    $('#wrapper').addClass('blur');
    $('#navbar').addClass('blur');
}
function closeMaterial(){
    $('#addMaterial').css('display','none');
    $('#wrapper').removeClass('blur');
    $('#navbar').removeClass('blur');
}
function addPasta() {
    $('#addPasta').css('display','block');
    $('#wrapper').addClass('blur');
    $('#navbar').addClass('blur');
}
function closePasta(){
    $('#addPasta').css('display','none');
    $('#wrapper').removeClass('blur');
    $('#navbar').removeClass('blur');
}
function addItem(pasta) {
    $('#addItemIdPasta').val(pasta);
    $('#addItem').css('display','block');
    $('#wrapper').addClass('blur');
    $('#navbar').addClass('blur');
}
function closeItem(){
    $('#addItem').css('display','none');
    $('#wrapper').removeClass('blur');
    $('#navbar').removeClass('blur');
}

function buscaDadosItem(idItem){

    atualizaTotal($('#idProjeto').val());

    buscaMateriais();

    $.ajax({
        url: "http://localhost/aula/cmc/scripts/selectItem.php?iditem=" + idItem,
        dataType: "json",
        async: false,
        success: function(json){
            item = json;

            selectMaterial(item.idmaterial);

            $('#qtdeBarras').val(item.qtdeBarras);
            $('#idpasta').val(item.idpasta);
            $('#iditem').val(item.iditem);
            $('#descricao').val(item.descricao);
            
            $('#comprimentoCol').val(item.comprimentoParede);
            
            $('#edit-Item').css("display", "block");   

            calculaMaterial();
        },
        complete: function() {
            return true;
        }
    });
}
 
function calculaMaterial(){
    var idMaterial = $("#material").val();
    var comprimentoMaterial = $('#comprimentoMaterial-'+idMaterial).val();

    var qtdeBarras = $('#qtdeBarras').val();
    console.log(qtdeBarras);
    
    var comprimentoCol = $('#comprimentoCol').val();
    console.log(comprimentoCol);
    

    var compTotal = qtdeBarras * comprimentoCol;
    console.log(compTotal);
    

    var totalMaterial = compTotal/comprimentoMaterial;
    console.log(totalMaterial);
    
    

    $("#totalMaterial").empty();
    $("#totalMaterial").append(parseInt(totalMaterial));
    $("#total").val(parseInt(totalMaterial));
    
}

function atualizaTotal(idProjeto){
    $.ajax({
        type: "GET",
        url: 'scripts/pisos.php?action=getTotal&idProjeto='+idProjeto,
        success: function(data) {
            $('#subTotalMaterial').empty();
            $('#subTotalMaterial').append(data);
        }
    });
}