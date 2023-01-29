<?php

namespace ClavesDocusign;

class ClavesDocusign
{
	public static function data()
	{

		return array(
			'username'			=> 'thequickdivorce@gmail.com',
			'password' 			=> 'theQuickDiv2022',
			'integrator_key' 	=> 'e3d83ce9-3c33-4937-a928-3fafb6e99cb3',
			'redirect_uri' 		=> 'https://thequickdivorce.com/callback',
			// QA			
			// 'host_qa' 			=> 'https://demo.docusign.net/restapi',	
			// 'private_key_qa' 	=> 'https://thequickdivorce.com/libs/private_qa.key',
			// 'user_id_qa' 		=> '0542a0c5-877c-4742-a26a-d547d118c870',	
			// 'base_path_qa' 		=> 'account-d.docusign.com',				
			// PROD
			'host' 				=> 'https://na4.docusign.net/restapi',	
			'private_key' 		=> 'https://thequickdivorce.com/libs/private.key',
			'user_id' 			=> 'f920002d-f2db-4eac-a026-7fe2a3bdf672',	
			'base_path' 		=> 'account.docusign.com',		
		);
	}
}
