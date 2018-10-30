$(document).ready( function() {

    $.validator.addMethod("senha", function(element) {
        return $('#senha').val() == $('#senha2').val();
    });


    $("#form-login").validate({
        rules : {
            email:{
                    required:true,
                    email:true
            },
            senha:{
                    required:true
            }                
        },
        messages:{
            email:{
                    required:"É necessário informar um email!",
                    email:"Por Favor insira um email válido!"
            },
            senha:{
                    required:"É necessario informar uma senha!"
            }     
        }
    });

    $("#form-cadastro").validate({
        rules : {
            nome:{
                required:true
            },
            email:{
                required:true,
                email:true
            },
            senha:{
                required:true
            },
            senha2:{
                required:true,
                senha: true
            }                
        },
        messages:{
            nome:{
                required: "É necessário informar o seu nome!"
            },
            email:{
                    required:"É necessário informar um email!",
                    email:"Por Favor insira um email válido!"
            },
            senha:{
                    required:"É necessario informar uma senha!"
            },
            senha2:{
                    required:"É necessario repetir a sua senha!",
                    senha:"As senhas não coincidem!"
            }     
        }
    });

    $("#form-recuperacao").validate({
        rules : {
            email:{
                required:true,
                email:true
            }            
        },
        messages:{
            email:{
                    required:"É necessário informar um email!",
                    email:"Por Favor insira um email válido!"
            }   
        }
    });

    $("#form-trocaSenha").validate({
        rules : {
            senha:{
                required:true
            },
            senha2:{
                required:true,
                senha: true
            }                
        },
        messages:{
            senha:{
                    required:"É necessario informar uma senha!"
            },
            senha2:{
                    required:"É necessario repetir a sua senha!",
                    senha:"As senhas não coincidem!"
            }     
        }
    });
});