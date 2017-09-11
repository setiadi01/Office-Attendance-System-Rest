Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

String.prototype.toDate = function(){
	// Convert from YYYYMMDD to Date object
	var y = this.substring(0,4);
	var m = this.substring(4,6);
	var d = this.substring(6);
	return new Date(y,m-1,d);
};

Date.prototype.toDate = function(){
	var y = this.getYear() + 1900;
	var m = this.getMonth()+1;
	var d = this.getDate();
	if(m<10)
	{
		m = "0" + m;
	}
	if(d<10)
	{
		d = "0" + d;
	}
	return y+""+m+""+""+d;
};

Date.prototype.toDDMMYYYY = function(){
	var y = this.getYear() + 1900;
	var m = this.getMonth()+1;
	var d = this.getDate();
	if(m<10) m = "0" + m;
	if(d<10) d = "0" + d;
	return d + "-" + m + "-" + y;
};

angular.module('ngLeaf',[])
	.filter('removeDecimal', function(){
	    return function(input){
	    	var output = input.substring(0,input.indexOf("."))
	    	return output;
	    }
   })
   .filter('number', function() {
	    return function(input, decimalPlace){
	    	if(decimalPlace==undefined){
	    		decimalPlace = 2;
	    	}
	    	return Number(input).formatMoney(decimalPlace,",",".");
	    }  
   })
   .filter('digits', function() {
    return function(input) {
       if ( input >= 100 && input < 1000){ 
    	   input = '0' + input
       }
       if (input >= 10 && input < 100){
    	   input = '00' + input;
       }
    	   
       if (input < 10){
    	   input = '000' + input;
       }
      
      return input;
    }
   })
   .filter('sumByKey', function() {
        return function(data, key) {
            if (typeof(data) === 'undefined' || typeof(key) === 'undefined') {
                return 0;
            }
 
            var sum = 0;
            for (var i = data.length - 1; i >= 0; i--) {
                sum += parseInt(data[i][key]);
            }
 
            return sum;
        };
    })
    .filter('num', function() {
	    return function(input) {
	       return parseInt(input, 10);
	    }
	})
   .filter('formatdate', function(){
	    return function(input){
	    	if(input==undefined) return "";
	    	if(input==" ") return "";
	    	var date = input.toDate();
	    	return date.toDDMMYYYY();
	    }
   })
   .filter('formatdatelocale', function(){
	    return function(input){
	    	if(input==undefined) return "-";
	    	if(input==" ") return "-";
	    	if(!input) return "-";
	    	
	    	var year = input.substring(0,4);
	        var month = parseInt(input.substring(4,6))-1;
	        var day = input.substring(6,8);
	        
	        return new Date(year,month,day).toLocaleString("in-IN", { year: "numeric", month: "long", day:"numeric"});
	    }
   })
   .filter('formatdatetime', function(){
	    return function(input){
	    	
	    	if(input==undefined) return "-";
	    	if(input==" ") return "-";
	    	if(!input) return "-";
	    	
	    	var year = input.substring(0,4);
	        var month = input.substring(4,6);
	        month = parseInt(month,10)-1;
	        var day = input.substring(6,8);
	        var hour = input.substring(8,10);
	        var minute = input.substring(10,12);
	        var second = input.substring(12,14);
	        return new Date(year,month,day).
	        toLocaleString("in-IN", { year: "numeric", month: "long", day:"numeric"})+' '+hour+':'+minute;

	    }
   })
   .filter('thousandsep', function(){
	   return function(input){
		   
		   if(input==undefined) return "-";
		   if(input==" ") return "-";
		   if(!input) return "-";
		   if(isNaN(input)) return "-";
		   
		   var formatted = parseInt(input).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + ".");
		   return formatted;
	   }
   })
   .filter('capitalize', function(){
	   return function(input, scope) {
		    if (input!=null)
		    input = input.toUpperCase();
		    return input;
	   }
   })
   .filter('mobilephone', function(){
	   return function(input) {
		    return input.replace(/(\d{4})(?!$)/g,"$&"+' - ');
	   }
   })
   .filter('trim', function(){
	    return function(input,count){
	    	var result = input;
//	    	var re = new RegExp(" ","g");
//	    	var ws = result.match(/\s/g).length;
//	    	console.log('ws: ' + ws);
//	    	console.log('count: ' + count);
//	    	if(count > ws + 1)
//	    		{
//	    			count = ws - 1;
//	    		}
//	    	for(var i=0; i<count; i++){
//	    		re.test(result);
//	    		console.log('re: ' + re.lastIndex);
//	    	}
//	    	var index = re.lastIndex;
//	    	return result.slice(0, index) + "...";
	    	var n = input.indexOf(' ');
	    	var m = 0;
	    	while(n!=-1){
	    		if(count==0) break;
	    		count--;
	    		m = n;
	    		n = input.indexOf(' ', n+1);
	    	}
	    	if(n==-1) return result;
	    	return result.substring(0,m) + "...";
	    }
   })
   .directive('separator', function(){
	  return {
		  restrict: 'E',
		  template: '<div class="separator"></div>'
	  }
   })
   .directive('holderFix', function () {
	    
	    return {
	        link: function (scope, element, attrs) {
	            Holder.run({ images: element[0], nocss: true });
	        }
	    };
   })
   .directive('backButton', function(){
    return {
      restrict: 'A',

      link: function(scope, element, attrs) {
    	  
    	element[0].innerHTML = '<i class="fa fa-arrow-circle-left">&nbsp;&nbsp;</i>' + element[0].innerHTML;  
        element.bind('click', function () {
          history.back();
          scope.$apply();
        });
      }
    }
   });