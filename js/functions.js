function eefstatifyTableToCsv( $table, filename ) {
	// Temporary delimiters unlikely to be typed by keyboard to avoid accidentally splitting the actual contents.
	const tmpColDelim = String.fromCharCode( 11 ),
		tmpRowDelim = String.fromCharCode( 0 ),
		// Actual delimiters for CSV.
		colDelim = '","',
		rowDelim = '"\r\n"',
		$rows = $table.find( 'tr' ),
		csv =
			'"' +
			$rows
				.map( function ( i, row ) {
					const $row = jQuery( row ),
						$cols = $row.find( 'td,th' );
					return $cols
						.map( function ( j, col ) {
							const $col = jQuery( col ),
								text = $col.text();
							return text.replace( /"/g, '""' ); // escape double quotes
						} )
						.get()
						.join( tmpColDelim );
				} )
				.get()
				.join( tmpRowDelim )
				.split( tmpRowDelim )
				.join( rowDelim )
				.split( tmpColDelim )
				.join( colDelim ) +
			'"',
		csvData =
			'data:application/csv;charset=utf-8,' + encodeURIComponent( csv );
	jQuery( this ).attr( {
		download: filename,
		href: csvData,
	} );
}

function eefstatifyColumnChart( id, dataArray ) {
	const dataMap = new Map( dataArray );
	const seriesData = [];
	for ( const [ x, y ] of dataMap.entries() ) {
		seriesData.push( { meta: x, value: y } );
	}
	const data = {
		labels: Array.from( { length: dataArray.length }, ( x, i ) => i + 1 ),
		series: [ seriesData ],
	};

	const options = {
		axisX: {
			showGrid: false,
		},
		axisY: {
			onlyInteger: true,
		},
		chartPadding: {
			top: 20,
			right: 30,
			bottom: 30,
			left: 30,
		},
		height: 300,
		plugins: [
			Chartist.plugins.tooltip( {
				anchorToPoint: true,
				transformTooltipTextFnc( y ) {
					return (
						y +
						' ' +
						( parseInt( y ) === 1
							? eefstatify_translations.view
							: eefstatify_translations.views )
					);
				},
				appendToBody: true,
				class: 'eefstatify-ct-tooltip',
			} ),
		],
		seriesBarDistance: 20,
	};

	const responsiveOptions = [
		[
			'screen and (max-width: 640px)',
			{
				seriesBarDistance: 5,
				axisX: {
					labelInterpolationFnc( value ) {
						return value[ 0 ];
					},
				},
			},
		],
	];

	new Chartist.Bar( id, data, options, responsiveOptions );
}

function eefstatifyLineChart( id, dataArray, type = 'default' ) {
	const dataMap = new Map( dataArray );
	const seriesData = [];
	for ( const [ x, y ] of dataMap.entries() ) {
		seriesData.push( { meta: x, value: y } );
	}
	const data = {
		labels: Array.from( dataMap.keys() ),
		series: [ seriesData ],
	};

	const options = {
		axisX: {
			showGrid: false,
			labelInterpolationFnc( value ) {
				return type === 'daily'
					? value.substring( 0, 2 ) === '1.'
						? value.substring( 2 )
						: ''
					: value;
			},
		},
		axisY: {
			onlyInteger: true,
		},
		chartPadding: {
			top: 20,
			right: 30,
			bottom: 30,
			left: 30,
		},
		height: 300,
		plugins: [
			Chartist.plugins.tooltip( {
				anchorToPoint: true,
				transformTooltipTextFnc( y ) {
					return (
						y +
						' ' +
						( parseInt( y ) === 1
							? eefstatify_translations.view
							: eefstatify_translations.views )
					);
				},
				appendToBody: true,
				class: 'eefstatify-ct-tooltip',
			} ),
		],
		showArea: true,
		showPoints: true,
	};

	new Chartist.Line( id, data, options );
}

function eefstatifySelectDateRange() {
	const t = new Date(),
		y = t.getFullYear(),
		m = t.getMonth(),
		d = t.getDate(),
		day = t.getDay(),
		mondayOfCurrentWeek = d - day + ( day === 0 ? -6 : 1 );
	switch ( jQuery( '#dateRange' ).val() ) {
		case 'default':
			jQuery( '#start' ).val( '' );
			jQuery( '#end' ).val( '' );
			eefstatifyValidateDateRange();
			break;
		case 'lastYear':
			eefstatifySetDateRange(
				new Date( y - 1, 0, 1 ),
				new Date( y - 1, 11, 31 )
			);
			break;
		case 'lastWeek':
			eefstatifySetDateRange(
				new Date( y, m, mondayOfCurrentWeek - 7 ),
				new Date( y, m, mondayOfCurrentWeek - 1 )
			);
			break;
		case 'yesterday':
			eefstatifySetDateRange(
				new Date( y, m, d - 1 ),
				new Date( y, m, d - 1 )
			);
			break;
		case 'today':
			eefstatifySetDateRange( t, t );
			break;
		case 'thisWeek':
			eefstatifySetDateRange(
				new Date( y, m, mondayOfCurrentWeek ),
				new Date( y, m, mondayOfCurrentWeek + 6 )
			);
			break;
		case 'last28days':
			eefstatifySetDateRange( new Date( y, m, d - 27 ), t );
			break;
		case 'lastMonth':
			eefstatifySetDateRange(
				new Date( y, m - 1, 1 ),
				new Date( y, m, 0 )
			);
			break;
		case 'thisMonth':
			eefstatifySetDateRange(
				new Date( y, m, 1 ),
				new Date( y, m + 1, 0 )
			);
			break;
		case '1stQuarter':
			eefstatifySetDateRange( new Date( y, 0, 1 ), new Date( y, 2, 31 ) );
			break;
		case '2ndQuarter':
			eefstatifySetDateRange( new Date( y, 3, 1 ), new Date( y, 5, 30 ) );
			break;
		case '3rdQuarter':
			eefstatifySetDateRange( new Date( y, 6, 1 ), new Date( y, 8, 30 ) );
			break;
		case '4thQuarter':
			eefstatifySetDateRange(
				new Date( y, 9, 1 ),
				new Date( y, 11, 31 )
			);
			break;
		case 'thisYear':
			eefstatifySetDateRange(
				new Date( y, 0, 1 ),
				new Date( y, 11, 31 )
			);
			break;
	}
}

function eefstatifySetDateRange( start, end ) {
	jQuery( '#start' ).val( eefstatifyDateFormat( start ) );
	jQuery( '#end' ).val( eefstatifyDateFormat( end ) );
	eefstatifyValidateDateRange();
}

function eefstatifyDateFormat( date ) {
	const m = date.getMonth() + 1,
		d = date.getDate();
	return (
		date.getFullYear() +
		'-' +
		( m > 9 ? '' : '0' ) +
		m +
		'-' +
		( d > 9 ? '' : '0' ) +
		d
	);
}

function eefstatifyDateRangeChange() {
	jQuery( '#dateRange' ).val( 'custom' );
	eefstatifyValidateDateRange();
}

function eefstatifyValidateDateRange() {
	const start = jQuery( '#start' ),
		end = jQuery( '#end' ),
		correct =
			start[ 0 ].validity.valid &&
			end[ 0 ].validity.valid &&
			( ( start.val() && end.val() ) ||
				( ! start.val() && ! end.val() ) );
	jQuery( 'form button' ).prop( 'disabled', ! correct );
}
