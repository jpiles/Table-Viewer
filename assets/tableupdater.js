

function changetable(){
	currPage = 0;
	$.post("assets/gettable.php", {table:$("#select-table").val(), num_rows:$("#num-rows").val(), page:0}, ontabledata);
}

function changesort(field){ 	
	if (sortParams.field == field){
		sortParams.dir = (sortParams.dir == ASC) ? DESC : ASC;
	} else {
		sortParams.field = field;
		sortParams.dir = ASC;
	}
	$.post("assets/gettable.php", {table:$("#select-table").val(), num_rows:$("#num-rows").val(), page:currPage, order:sortParams.field, dir:sortParams.dir}, ontabledata);
}

function changepage(page){
	currPage = page - 1;
	$.post("assets/gettable.php", {table:$("#select-table").val(), num_rows:$("#num-rows").val(), page:currPage, order:sortParams.field, dir:sortParams.dir}, ontabledata);
}

var ASC = "ASC";
var DESC = "DESC";
var currPage = 0;
var sortParams = {field:null, dir:ASC};

function ontabledata(datastr){
	var data = JSON.parse(datastr);

	var html = "<table>";

	html += "<thead>"
	html += "<tr class='title_row'>";

	for (var i = 0; i < data.fields.length; i++){
		html += "<th class='"+(((i%2)==0)?"even":"odd")+"'>";
		html += "<a onclick=\"changesort('"+data.fields[i]+"')\">";
		html += data.fields[i];
		if (sortParams.field == data.fields[i]){
			html += "<img src='assets/images/arrow_"+sortParams.dir+".png' class='"+sortParams.dir+"'/>";
		}
		html += "</a>";
		html += "</th>";
	}

	html += "</tr>";
	html += "</thead>"
	
	for (var i = 0; i < data.rows.length; i++){
		//
		html += "<tr class='"+(((i%2)==0)?"even":"odd")+"'>";
		for (var j = 0; j < data.rows[i].length; j++){
			html += "<td class='"+(((j%2)==0)?"even":"odd")+"'>"+data.rows[i][j]+"</td>";
		}
		console.log("end row");
		html += "</tr>";
	}

	html += "</table>";
	$("#table-show").html(html);
	
	var num_pages = Math.ceil(parseInt(data.total_entries)/parseInt($("#num-rows").val()));
	console.log("NUM PAGES:"+num_pages);
	
	if (num_pages > 0){
		$("#pagination").paginate({
					count 		: Math.ceil(parseInt(data.total_entries)/parseInt($("#num-rows").val())),
					start 		: currPage+1,
					display     : 5,
					border		: true,
					border_color			: 'none',
					text_color  			: '#000000',
					background_color    	: 'none',	
					border_hover_color		: 'none',
					text_hover_color  		: '#000000',
					background_hover_color	: 'none', 
					images					: true,
					mouse					: 'press',
					onChange     			: changepage
				});
	}
}