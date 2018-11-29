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

    $('#form-tijolo').change(function(){
        calculaTijolo();
    });
    
    //  CADASTRAR PASTA
    $("#form-addProjeto").validate({
        rules : {
            nomeTijolo:{
                required:true
            },
            espessuraTijolo:{
                required:true
            },
            alturaTijolo:{
                required:true
            },
            comprimentoTijolo:{
                required:true
            },              
        },
        messages:{
            nomeTijolo:{
                required:"Escolha um nome para o seu Tijolo!",
            },
            espessuraTijolo:{
                required:"Informe a espessura do seu Tijolo!",
            },
            alturaTijolo:{
                required:"Informe a altura do seu Tijolo!",
            },
            comprimentoTijolo:{
                required:"Informe a comprimento do seu Tijolo!",
            }
        }
    });

    $("#form-addTijolo").submit(function() {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: 'scripts/tijolos.php?action=addTijolo',
            data: $('#form-addTijolo').serialize(),
            success: function() {
                closeTijolo();
                buscaTijolos();
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
        $("#form-tijolo").validate({
            rules : {
                tijolo:{
                    required:true
                },
                massa:{
                    required:true
                },
                altParede:{
                    required:true
                },
                comParede:{
                    required:true
                }          
            },
            messages:{
                tijolo:{
                    required:"Escolha um tipo de Tijolo!",
                },
                massa:{
                    required:"Informe a espessura da Massa!",
                },
                altParede:{
                    required:"Informe a altura da Parede!",
                },
                comParede:{
                    required:"Informe o comprimento da Parede!",
                }
            }
        });
    
        $("#form-tijolo").submit(function() {
            event.preventDefault();
    
            $.ajax({
                type: "POST",
                url: 'scripts/tijolos.php?action=salvarItem',
                data: $('#form-tijolo').serialize(),
                success: function(data) {
                    console.log(data);
                    
                    console.log($('#form-tijolo').serialize());
                    atualizaTotal($('#idProjeto').val());
                }
              });
        });


        $('#btn_excluir').click(function(){
            $.ajax({
                type: "POST",
                url: 'scripts/tijolos.php?action=excluirItem',
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

function buscaTijolos(){

    $.ajax({
        url: "http://localhost/aula/cmc/scripts/tijolos.php?action=get",
        dataType: "json",
        async: false,
        success: function(json){
            tijolos = json;

                $('#listaTijolos').empty();

                for(i=0;i<tijolos.length;i++){
                    $('#listaTijolos').append('<div class="tijolo" id="tijolo-'+tijolos[i].idmaterial+'" onclick="selectTijolo('+tijolos[i].idmaterial+')"></div>');
                    
                    $('#tijolo-'+tijolos[i].idmaterial).append('<img class="tijolo-img" id="tijolo-'+tijolos[i].idmaterial+'-img" src="_assets/img/tijolo.png">');
                    $('#tijolo-'+tijolos[i].idmaterial).append('<p>'+tijolos[i].descricao+'  ('+tijolos[i].espessura+'x'+tijolos[i].altura+'x'+tijolos[i].comprimento+'cm)</p>');
                    $('#tijolo-'+tijolos[i].idmaterial).append('<input type="hidden" name="espessuraTijolo" id="espessuraTijolo-'+tijolos[i].idmaterial+'" value="'+tijolos[i].espessura+'">');
                    $('#tijolo-'+tijolos[i].idmaterial).append('<input type="hidden" name="alturaTijolo" id="alturaTijolo-'+tijolos[i].idmaterial+'" value="'+tijolos[i].altura+'">');
                    $('#tijolo-'+tijolos[i].idmaterial).append('<input type="hidden" name="comprimentoTijolo" id="comprimentoTijolo-'+tijolos[i].idmaterial+'" value="'+tijolos[i].comprimento+'">');
                }

                $('#listaTijolos').append('<div class="tijolo" id="tijolo-cadastrar" onclick="addTijolo()"></div>');
                $('#tijolo-cadastrar').append('<img class="tijolo-cadastrar-img" id="tijolo-cadastrar-img" src="_assets/img/add.png" style="margin-top: 15px;">');
                $('#tijolo-cadastrar').append('<p>Cadastrar Tijolo</p>');
        },
        complete: function() {
            return true;
        }
    });
}

function selectTijolo(idTijolo){
    $('#tijolo').val(idTijolo);
    $('.tijolo').removeClass('tijoloSelected');
    $('.tijolo-img').attr('src','_assets/img/tijolo.png');
    $('#tijolo-'+idTijolo).addClass('tijoloSelected');
    $('#tijolo-'+idTijolo+'-img').attr('src','_assets/img/tijolo-laranja.png');

    calculaTijolo();
}

function addTijolo() {
    $('#addTijolo').css('display','block');
    $('#wrapper').addClass('blur');
    $('#navbar').addClass('blur');
}
function closeTijolo(){
    $('#addTijolo').css('display','none');
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

    buscaTijolos();

    $.ajax({
        url: "http://localhost/aula/cmc/scripts/selectItem.php?iditem=" + idItem,
        dataType: "json",
        async: false,
        success: function(json){
            item = json;

            selectTijolo(item.idmaterial);

            $('#massa').val(item.espessuraMassa);
            $('#idpasta').val(item.idpasta);
            $('#iditem').val(item.iditem);
            $('#descricao').val(item.descricao);
            

            $('#altParede').val(item.alturaParede);
            $('#compParede').val(item.comprimentoParede);
            
            if(item.tijoloDeitado == 1){
                $("#deitado").prop('checked', true);
            }else{
                $("#deitado").prop('checked', false);
            }
            $('#edit-Tijolo').css("display", "block");   

            calculaTijolo();
        },
        complete: function() {
            return true;
        }
    });
}
 
function calculaTijolo(){
    var idMaterial = $("#tijolo").val();
    var comprimentoMaterial = $('#comprimentoTijolo-'+idMaterial).val();
    var alturaMaterial = $('#alturaTijolo-'+idMaterial).val();
    var espessuraMaterial = $('#espessuraTijolo-'+idMaterial).val();

    var espessuraMassa = $('#massa').val();
    var altParede = $('#altParede').val();
    var compParede = $('#compParede').val();
    if ($('#deitado').is(':checked')) {
        var deitado = 1;
    }else{
        var deitado = 0;
    }
    var espessuraMassa = espessuraMassa/100;
    var comprimentoMaterial = comprimentoMaterial/100;
    var alturaMaterial = alturaMaterial/100;
    var espessuraMaterial = espessuraMaterial/100;
    
    if(deitado==1){
        var tijoloPorM2 = 1/[(comprimentoMaterial+espessuraMassa)*(espessuraMaterial+espessuraMassa)];
    }else{
        var tijoloPorM2 = 1/[(comprimentoMaterial+espessuraMassa)*(alturaMaterial+espessuraMassa)];
    }


    var paredeM2 = altParede*compParede;
    var totalTijolos = paredeM2*tijoloPorM2;

    $("#totalTijolos").empty();
    $("#totalTijolos").append(parseInt(totalTijolos));
    $("#total").val(parseInt(totalTijolos));
    
}

function atualizaTotal(idProjeto){
    $.ajax({
        type: "GET",
        url: 'scripts/tijolos.php?action=getTotal&idProjeto='+idProjeto,
        success: function(data) {
            $('#subTotalTijolos').empty();
            $('#subTotalTijolos').append(data);
        }
    });
}
