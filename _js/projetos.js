$(document).ready( function() {

    $("#form-criarProjeto").validate({
        rules : {
            nome:{
                required:true
            }               
        },
        messages:{
            nome:{
                required:"Escolha um nome para o seu projeto!",
            }     
        }
    });

    $("#novoProjeto").on("click", function(){
        $('#addProjeto').css('display','block');
        $('#wrapper').addClass('blur');
        $('#navbar').addClass('blur');
    });
    
    $("#cancelaProjeto").on("click", function(){
        $('#addProjeto').css('display','none');
    });
});