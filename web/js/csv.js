/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {

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
            csv = 'id;Reference;Nom;Quantité\n' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');
                
                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();
                if ( text.indexOf("edit") !== -1){
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
            
        $(this)
            .attr({
            'download': filename,
                'href': csvData,
                'target': '_blank'
        });
    }

    // This must be a hyperlink
    $("#csv").on('click', function (event) {
        // CSV
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
        
        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });
});