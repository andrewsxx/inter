$(document).ready(function(){
    $("form#create-category input").each(function(){
        setInputRule($(this));
    });
    
     //LOGIN SUBMIT
    $("form#create-category").submit(function(){
        $(".response").html("").hide().removeClass("*");
        if(Contabilidad.Validate.isValid($(this))){
            var data = {};
            $(this).find("input").each(function(){
                data[$(this).attr("name")] = $(this).val();
            });
            return true;
        } else {
            findAndDisplayErrors($("body"));
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