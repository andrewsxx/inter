Contabilidad.Validate =
{
    setRules : function($el, rules){
         $el.data("validateRules", rules);
    },

    setMessages : function($el, messages){
         $el.data("validateMessages", messages);
    },

    isValid : function($form, findBy)
    {
        if(!findBy) findBy = "input";
        var tthis = this;
        var isValid = true;
        this.clean($form);
        $form.find(findBy).each(function(){
            var rules = $(this).data("validateRules");
            for(var rule in rules){
                tthis.validate(rule, rules[rule], $(this));
            }
            var errors = $(this).data("errors");
            if(errors && errors.length){
                $(this).addClass("input-error");
                //this is for password input, which are hidden and there is a text input instead of them
                if(!$(this).is(":visible")){
                    var name = $(this).attr("name");
                    $("input[for='" + name + "']").addClass("input-error");
                }
                isValid = false;
            }
        });
        return isValid;
    },

    validate : function(rule, ruleValue, $el)
    {
        if(!$el.data("errors")) {
            $el.data("errors", []);
        }
        if(!ruleValue) return false;
        var errors =  $el.data("errors");
        var value = $.trim($el.val());
        switch(rule){
            case "required":
                if(!value.length || value == $el.data("defaultValue")){
                    errors.push({error: "required", message: this.getMessage($el, rule)});
                }
                break;
            case "email":
                var rege = /^([A-Za-z0-9_\-\.\+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                if(!rege.test(value.replace(/^\s+/g,'').replace(/\s+$/g,''))) {
                    errors.push({error: "email", message: this.getMessage($el, rule)});
                }
                break;
            case "equalsTo":
                var rvalue = $.trim(ruleValue.val());
                if(value != rvalue) {
                    errors.push({error: "equalsTo", message: this.getMessage($el, rule)});
                }
                break;
            case "password":
                if(value.length < 6) {
                    errors.push({error: "password", message: this.getMessage($el, rule)});
                }
                break;
            case "accept":
                var param = ruleValue.replace(/,/g, '|');
                if(!value.match(new RegExp(".(" + param + ")$", "i"))){
                    errors.push({error: "accept", message: this.getMessage($el, rule)});
                };
                break;
            case "money":
                var regex = /^(\d+[.,\d+]*)$/;
                if(!regex.test(value)) {
                    errors.push({error: "money", message: this.getMessage($el, rule)});
                };
                break;
        }
        $el.data("errors", errors);
        return false;
    },

    getMessage : function($el, rule){
        var messages = {};
        var msg = "";
        if($el.data("validateMessages")){messages = $el.data("validateMessages");}
        var defaultMessages = {
            "required" : Contabilidad.tr('Llena todos los campos.'),
            "email" : Contabilidad.tr('Escribe un email valido.'),
            "equalsTo" : Contabilidad.tr('Digita el mismo valor.'),
            "password" : Contabilidad.tr('Digita al menos 6 caracteres.'),
            "accept" : Contabilidad.tr('Please enter a value with a valid extension.'),
            "money" : Contabilidad.tr('Digita solo valores.')
        }
        if(messages[rule]){
            msg = messages[rule];
        } else {
            msg = defaultMessages[rule];
        }
        return msg;
    },

    //clean errors and remove error class from inputs inside $form
    clean : function($form){
        $form.find(".input-error")
        .removeClass("input-error")
        .data("errors", null);
    },

    //return errors
    getErrors : function($form){
        var errors = null;
        $form.find(".input-error").each(function(){
            errors =  $(this).data("errors");
            if(errors.length){
                return false;
            }
        });
        return errors;
    }
}