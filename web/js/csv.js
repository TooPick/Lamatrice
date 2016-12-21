/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    
    function compCsv($table) {

 
        var $rows = $table.find('tr:has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character
            
            // actual delimiter characters for CSV format
            colDelim = '";"',
            rowDelim = '"\n"',

            // Grab text from table into CSV formatted string
            csv = 'id;Reference;Nom;Quantité;\n' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');
                
                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();
                if ( text.indexOf("Modifier") !== -1){
                    return ("\n");
                }else {
                    return text; 
                }

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '',

            // Data URI
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
            //console.log(csv);

            var fileInput = document.getElementById("fileCsv"),
            readFile = function () {
            var reader = new FileReader();
            reader.onload = function () {
                var comp = reader.result;
                //console.log(comp);
                var compS = comp.split(";");
                for ( var i = 0 ; i < compS.length ; ++i){
                    compS[i] = compS[i].replace(/[\n"]/g,'');
                }
                var tabComp = new Object();
                for ( var j = 4; j< compS.length ; j+=4){
                    tabComp['"'+compS[j]+'"'] = compS[j+3]; 
                }
                
                var csvS = csv.split(";");
                for ( var i = 0 ; i < csvS.length ; ++i){
                    csvS[i] = csvS[i].replace(/[\n"]/g,'');
                }
                var tabCsv = new Object();
                for ( var j = 4; j< csvS.length ; j+=4){
                    tabCsv['"'+csvS[j]+'"'] = csvS[j+3]; 
                }
                //console.log(tabCsv);
                for ( var k in tabCsv){
                    if ( tabComp.hasOwnProperty(k)){
                        var ind = k.replace(/["]/g,'');
                        var diff = tabCsv[k]-tabComp[k];
                        $('a[href*="/admin/product/'+ind+'/show"]').parent().nextAll().slice(2, 3).html(diff);
                        
                    }
                }
                $("#cmp").remove();
                $("#fileCsv").remove();
                var path = "{{ path('admin_product_stock') }}";
                $("#csv").after('<br><a id="retour" href="">Retour à la liste</a>');
                $("#retour").pop("href",path);
            };
            // start reading the file. When it is done, calls the onload event defined above.
                
	
            if (FileReader.prototype.readAsBinaryString === undefined) {
                FileReader.prototype.readAsBinaryString = function (fileData) {
                var binary = "";
                var pt = this;
                var reader = new FileReader();
                reader.onload = function (e) {
                    var bytes = new Uint8Array(reader.result);
                    var length = bytes.byteLength;
                    for (var i = 0; i < length; i++) {
                        binary += String.fromCharCode(bytes[i]);
                    }
                    //pt.result  - readonly so assign content to another property
                    pt.content = binary;
                    $(pt).trigger('onload');
                }
                reader.readAsArrayBuffer(fileInput.files[0]);
            }   
}
                reader.readAsBinaryString(fileInput.files[0]);
                
            };
            readFile();
            
    }
    
    function exportTableToCSV($table, filename) {

 
        var $rows = $table.find('tr:has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character
            
            // actual delimiter characters for CSV format
            colDelim = '";"',
            rowDelim = '"\n"',

            // Grab text from table into CSV formatted string
            csv = 'id;Reference;Nom;Quantité;\n' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');
                
                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();
                if ( text.indexOf("Modifier") !== -1){
                    return ("\n");
                }else {
                    return text; 
                }

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '',

            // Data URI
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
            //console.log(csv);
        $(this)
            .attr({
            'download': filename,
                'href': csvData,
                'target': '_blank'
        });
    }

    // This must be a hyperlink
    $("#csv").on('click', function (event) {
        
        var max = $("#stock_next").hasClass("disabled");
        var info = $("select[name='stock_length'] option:selected" ).text();
        var r = false;
        if ( max ) {
            r = true;
        } else { 
            r = confirm("Attention l'export contiendra "+info+" élément. Veuillez en selectionnez plus si besoin.");
        }
        if (r == true) {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();

            if(dd<10) {
                dd='0'+dd
            } 

            if(mm<10) {
                mm='0'+mm
            } 

            today = dd+'-'+mm+'-'+yyyy;
            exportTableToCSV.apply(this, [$('#stock'), 'stock-'+today+'.csv']);
        
        } 

        
        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });
    
    $("#cmp").on('click', function (event) {
        compCsv.apply(this, [$('#stock')]);
        
    });
    
});