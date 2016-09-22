function exportTableToCSV($table, filename) {
	var tmpColDelim = String.fromCharCode(11), tmpRowDelim = String.fromCharCode(0), // Temporary delimiters unlikely to be typed by keyboard to avoid accidentally splitting the actual contents
	colDelim = '","', rowDelim = '"\r\n"', // actual delimiters for CSV
	$rows = $table.find('tr'),
	csv = '"' + $rows.map(function(i, row) {
		var $row = jQuery(row), $cols = $row.find('td,th');
		return $cols.map(function(j, col) {
			var $col = jQuery(col), text = $col.text();
			return text.replace(/"/g, '""'); // escape double quotes
		}).get().join(tmpColDelim);
	}).get().join(tmpRowDelim).split(tmpRowDelim)
			.join(rowDelim).split(tmpColDelim)
			.join(colDelim) + '"',
	csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
	jQuery(this).attr({
		'download' : filename,
		'href' : csvData
	});
}