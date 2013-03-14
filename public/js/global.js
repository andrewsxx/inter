window.Contabilidad = window.Contabilidad || {};

Contabilidad.getEndPoint =  function(options){
    if(options && options.async){
        if(!this.endAsyncPoint){
            options = jQuery.extend({url: BASE_URL + '/jsonrpc', async: true}, options);
            this.endAsyncPoint = jQuery.Zend.jsonrpc(options);
        }
        if(options.success) this.endAsyncPoint.setAsyncSuccess(options.success);
        return this.endAsyncPoint;
    } else {
        if(!this.endPoint){
            options = jQuery.extend({url: BASE_URL + '/jsonrpc'}, options);
            this.endPoint = jQuery.Zend.jsonrpc(options);
        }
        return this.endPoint;
    }
};
    
Contabilidad.getURLParameter = function (name) {
    return decodeURI((RegExp("[\\?&#]" + name + '=' + '(.+?)(&|$)').exec(window.location)||[,null])[1]);
};

Contabilidad.private_home = BASE_URL;
Contabilidad.admin_home = BASE_URL + "admin/categorias";

Contabilidad.htmlDecode = function (input)
{
    var e = document.createElement('div');
    e.innerHTML = input;
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
};

//translate function
Contabilidad.tr = function(string){
    var args = [].slice.call(arguments);
    if(!args.length) return "";
    args[0] = Contabilidad.htmlDecode(args[0]); //replace html code
    if(args.length > 1){
        var i=0;
        args[0] = args[0].replace(/%s/g, function (matched, group) {
            // Only disallow undefined values
            i++;
            return (args[i] && typeof(args[i]) !== 'undefined') ? args[i] : matched;
        });
    }
    return args[0];
};

Contabilidad.currencyValue = function(){
    var args = [].slice.call(arguments);
    if(!args.length) return "";
    var value = args[0];
    switch (args[1]){
        case '1':
            value = "$ " + value;
            break;
        default :
            value = "USD " + value;
            break;
    }
    return value;
}

Contabilidad.toDate = function(timestamp){
    var date = new Date((timestamp) * 1000);
    if (date.getDate()<10){
        var fday = "0" + date.getDate();
    } else {
        var fday = date.getDate();
    }
    if ((date.getMonth() + 1 )<10){
        var fmonth = "0" + (date.getMonth() + 1 );
    } else {
        var fmonth = (date.getMonth() + 1 );
    }
    return  fday + "/" + fmonth + "/" + date.getFullYear();
}

Contabilidad.getMonthInfo = function(timestamp){
    var date = new Date((timestamp));
    var ano = date.getFullYear();
    var month = date.getMonth();
    var months = {
        0: {name : Contabilidad.tr("enero"),
            lastDay : 31
        },
        1: {name : Contabilidad.tr("febrero"),
            lastDay : ((ano % 4 == 0) && ((ano % 100 != 0) || (ano % 400 == 0))) ? 29 : 28
        },
        2: {name : Contabilidad.tr("marzo"),
            lastDay : 31
        },
        3: {name : Contabilidad.tr("abril"),
            lastDay : 30
        },
        4: {name : Contabilidad.tr("mayo"),
            lastDay : 31
        },
        5: {name : Contabilidad.tr("junio"),
            lastDay : 30
        },
        6: {name : Contabilidad.tr("julio"),
            lastDay : 31
        },
        7: {name : Contabilidad.tr("agosto"),
            lastDay : 31
        },
        8: {name : Contabilidad.tr("septiembre"),
            lastDay : 30
        },
        9: {name : Contabilidad.tr("octubre"),
            lastDay : 31
        },
        10: {name : Contabilidad.tr("noviembre"),
            lastDay : 30
        },
        11: {name : Contabilidad.tr("diciembre"),
            lastDay : 31
        }
    }
    months[month].date = new Date (ano, month, months[month].lastDay);
    return months[month];
}

Contabilidad.getURLParameter = function(name) {
    return decodeURI((RegExp("[\\?&#]" + name + '=' + '(.+?)(&|$)').exec(window.location)||[,null])[1]);
}


//PROTOTYPE FUNCTIONS

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}