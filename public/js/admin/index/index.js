const USER_NOT_FOUND = "wrong authentication";
const EMAIL_ALREADY_REGISTERED = "email already registered";

$(document).ready(function(){
    $("form#login input").each(function(){
        setInputRule($(this));
    });
    
     //LOGIN SUBMIT
    $("form#login").submit(function(){
        $(".response").html("").hide().removeClass("*");
        if(Contabilidad.Validate.isValid($(this))){
            var data = {};
            $(this).find("input").each(function(){
                data[$(this).attr("name")] = $(this).val();
            });
            var resp = Contabilidad.getEndPoint({async : true, success: function(resp){
                if(resp.result == "success"){
                    document.location.href = Contabilidad.admin_home;
                } else if(resp.result == "failure") {
                    if(resp.reason == USER_NOT_FOUND){
                        $(".response")
                        .addClass("error")
                        .html(Contabilidad.tr("El nombre de usuario y la contraseña proporcionados no coinciden.")).show();
                    }
                }
            }}).login(data);
        } else {
            findAndDisplayErrors($("#login-container"));
        }
        return false;
    });
});

/*************************************
 **********VALIDATE METHODS***********
 *************************************/

function findAndDisplayErrors($form)
{
    var errors;
    $form.find(".input-error").each(function(){
        errors =  $(this).data("errors");
        if(errors.length){
            return;
        }
        return;
    });
    $form.find(".response").html(errors[0].message).show();
}

//set rules to an input
function setInputRule($input){
    if($input.attr("type") == "submit") return;
    //validation rulesc
    var rules = {required : $input.hasClass("required")};
    if($input.hasClass("is_email")){rules.email = true;}
    if($input.attr("type") == "password"){rules.password = true;}
    Contabilidad.Validate.setRules($input, rules);
}