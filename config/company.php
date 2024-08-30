<?php

/* Company Information for use on document exports */

return [

	'name' => env('COMPANY_NAME', 'Nome da Empresa'),
	'short_name' => env('COMPANY_SHORT_NAME', 'Empresa'), 
	'address' => env('COMPANY_ADDRESS', 'Rua, 123'),
	'district' => env('COMPANY_DISTRICT', 'Bairro'), // Bairro
	'city' => env('COMPANY_CITY', 'Cidade'),
	'state' => env('COMPANY_STATE', 'Estado'),
	'uf' => env('COMPANY_UF', 'XX'),
	'country' => env('COMPANY_COUNTRY', 'Country'),
	'country_br' => env('COMPANY_COUNTRY_BR', 'País'),
	'zip' => env('COMPANY_ZIP', '00000-000'), // CEP
	'vat' => env('COMPANY_VAT', '00.00.000/0001-00'), // CNPJ

	'ie' => env('COMPANY_IE', null), // Inscricao Estadual
	'site' => env('COMPANY_SITE', null),
	'phone' => env('COMPANY_PHONE', null),
	'email' => env('COMPANY_EMAIL', null),
	'skype' => env('COMPANY_SKYPE', null),

]

?>