function ancp_getRequest(){
	if(location.search.length > 1) {
		var get = new Object();
		var ret = location.search.substr(1).split("&");
		for(var i = 0; i < ret.length; i++) {
			var r = ret[i].split("=");
			get[r[0]] = r[1];
		}
		return get;
  	} else {
		return false;
	}
}
var get = ancp_getRequest();

if((get["category_name"] != undefined) && (get["category_name"].length > 1) ) {
	jQuery(function($){
		$("#menu-posts .wp-submenu a[href*='="+get["category_name"]+"']\"").parent().addClass("current");
	});
}