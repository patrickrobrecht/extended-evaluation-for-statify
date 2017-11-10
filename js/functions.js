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
		'download': filename,
		'href': csvData
	});
}

function eefstatifyColumnChart(id, title, subtitle, xAxisValues, yAxisValues, yAxisTitle, filename) {
    jQuery(function() {
        jQuery(id).highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: title
            },
            subtitle: {
                text: subtitle
            },
            xAxis: {
                categories: xAxisValues
            },
            yAxis: {
                title: {
                    text: yAxisTitle
                },
                min: 0
            },
            legend: {
                enabled: false
            },
            series: [{
                name: yAxisTitle,
                data: yAxisValues
            }],
            credits: {
                enabled: false
            },
            exporting: {
                filename: filename
            }
        });
    });
}

function eefstatifyLineChart(id, title, subtitle, xAxisValues, yAxisValues, yAxisTitle, filename) {
    jQuery(function() {
        jQuery(id).highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: title
            },
            subtitle: {
                text: subtitle
            },
            xAxis: {
                categories: xAxisValues
            },
            yAxis: {
                title: {
                    text: yAxisTitle
                },
                min: 0
            },
            legend: {
                enabled: false
            },
            series: [ {
                name: yAxisTitle,
                data: yAxisValues
            } ],
            credits: {
                enabled: false
            },
            exporting: {
                filename: filename
            }
        });
    });
}

function eefstatifySelectDateRange() {
    var t = new Date(),
        y = t.getFullYear(),
        m = t.getMonth(),
        d = t.getDate(),
        day = t.getDay(),
        mondayOfCurrentWeek = d - day + (day === 0 ? -6 : 1);
    switch (jQuery('#dateRange').val()) {
        case 'default':
            jQuery('#start').val('');
            jQuery('#end').val('');
            eefstatifyValidateDateRange();
            break;
        case 'lastYear':
            eefstatifySetDateRange(new Date(y - 1, 0, 1), new Date(y - 1, 11, 31));
            break;
        case 'lastWeek':
            eefstatifySetDateRange(new Date(y, m, mondayOfCurrentWeek - 7), new Date(y, m, mondayOfCurrentWeek - 1));
            break;
        case 'yesterday':
            eefstatifySetDateRange(new Date(y, m, d - 1), new Date(y, m, d - 1));
            break;
        case 'today':
            eefstatifySetDateRange(t, t);
            break;
        case 'thisWeek':
            eefstatifySetDateRange(new Date(y, m, mondayOfCurrentWeek), new Date(y, m, start.getDate() + 6));
            break;
        case 'last28days':
            eefstatifySetDateRange(new Date(y, m, d - 28), t);
            break;
        case 'lastMonth':
            eefstatifySetDateRange(new Date(y, m - 1, 1), new Date(y, m, 0));
            break;
        case 'thisMonth':
            eefstatifySetDateRange(new Date(y, m, 1),new Date(y, m + 1, 0));
            break;
        case '1stQuarter':
            eefstatifySetDateRange(new Date(y, 0, 1), new Date(y, 2, 31));
            break;
        case '2ndQuarter':
            eefstatifySetDateRange(new Date(y, 3, 1), new Date(y, 5, 30));
            break;
        case '3rdQuarter':
            eefstatifySetDateRange(new Date(y, 6, 1), new Date(y, 8, 30));
            break;
        case '4thQuarter':
            eefstatifySetDateRange(new Date(y, 9, 1), new Date(y, 11, 31));
            break;
        case 'thisYear':
            eefstatifySetDateRange(new Date(y, 0, 1), new Date(y, 11, 31));
            break;
    }
}

function eefstatifySetDateRange(start, end) {
    jQuery('#start').val(eefstatifyDateFormat(start));
    jQuery('#end').val(eefstatifyDateFormat(end));
    eefstatifyValidateDateRange();
}

function eefstatifyDateFormat(date) {
    var m = date.getMonth() + 1,
        d = date.getDate();
    return date.getFullYear() + '-' + (m > 9 ? '' : '0') + m + '-' + (d > 9 ? '' : '0') + d;
}

function eefstatifyDateRangeChange() {
    jQuery('#dateRange').val('custom');
    eefstatifyValidateDateRange();
}

function eefstatifyValidateDateRange() {
    var start = jQuery('#start'),
        end = jQuery('#end'),
        correct = start[0].validity.valid && end[0].validity.valid && ((start.val() && end.val()) || (!start.val() && !end.val()));
    jQuery('form button').prop('disabled', !correct);
}
