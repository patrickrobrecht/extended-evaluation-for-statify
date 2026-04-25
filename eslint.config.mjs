import globals from 'globals';
import wordpress from '@wordpress/eslint-plugin';

export default [
	...wordpress.configs.recommended,
	{
		languageOptions: {
			globals: {
				...globals.browser,
				...globals.jquery,
				Chartist: 'readonly',
				eefStatifyTranslations: 'readonly',
			},
		},
		rules: {
			'no-nested-ternary': 'off',
			'no-unused-vars': [
				'error',
				{
					varsIgnorePattern:
						'eefstatify(TableToCsv|ColumnChart|LineChart|SelectDateRange|DateRangeChange)',
				},
			],
		},
	},
];
