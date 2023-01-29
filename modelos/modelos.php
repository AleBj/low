<?php
class usuario extends ActiveRecord\Model{}
class role extends ActiveRecord\Model
{
	public static $table_name = 'roles';
	public static $primary_key = 'role_id';
}
class permisos_role extends ActiveRecord\Model
{
	public static $table_name = 'permisos_roles';
}
class permiso extends ActiveRecord\Model
{
	public static $table_name = 'permisos';
	public static $primary_key = 'id_permiso';
}
///
class contenidos_imagene extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_imagenes';
	public static $primary_key = 'id';	
}
class contenidos_curso extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_cursos';
	public static $primary_key = 'id';	
}


class contenidos_cursos_categoria extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_cursos_categorias';
	public static $primary_key = 'id';	
}


class contenidos_grupo extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_grupos';
	public static $primary_key = 'id';	
}

class contenidos_cupon_descuento extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_cupon_descuentos';
	public static $primary_key = 'id';	
}

class contenidos_plataformas_pag extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_plataformas_pago';
	public static $primary_key = 'id';	
}


class contenidos_producto extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_productos';
	public static $primary_key = 'id';	
}

class contenidos_productos_variable extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_productos_variables';
	public static $primary_key = 'id';	
}

class contenidos_faq extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_faqs';
	public static $primary_key = 'id';	
}

class contenidos_howitwork extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_howitworks';
	public static $primary_key = 'id';	
}

class contenidos_termsandcondition extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_termsandconditions';
	public static $primary_key = 'id';	
}
class contenidos_disclaime extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_disclaimer';
	public static $primary_key = 'id';	
}

class contenidos_whyu extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_whyus';
	public static $primary_key = 'id';	
}

class contenidos_forms_aviso extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_forms_avisos';
	public static $primary_key = 'id';	
}

class contenidos_forms_modulo extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_forms_modulos';
	public static $primary_key = 'id';	
}

class contenidos_forms_respuesta extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_forms_respuestas';
	public static $primary_key = 'id';	
}

class contenidos_forms_circuit extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_forms_circuits';
	public static $primary_key = 'id';	
}

class contenidos_forms_doc extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_forms_docs';
	public static $primary_key = 'id';	
}

class contenidos_noticia extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_noticias';
	public static $primary_key = 'id';	
}

class contenidos_elementos_portad extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_elementos_portada';
	public static $primary_key = 'id';	
}


class contenidos_video extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_videos';
	public static $primary_key = 'id';	
}

class contenidos_shippin extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_shipping';
	public static $primary_key = 'id';	
}

class contenidos_billin extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_billing';
	public static $primary_key = 'id';	
}

class contenidos_compra extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_compras';
	public static $primary_key = 'id';	
}

class contenidos_compras_detall extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_compras_detalle';
	public static $primary_key = 'id';	
}




///////////////////////////////////////////////////////////////////


class contenidos_subcategoria extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_subcategorias';
	public static $primary_key = 'id';	
}

class contenidos_seccione extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_secciones';
	public static $primary_key = 'id';	
}
class contenidos_banner extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_banners';
	public static $primary_key = 'id';	
}

class contenidos_promocione extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_promociones';
	public static $primary_key = 'id';	
}

class contenidos_carrito extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_carritos';
	public static $primary_key = 'id';	
}
class contenidos_direccion_envio extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_direccion_envios';
	public static $primary_key = 'id';	
}
class contenidos_datos_facturacio extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_datos_facturacion';
	public static $primary_key = 'id';	
}

class contenidos_facturacion_cfd extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_facturacion_cfdi';
	public static $primary_key = 'id';	
}

class contenidos_facturacion_direccione extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_facturacion_direcciones';
	public static $primary_key = 'id';	
}
class contenidos_medidas_caja extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_medidas_cajas';
	public static $primary_key = 'id';	
}
class contenidos_envios_codpostale extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_envios_codpostales';
	public static $primary_key = 'id';	
}

class contenidos_pedido extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_pedidos';
	public static $primary_key = 'id';	
}
class contenidos_user extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_users';
	public static $primary_key = 'id';	
}
class contenidos_tag extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_tags';
	public static $primary_key = 'id';	
}
class contenidos_descuento extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_descuentos';
	public static $primary_key = 'id';	
}
class contenidos_blo extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_blog';
	public static $primary_key = 'id';	
}
class contenidos_pres extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_press';
	public static $primary_key = 'id';	
}
class contenidos_archivos_exce extends ActiveRecord\Model
{
	public static $table_name = 'contenidos_archivos_excel';
	public static $primary_key = 'id';
}



/// Menues
class widgets_menu_primario extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_primarios';
	public static $primary_key = 'id';	
}
class widgets_menu_primario_pie extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_primario_pies';
	public static $primary_key = 'id';	
}
class widgets_menu_secundario extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_secundarios';
	public static $primary_key = 'id';	
}
class widgets_menu_administrador extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_administradors';
	public static $primary_key = 'id';	
}
class widgets_menu_administracion extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_administracions';
	public static $primary_key = 'id';	
}
class widgets_menu_submenu extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_submenus';
	public static $primary_key = 'id';	
}
class widgets_menu_permiso extends ActiveRecord\Model
{
	public static $table_name = 'widgets_menu_permisos';
	public static $primary_key = 'id';	
}
// Notificaciones
class usuarios_notificacione extends ActiveRecord\Model
{
	public static $table_name = 'usuarios_notificaciones';
	public static $primary_key = 'id';	
}
class usuarios_notificaciones_pivot extends ActiveRecord\Model
{
	public static $table_name = 'usuarios_notificaciones_pivots';
	public static $primary_key = 'id';	
}
class usuario_expositore extends ActiveRecord\Model
{
	public static $table_name = 'usuario_expositores';
	public static $primary_key = 'id';	
}
class usuario_informe extends ActiveRecord\Model
{
	public static $table_name = 'usuario_informes';
	public static $primary_key = 'id';	
}
// Acl
/*class role extends ActiveRecord\Model{}
class permisos_role extends ActiveRecord\Model{}
class permiso extends ActiveRecord\Model{}
class permisos_usuario extends ActiveRecord\Model{}*/