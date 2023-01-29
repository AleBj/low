<?php
class idiomas
{
	
	
	public function getLang($_url)
	{
		$_lang =array_filter(explode('/',$_url));
		$num = (count($_lang) - 1);
		return $_lang[$num];
	
	}
	
	public function data()
	{					
		return array(
			'es' => array(
						'nav' => array('Inicio','Nosotros','Soluciones','Casos','Blog','Contacto'),
						'home' => array(
										'titulo' => 'Inicio',
										'titulo_contacto' => '¿Estás interesado en las soluciones de Kit Urbano?',
										'descripcion_contacto' => 'Complete el formulario y en breve estaremos en contacto con usted'
									),
						'nosotros' => array(
											'titulo' => 'Nosotros',
											'titulo_1' => '<strong>Creemos,</strong> buscamos y creamos.',
											'descripcion_1-1' => 'Creamos una usina de innovación cívica y social que tiene el objetivo de achicar la brecha entre la gestión de gobierno y los ciudadanos.',
											'descripcion_1-2' => 'Buscamos fomentar la escucha activa y la participación ciudadana para generar una co-creación de soluciones innovadoras para problemáticas cotidianas.',
											'cita_destacada' => '“Aquel que tiene un porqué para vivir puede enfrentarse a todos los cómo”',
											'cita_destacada_autor' => 'Friedrich Nietzsche',
											'cita_destacada_autor_profesion' => 'Filosofo, poeta y músico alemán.',
											'titulo_2' => 'El trabajo cotidiano: <strong>diversión</strong>',
											'descripcion_2' => 'Las personas son la parte esencial de Kit Urbano. Cada uno de los profesionales que conforma el equipo brinda algo único que hace que el trabajo no se sienta como tal y el día a día sea todo menos rutinario. Buscamos gente brillante y apasionada que nos siga inspirando y nos lleve a dar lo mejor en todos los planos.',
											'titulo_3' => '<strong>Impulsados</strong> por la pasión',
											'descripcion_3-1' => 'Un cambio concreto y duradero',
											'descripcion_3-2' => 'Estamos convencidos que se puede generar un cambio cierto y duradero en nuestra sociedad a través del habla como protagonista de la comunicación. Creemos que debemos valernos de la inteligencia colectiva para mejorar los procesos y servicios gubernamentales.',
											'titulo_4' => '<strong>Valores</strong> y espacio',
											'descripcion_4-1' => 'Un ambiente adecuado es vital para potenciar el desarrollo de cualquier organización. Creemos en lo valioso de las soluciones pequeñas a los problemas grandes. Trabajamos con el objetivo de lograr una herramienta que le brinde al ciudadano el poder de realizar un cambio en su entorno.',
											'descripcion_4-2' => 'El esfuerzo es continuidad y constancia; sacará adelante cualquier proyecto que encaremos. Es el arma invaluable con la que nos valemos en esta loca aventura de emprender. Nuestro proyecto es el futuro y concentra todo lo que viene. Los desafíos son enormes, pero estamos ansiosos por enfrentarlos.',
											'titulo_contacto' => '<strong>¿Estás interesado</strong> en las soluciones de Kit Urbano?',
											'descripcion_contacto' => 'Complete el formulario y en breve estaremos en contacto con usted.',
											'footer' => 'Copyright © 2016 Kit Urbano Todos los derechos reservados.'
										),
						'soluciones' => array(
											'titulo' => 'soluciones',
											'descripcion' => 'Esto es una descripcion'
										),						
						'casos' => array(
										'titulo' => 'Casos'
									),
						'blog' => array(
										'titulo' => 'Blog',
										'buscador' => 'buscar nota...'
									),
						'contacto' => array(
											'titulo' => 'Contacto'
										)
					),
					
			'en' => array(
						'nav' => array('Home','About','Solutions','Cases','Blog','Contact'),
						'home' => array(
										'titulo' => 'Home',
										'titulo_contacto' => '¿Estás interesado en las soluciones de Kit Urbano?',
										'descripcion_contacto' => 'Complete el formulario y en breve estaremos en contacto con usted'
									),
						'nosotros' => array(
											'titulo' => 'About',
											'titulo_1' => '<strong>Creemos,</strong> buscamos y creamos.',
											'descripcion_1-1' => 'Creamos una usina de innovación cívica y social que tiene el objetivo de achicar la brecha entre la gestión de gobierno y los ciudadanos.',
											'descripcion_1-2' => 'Buscamos fomentar la escucha activa y la participación ciudadana para generar una co-creación de soluciones innovadoras para problemáticas cotidianas.',
											'cita_destacada' => '“Aquel que tiene un porqué para vivir puede enfrentarse a todos los cómo”',
											'cita_destacada_autor' => 'Friedrich Nietzsche',
											'cita_destacada_autor_profesion' => 'Filosofo, poeta y músico alemán.',
											'titulo_2' => 'El trabajo cotidiano: <strong>diversión</strong>',
											'descripcion_2' => 'Las personas son la parte esencial de Kit Urbano. Cada uno de los profesionales que conforma el equipo brinda algo único que hace que el trabajo no se sienta como tal y el día a día sea todo menos rutinario. Buscamos gente brillante y apasionada que nos siga inspirando y nos lleve a dar lo mejor en todos los planos.',
											'titulo_3' => '<strong>Impulsados</strong> por la pasión',
											'descripcion_3-1' => 'Un cambio concreto y duradero',
											'descripcion_3-2' => 'Estamos convencidos que se puede generar un cambio cierto y duradero en nuestra sociedad a través del habla como protagonista de la comunicación. Creemos que debemos valernos de la inteligencia colectiva para mejorar los procesos y servicios gubernamentales.',
											'titulo_4' => '<strong>Valores</strong> y espacio',
											'descripcion_4-1' => 'Un ambiente adecuado es vital para potenciar el desarrollo de cualquier organización. Creemos en lo valioso de las soluciones pequeñas a los problemas grandes. Trabajamos con el objetivo de lograr una herramienta que le brinde al ciudadano el poder de realizar un cambio en su entorno.',
											'descripcion_4-2' => 'El esfuerzo es continuidad y constancia; sacará adelante cualquier proyecto que encaremos. Es el arma invaluable con la que nos valemos en esta loca aventura de emprender. Nuestro proyecto es el futuro y concentra todo lo que viene. Los desafíos son enormes, pero estamos ansiosos por enfrentarlos.',
											'titulo_contacto' => '<strong>¿Estás interesado</strong> en las soluciones de Kit Urbano?',
											'descripcion_contacto' => 'Complete el formulario y en breve estaremos en contacto con usted.',
											'footer' => 'Copyright © 2016 Kit Urbano Todos los derechos reservados.'
										),
						'soluciones' => array(
											'titulo' => 'Solutions',
											'descripcion' => 'This is a description'
										),
						'casos' => array(
										'titulo' => 'Cases'
									),
						'blog' => array(
										'titulo' => 'Blog',
										'buscador' => 'search news...'
									),
						'contacto' => array(
											'titulo' => 'Contact'
										)	
					),
			
			'po' => array(
						'nav' => array('Iniciação','Nós','Soluções','Casos','Blog','Contato'),
						'home' => array(
										'titulo' => 'Iniciação',
										'titulo_contacto' => '¿Estás interesado en las soluciones de Kit Urbano?',
										'descripcion_contacto' => 'Complete el formulario y en breve estaremos en contacto con usted'
									),
						'nosotros' => array(
											'titulo' => 'Nós',
											'titulo_1' => '<strong>Creemos,</strong> buscamos y creamos.',
											'descripcion_1-1' => 'Creamos una usina de innovación cívica y social que tiene el objetivo de achicar la brecha entre la gestión de gobierno y los ciudadanos.',
											'descripcion_1-2' => 'Buscamos fomentar la escucha activa y la participación ciudadana para generar una co-creación de soluciones innovadoras para problemáticas cotidianas.',
											'cita_destacada' => '“Aquel que tiene un porqué para vivir puede enfrentarse a todos los cómo”',
											'cita_destacada_autor' => 'Friedrich Nietzsche',
											'cita_destacada_autor_profesion' => 'Filosofo, poeta y músico alemán.',
											'titulo_2' => 'El trabajo cotidiano: <strong>diversión</strong>',
											'descripcion_2' => 'Las personas son la parte esencial de Kit Urbano. Cada uno de los profesionales que conforma el equipo brinda algo único que hace que el trabajo no se sienta como tal y el día a día sea todo menos rutinario. Buscamos gente brillante y apasionada que nos siga inspirando y nos lleve a dar lo mejor en todos los planos.',
											'titulo_3' => '<strong>Impulsados</strong> por la pasión',
											'descripcion_3-1' => 'Un cambio concreto y duradero',
											'descripcion_3-2' => 'Estamos convencidos que se puede generar un cambio cierto y duradero en nuestra sociedad a través del habla como protagonista de la comunicación. Creemos que debemos valernos de la inteligencia colectiva para mejorar los procesos y servicios gubernamentales.',
											'titulo_4' => '<strong>Valores</strong> y espacio',
											'descripcion_4-1' => 'Un ambiente adecuado es vital para potenciar el desarrollo de cualquier organización. Creemos en lo valioso de las soluciones pequeñas a los problemas grandes. Trabajamos con el objetivo de lograr una herramienta que le brinde al ciudadano el poder de realizar un cambio en su entorno.',
											'descripcion_4-2' => 'El esfuerzo es continuidad y constancia; sacará adelante cualquier proyecto que encaremos. Es el arma invaluable con la que nos valemos en esta loca aventura de emprender. Nuestro proyecto es el futuro y concentra todo lo que viene. Los desafíos son enormes, pero estamos ansiosos por enfrentarlos.',
											'titulo_contacto' => '<strong>¿Estás interesado</strong> en las soluciones de Kit Urbano?',
											'descripcion_contacto' => 'Complete el formulario y en breve estaremos en contacto con usted.',
											'footer' => 'Copyright © 2016 Kit Urbano Todos los derechos reservados.'
										),
						'soluciones' => array(
											'titulo' => 'soluções',
											'descripcion' => 'Esta é uma descrição'
										),
						'casos' => array(
										'titulo' => 'Casos'
									),
						'blog' => array(
										'titulo' => 'Blog',
										'buscador' => 'buscar nota...'
									),
						'contacto' => array(
											'titulo' => 'Contato'
										)
					)
			
			
			);

				
				
	}
}