$(document).ready(function(){
    $(".delete-category").click(function(){
        var id = $(this).attr("data-id");
        $(this).parent().remove();
        var resp = Contabilidad.getEndPoint({async : true, success: function(resp){
            if(resp.result == "success"){
            } else if(resp.result == "failure") {
                if(resp.reason == "CATEGORY NOT FOUND"){
                    $(".response")
                    .addClass("error")
                    .html(Contabilidad.tr("La categoria no existe")).show().fadeOut(5000, function(){
                        $(".response")
                        .removeClass("*")
                        .html("");
                    });
                }
            }
        }}).deleteCategory(id);
        return false;
    });
});