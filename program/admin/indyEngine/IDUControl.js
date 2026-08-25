var checkSentType = 'save';
var keyUpdate;
var formID = "";
var pathRefresh = "";

function createIDU_submit(form_id,path_refresh,sent_type){	
	var chk_attr = getChkAttr(form_id);
	check_form(chk_attr,form_id,path_refresh);
	//alert(check_form);
	checkSentType = sent_type;
	formID = form_id;
	pathRefresh = path_refresh;
}
function check_form(chk_attr,form_id,path_refresh){
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=check_form&chk_attr="+chk_attr+"&"+$("#"+form_id).serialize(),
		success: function(result){
			$("#show_error").html(result);
		}
	});		
}
function queryDB_form(){
	if(checkSentType == 'save'){
		insertDB_form(formID,pathRefresh);
	}
	else{
		updateDB_form(formID,pathRefresh);
	}
}
function insertDB_form(form_id,path_refresh){
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=insert_form&path_refresh="+path_refresh+"&"+$("#"+form_id).serialize(),
		success: function(result){
			$("#show_error").html(result);
		}
	});		
}
function updateDB_form(form_id,path_refresh){
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=update_form&path_refresh="+path_refresh+"&"+$("#"+form_id).serialize(),
		success: function(result){
			$("#show_error").html(result);
		}
	});	
}
function updateDB_for_rows(form_id,path_refresh){
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=update_form_rows&path_refresh="+path_refresh+"&"+$("#"+form_id).serialize(),
		success: function(result){
			$("#show_error").html(result);
		}
	});	
}

function insertDB(table,fields,values,path_refresh){
	var values = values.split('&').join('{{indy_schar}}');
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=insertDB&table="+table+"&fields="+fields+"&values="+values+"&path_refresh="+path_refresh,
		success: function(result){
			$("#show_error").html(result);
		}
	});
}

function insertDBMultitable(table,fields,values,path_refresh){
	var value = value.split('&').join('{{indy_schar}}');
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=insertDBMultitable&table="+table+"&fields="+fields+"&values="+values+"&path_refresh="+path_refresh,
		success: function(result){
			$("#show_error").html(result);
		}
	});
}

function deleteDB(table,key,path_refresh){
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=deleteDB&table="+table+"&key="+key+"&path_refresh="+path_refresh,
		success: function(result){
			//alert(result);
			$("#show_error").html(result);
		}
	});
}
function updateDB(table,key,field,value,path_refresh){
	var value = value.split('&').join('{{indy_schar}}');
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=updateDB&table="+table+"&key="+key+"&field="+field+"&value="+value+"&path_refresh="+path_refresh,
		success: function(result){
			$("#show_error").html(result);
		}
	});
}
function updateDB_checksame(table,key,field,value,path_refresh){
	var value = value.split('&').join('{{indy_schar}}');
	$.ajax({
		type: "POST",
		url: "indyEngine/IDUControl.php",
		data: "type=updateDB_checksame&table="+table+"&key="+key+"&field="+field+"&value="+value+"&path_refresh="+path_refresh,
		success: function(result){
			$("#show_error").html(result);
		}
	});
}

//getChkAttr return ค่าใน form ที่มี array attribute chkform ใน form_id
/* 
	var chk_attr = getChkAttr(form_id);
*/
function getChkAttr(form_id){
	var valsArray = $("#"+form_id).serializeArray();
	var str = "";
	for (i=0;i<valsArray.length;i++){
		var inputName = $("input[name='"+valsArray[i]["name"]+"']").attr("chkform");
		if(inputName){
			str += valsArray[i]["name"]+"="+ inputName + ",";
		}
	}
	return str;
}
//getTableName return table name in table_id
/* 
	var table_name = getTableName(table_id);
*/
function getTableName(table_id){
	var table_name = $("#"+table_id).attr("id");
	var primary_keys = getPrimaryKeys(table_id);
	var array = [];
	array.push(table_name);
	array.push(primary_keys);
	return array;
}
//getPrimaryKeys return array primary key in table_id
/* 
	var id = getPrimaryKeys(table_id);
*/
function getPrimaryKeys(table_id){
	var primarykeys = [];
	$("#"+table_id+" tbody tr").each(function(){
		var key = $(this).attr("key");
		primarykeys.push(key);
	});
	return primarykeys;
}
//addTextTD return html td
/* 
	var text = '<a href="#{key}"><button title="{key}" class="btn btn-warning"></button></a>';
	addTextTDKey('table_id',text);
*/
function addTextTD(table_id,text){
	var id = getPrimaryKeys(table_id);
	for(var i=0;i<id.length;i++){
		var target = $( "tr[key='"+id[i]+"']");
		target.append('<td>'+text+'</td>');
	};
}
//addTextTDKey return html td witch primary key
/*
	var text = '<a href="#{key}"><button title="{key}" class="btn btn-warning"></button></a>';
	addTextTDKey('table_id',text);
*/
function addTextTDKey(table_id,text){
	var id = getPrimaryKeys(table_id);
	for(var i=0;i<id.length;i++){
		var target = $( "tr[key='"+id[i]+"']");
		var strs = text.replace(/{key}/g,id[i]);
		var strs = strs.replace(/{table}/g,table_id);
		target.append('<td>'+strs+'</td>');
	};
}
