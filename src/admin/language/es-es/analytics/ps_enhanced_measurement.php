<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Corregir errores comunes';
$_['heading_getting_started']               = 'Comenzando';
$_['heading_setup']                         = 'Configurando (GA4) Enhanced Measurement';
$_['heading_troubleshot']                   = 'Solución de problemas comunes';
$_['heading_faq']                           = 'Preguntas Frecuentes';
$_['heading_contact']                       = 'Contactar Soporte';

// Text
$_['text_extension']                        = 'Extensiones';
$_['text_edit']                             = 'Editar (GA4) Enhanced Measurement';
$_['text_success']                          = 'Éxito: ¡Has modificado (GA4) Enhanced Measurement!';
$_['text_getting_started']                  = '<p><strong>Resumen:</strong> La extensión Playful Sparkle - GA4 Enhanced Measurement para OpenCart 4 proporciona capacidades avanzadas de seguimiento para tu tienda de comercio electrónico. Soporta múltiples opciones de seguimiento de eventos, incluyendo interacciones de usuarios, actividades en el carrito y eventos de compra. Además, permite la integración con Google Tag Manager o Global Site Tag, ofreciendo flexibilidad en la implementación de soluciones de medición.</p><p><strong>Requisitos:</strong> OpenCart 4.x, una cuenta válida de Google Analytics GA4, y las credenciales apropiadas según la Implementación de Medición seleccionada: se requiere el Google Tag ID y el API secreto del Measurement Protocol al utilizar Global Site Tag (gtag.js), y el Measurement ID es necesario si seleccionas Google Tag Manager (GTM). Asegúrate de que no haya otras extensiones de análisis activas para evitar conflictos de código.</p>';
$_['text_setup']                            = '<ul><li>Selecciona tu Implementación de Medición preferida (Global Site Tag o Google Tag Manager).</li><li>Si usas Global Site Tag, ingresa tu Google Tag ID y el API secreto del Measurement Protocol. Para Google Tag Manager, ingresa tu Measurement ID.</li><li>Configura los eventos de seguimiento que deseas habilitar, como inicio de sesión, compra o seguimiento de agregar al carrito.</li><li>Verifica que no haya otras extensiones que inyecten códigos de seguimiento (por ejemplo, Tag Manager o Global Site Tag) activas para evitar conflictos.</li><li>Guarda la configuración y prueba la implementación usando las herramientas de depuración de Google Analytics.</li></ul>';
$_['text_troubleshoot']                     = '<ul><li><strong>Problema:</strong> Los eventos no son visibles en el panel de Google Analytics. <strong>Solución:</strong> Confirma que el Measurement ID o Tag ID esté correctamente ingresado y coincida con tu propiedad GA4. Si usas Global Site Tag (gtag.js), asegúrate de que el Google Tag ID y el API secreto del Measurement Protocol estén correctamente configurados.</li><li><strong>Problema:</strong> Se están rastreando eventos duplicados. <strong>Solución:</strong> Verifica si otras extensiones de análisis están inyectando código de seguimiento y desactívalas si es necesario. Además, verifica que el mismo evento no esté siendo rastreado a través de múltiples implementaciones (por ejemplo, tanto GTM como gtag.js).</li><li><strong>Problema:</strong> El seguimiento no funciona en varias tiendas. <strong>Solución:</strong> Asegúrate de que el Tag ID o Measurement ID esté configurado correctamente para cada tienda. Para GTM, asegúrate de que cada tienda tenga el contenedor apropiado configurado en Google Tag Manager.</li><li><strong>Problema:</strong> Los datos de reembolsos no son visibles en Google Analytics. <strong>Solución:</strong> Permite tiempo para que los datos de reembolsos aparezcan en Google Analytics y asegúrate de que el reembolso esté correctamente configurado como parcial o completo, ya que solo se acepta una presentación por pedido.</li></ul>';
$_['text_faq']                              = '<details><summary>¿Por qué se ve el Modo de Consentimiento de Google (GCM) cuando selecciono Global Site Tag?</summary>Global Site Tag (gtag.js) no soporta ni requiere GCM.</details><details><summary>¿Por qué no hay una opción de modo de depuración para Google Tag Manager?</summary>Debes configurar el modo de depuración directamente en Google Tag Manager.</details><details><summary>¿Qué sucede si selecciono un ID de artículo diferente que no está disponible?</summary>Se usará el product_id en su lugar.</details><details><summary>¿Qué sucede si no completo la Afiliación?</summary>Se usará el nombre de la tienda.</details><details><summary>¿Puedo retrasar el envío de eventos a Google Analytics?</summary>Sí, marca la pestaña de Eventos de Seguimiento y el campo de Retraso de Seguimiento.</details><details><summary>¿Por qué no aparecen los datos de reembolsos en Google Analytics?</summary>Los datos de reembolsos pueden tardar en aparecer en Google Analytics.</details><details><summary>¿Por qué no puedo reembolsar más de una vez?</summary>Google Analytics solo acepta una presentación de reembolso por pedido. Puedes procesar un reembolso parcial o completo.</details><details><summary>¿Qué eventos son compatibles?</summary>Los eventos compatibles son: add_payment_info, add_shipping_info, add_to_cart, add_to_wishlist, begin_checkout, generate_lead, login, purchase, refund, remove_from_cart, search, select_item, select_promotion, sign_up, view_cart, view_item, view_item_list, view_promotion.</details><details><summary>¿Cómo funciona el evento de agregar al carrito?</summary>El evento de agregar al carrito se activa solo cuando el usuario realmente agrega un producto al carrito. De lo contrario, se activa el evento select_item o select_promotion dependiendo de si es un producto especial o no.</details>';
$_['text_contact']                          = '<p>Para más asistencia, por favor contacta a nuestro equipo de soporte:</p><ul><li><strong>Contacto:</strong> <a href="mailto:%s">%s</a></li><li><strong>Documentación:</strong> <a href="%s" target="_blank" rel="noopener noreferrer">Documentación del usuario</a></li></ul>';
$_['text_gtag']                             = 'Global Site Tag - gtag.js';
$_['text_gtm']                              = 'Google Tag Manager';
$_['text_item_options_group']               = 'Opciones de artículo';
$_['text_store_options_group']              = 'Opciones de tienda';
$_['text_product_id']                       = 'ID de producto';
$_['text_model']                            = 'Modelo';
$_['text_sku']                              = 'SKU';
$_['text_upc']                              = 'UPC';
$_['text_ean']                              = 'EAN';
$_['text_jan']                              = 'JAN';
$_['text_isbn']                             = 'ISBN';
$_['text_mpn']                              = 'MPN';
$_['text_default']                          = '(predeterminado)';
$_['text_category_option_type_1']           = 'El último segmento de todas las categorías asociadas al producto';
$_['text_category_option_type_2']           = 'Todas las categorías, con los nombres de categoría separados por el símbolo "&gt;" asociadas al producto';
$_['text_category_option_type_3']           = 'Los nombres de categoría actuales asociadas al producto';
$_['text_category_option_type_4']           = 'El último segmento del nombre de la categoría actual asociada al producto';
$_['text_multi_currency']                   = 'Multi-moneda';
$_['text_refund_quantity']                  = 'Cantidad';
$_['text_refund_successfully_sent']         = 'Éxito: Los datos de reembolso se han enviado exitosamente a Google Analytics.';
$_['text_group_ad_settings']                = 'Configuración de anuncios';
$_['text_group_analytics_settings']         = 'Configuración de análisis';
$_['text_group_security_settings']          = 'Configuración de seguridad';
$_['text_group_advanced_settings']          = 'Configuración avanzada';
$_['text_product_already_refunded']         = 'Este producto ya ha sido reembolsado. No se pueden realizar más acciones.';
$_['text_gcm_info']                         = 'El Modo de Consentimiento de Google (GCM) solo funciona cuando eliges Google Tag Manager en el menú desplegable de Implementación de Medición. No funciona con Global Site Tag (gtag.js). Para usar esta función, asegúrate de tener un banner de cookies instalado. Esta extensión establece un estado básico de consentimiento por defecto, pero el banner de cookies es responsable de actualizar el consentimiento para permitir la recolección de datos.';

// Column
$_['column_refund_quantity']                = 'Cantidad de Reembolso';

// Tab
$_['tab_general']                           = 'General';
$_['tab_gcm']                               = 'Modo de Consentimiento de Google (GCM)';
$_['tab_track_events']                      = 'Seguimiento de Eventos';
$_['tab_help_and_support']                  = 'Ayuda y Soporte';
$_['tab_gtag']                              = 'Etiqueta Global del Sitio - gtag.js';
$_['tab_gtm']                               = 'Administrador de Etiquetas de Google (GTM)';

// Entry
$_['entry_status']                          = 'Estado';
$_['entry_measurement_implementation']      = 'Implementación de Medición';
$_['entry_google_tag_id']                   = 'Google Tag ID';
$_['entry_gtm_id']                          = 'Measurement ID';
$_['entry_measurement_protocol_api_secret'] = 'Measurement Protocol API Secret';
$_['entry_item_id']                         = 'ID del Artículo';
$_['entry_item_category_option']            = 'Categoría del Artículo';
$_['entry_tracking_delay']                  = 'Retraso en el Seguimiento';
$_['entry_affiliation']                     = 'Afiliación';
$_['entry_location_id']                     = 'ID de Ubicación';
$_['entry_item_price_tax']                  = 'Mostrar Precios con Impuesto';
$_['entry_currency']                        = 'Moneda';
$_['entry_debug_mode']                      = 'Modo de Depuración';
$_['entry_gtag_debug_mode']                 = 'Depuración de la Etiqueta Global del Sitio';
$_['entry_generate_lead']                   = 'Rastrear Evento de Generación de Prospecto';
$_['entry_sign_up']                         = 'Rastrear Evento de Registro';
$_['entry_login']                           = 'Rastrear Evento de Inicio de Sesión';
$_['entry_add_to_wishlist']                 = 'Rastrear Evento de Agregar a la Lista de Deseos';
$_['entry_add_to_cart']                     = 'Rastrear Evento de Agregar al Carrito';
$_['entry_remove_from_cart']                = 'Rastrear Evento de Eliminar del Carrito';
$_['entry_search']                          = 'Rastrear Evento de Búsqueda';
$_['entry_view_item_list']                  = 'Rastrear Evento de Ver Lista de Artículos';
$_['entry_select_item']                     = 'Rastrear Evento de Seleccionar Artículo';
$_['entry_view_item']                       = 'Rastrear Evento de Ver Artículo';
$_['entry_select_promotion']                = 'Rastrear Evento de Seleccionar Promoción';
$_['entry_view_promotion']                  = 'Rastrear Evento de Ver Promoción';
$_['entry_view_cart']                       = 'Rastrear Evento de Ver Carrito';
$_['entry_begin_checkout']                  = 'Rastrear Evento de Iniciar Pago';
$_['entry_add_payment_info']                = 'Rastrear Evento de Agregar Información de Pago';
$_['entry_add_shipping_info']               = 'Rastrear Evento de Agregar Información de Envío';
$_['entry_purchase']                        = 'Rastrear Evento de Compra';
$_['entry_user_id']                         = 'Enviar ID de Usuario';
$_['entry_gcm_status']                      = 'Habilitar GCM';
$_['entry_ad_storage']                      = 'Almacenamiento de Anuncios';
$_['entry_ad_user_data']                    = 'Datos de Usuario de Anuncios';
$_['entry_ad_personalization']              = 'Personalización de Anuncios';
$_['entry_analytics_storage']               = 'Almacenamiento de Análisis';
$_['entry_functionality_storage']           = 'Almacenamiento de Funcionalidad';
$_['entry_personalization_storage']         = 'Almacenamiento de Personalización';
$_['entry_security_storage']                = 'Almacenamiento de Seguridad';
$_['entry_wait_for_update']                 = 'Esperar Actualización';
$_['entry_ads_data_redaction']              = 'Redacción de Datos de Anuncios';
$_['entry_url_passthrough']                 = 'Transmisión de URL';
$_['entry_strict']                          = 'Estricto';
$_['entry_balanced']                        = 'Equilibrado';
$_['entry_custom']                          = 'Personalizado';
$_['entry_gcm_profiles']                    = 'Perfiles de GCM';

// Button
$_['button_fix_event_handler']              = 'Corregir Controlador de Eventos';
$_['button_refund']                         = 'Reembolsar';
$_['button_refund_selected']                = 'Reembolsar Seleccionado';
$_['button_refund_all']                     = 'Reembolsar Todo';

// Help
$_['help_google_tag_id_locate']             = 'Para encontrar tu Google Tag ID, inicia sesión en tu <a href="https://analytics.google.com" target="_blank" rel="external noopener noreferrer">cuenta de Analytics</a>. Ve a la sección de Administración, selecciona la propiedad que deseas rastrear y localiza tu Google Tag ID. Comenzará con "G-" seguido de una combinación única de letras y números, como "G-XXXXXXXXXX". <a href="https://support.google.com/analytics/answer/9539598?hl=es" target="_blank" rel="external noopener noreferrer">Instrucciones más detalladas aquí</a>.';
$_['help_gtm_id_locate']                    = 'Para encontrar tu Measurement ID para tu <a href="https://tagmanager.google.com" target="_blank" rel="external noopener noreferrer">cuenta de Google Tag Manager</a>, busca el ID en la parte superior del panel de trabajo, comenzando con "GTM-" seguido de una serie única de letras y números, como "GTM-XXXXXXXX". <a href="https://support.google.com/analytics/answer/12270356?hl=es" target="_blank" rel="external noopener noreferrer">Instrucciones más detalladas aquí</a>.';
$_['help_mp_api_secret_locate']             = 'Para encontrar tu Measurement Protocol API Secret, ve a tu <a href="https://analytics.google.com/" target="_blank" rel="external noopener noreferrer">cuenta de Google Analytics</a>. Navega a Administración en el menú de la izquierda, luego bajo la Configuración de la Propiedad, selecciona Flujos de Datos. Elige tu flujo de datos y desplázate hacia abajo hasta la sección de secretos de Measurement Protocol API. Aquí podrás crear un nuevo secreto de API o encontrar los existentes. El secreto de API es una cadena única, por ejemplo, XXXXXXX-XXXXXXX-XXXXXX, utilizada para autenticar las solicitudes del lado del servidor.';
$_['help_affiliation']                      = 'Ingresa el nombre de la tienda o departamento para la parte de <strong>afiliación</strong> del seguimiento de comercio electrónico. Si dejas este campo vacío, se utilizará automáticamente el nombre de la tienda predeterminado de la configuración.';
$_['help_location_id']                      = 'La ubicación física del artículo, como la tienda donde se vende. Lo ideal es usar el <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="external noopener noreferrer">Google Place ID</a> para esa ubicación, pero también puedes usar un ID de ubicación personalizado.';
$_['help_tracking_delay']                   = 'Especifica el retraso (en milisegundos) antes de ejecutar la acción predeterminada (por ejemplo, navegar a un enlace o enviar un formulario) después de que se envíe el evento de GA4. Esto asegura que el evento se rastree correctamente antes de que se complete la acción. Déjalo vacío para usar el valor predeterminado.';
$_['help_generate_lead']                    = 'Este evento mide cuando se ha generado un lead, específicamente rastreando suscripciones a boletines y envíos de formularios de contacto. Úsalo para entender la efectividad de tus campañas de marketing y cuántos clientes vuelven a interactuar con tu negocio después de una reorientación.';
$_['help_sign_up']                          = 'Este evento indica que un usuario se ha registrado en una cuenta. Úsalo para comprender los diferentes comportamientos de los usuarios que han iniciado sesión y los que no han iniciado sesión.';
$_['help_login']                            = 'Envía este evento para señalar que un usuario ha iniciado sesión en tu sitio web o aplicación.';
$_['help_add_to_wishlist']                  = 'Este evento indica que un artículo se ha añadido a una lista de deseos. Úsalo para identificar los artículos populares para regalos en tu aplicación.';
$_['help_add_to_cart']                      = 'Este evento indica que un artículo se ha añadido a un carrito para su compra.';
$_['help_remove_from_cart']                 = 'Este evento indica que un artículo se ha eliminado de un carrito.';
$_['help_search']                           = 'Registra este evento para indicar cuando el usuario ha realizado una búsqueda. Úsalo para identificar lo que los usuarios están buscando en tu sitio web o aplicación. Por ejemplo, envía este evento cuando un usuario vea una página de resultados de búsqueda después de realizar una búsqueda.';
$_['help_view_item_list']                   = 'Registra este evento cuando al usuario se le presenta una lista de artículos de una determinada categoría.';
$_['help_select_item']                      = 'Este evento indica que un artículo fue seleccionado de una lista.';
$_['help_view_item']                        = 'Este evento indica que se mostró contenido al usuario. Úsalo para descubrir los artículos más populares vistos.';
$_['help_select_promotion']                 = 'Este evento indica que se seleccionó una promoción de una lista.';
$_['help_view_promotion']                   = 'Este evento indica que se visualizó una promoción de una lista.';
$_['help_view_cart']                        = 'Este evento indica que un usuario visualizó su carrito.';
$_['help_begin_checkout']                   = 'Este evento indica que un usuario ha comenzado el proceso de pago.';
$_['help_add_payment_info']                 = 'Este evento indica que un usuario ha enviado su información de pago en un proceso de pago de comercio electrónico.';
$_['help_add_shipping_info']                = 'Este evento indica que un usuario ha enviado su información de envío en un proceso de pago de comercio electrónico.';
$_['help_purchase']                         = 'Este evento indica cuando un usuario ha comprado uno o más artículos.';
$_['help_user_id']                          = 'Esta opción habilita el seguimiento de los ID de usuarios que han iniciado sesión, lo que te permite comprender mejor el comportamiento de los usuarios a través de sesiones y dispositivos, proporcionando análisis más precisos y detallados.';
$_['help_ad_storage']                       = 'Controla si se permite el almacenamiento de datos para fines relacionados con los anuncios, como el seguimiento de clics o conversiones en los anuncios.';
$_['help_ad_user_data']                     = 'Determina si se almacenan los datos sobre los usuarios que interactúan con los anuncios, mejorando las capacidades de segmentación de los anuncios.';
$_['help_ad_personalization']               = 'Permite que los anuncios se personalicen según los datos del usuario, proporcionando anuncios más relevantes para los usuarios.';
$_['help_analytics_storage']                = 'Habilita el almacenamiento de datos utilizados para fines de análisis, ayudando a realizar un seguimiento del rendimiento del sitio y el comportamiento del usuario.';
$_['help_functionality_storage']            = 'Permite el almacenamiento de datos para soportar funcionalidades, como preferencias de usuario o características del sitio que mejoran la experiencia del usuario.';
$_['help_personalization_storage']          = 'Controla el almacenamiento de datos para personalizar la experiencia del usuario, como contenido recomendado o configuraciones.';
$_['help_security_storage']                 = 'Asegura el almacenamiento de datos relacionados con la seguridad, como para la prevención de fraudes y el control de acceso seguro.';
$_['help_wait_for_update']                  = 'Establece el tiempo (en milisegundos) para retrasar la actualización del estado de consentimiento, asegurando que se apliquen todos los ajustes.';
$_['help_ads_data_redaction']               = 'Redacta los datos del usuario relacionados con los anuncios, asegurando la privacidad al ocultar cierta información identificable.';
$_['help_url_passthrough']                  = 'Permite que la URL pase por los controles de consentimiento, útil para rastrear caminos específicos de los usuarios sin almacenar datos personales.';
$_['help_gcm_status']                       = 'Habilita el Modo de Consentimiento de Google, permitiendo que tu sitio ajuste el comportamiento de las etiquetas de Google según los ajustes de consentimiento del usuario. Este modo proporciona un seguimiento respetuoso con la privacidad, permitiendo que los análisis y anuncios funcionen en conformidad con las preferencias de consentimiento.';

// Error
$_['error_permission']                      = 'Advertencia: No tiene permiso para modificar la configuración de (GA4) Enhanced Measurement.';
$_['error_refund_send']                     = 'Advertencia: No se pudo enviar los datos de reembolso a Google Analytics (GA4). Verifique su configuración e inténtelo nuevamente.';
$_['error_no_refundable_selected']          = 'Advertencia: No se seleccionaron productos para el reembolso. Por favor, seleccione al menos un producto para procesar el reembolso.';
$_['error_google_tag_id']                   = 'El campo Google Tag ID es obligatorio. Por favor, ingrese su ID de Google Analytics.';
$_['error_google_tag_id_invalid']           = 'El formato de Google Tag ID es incorrecto. Asegúrese de que siga el formato G-XXXXXXXXXX.';
$_['error_gtm_id']                          = 'El campo GTM ID es obligatorio. Por favor, ingrese su Measurement ID.';
$_['error_gtm_id_invalid']                  = 'El formato de GTM ID es incorrecto. Asegúrese de que siga el formato GTM-XXXXXXXX.';
$_['error_mp_api_secret']                   = 'El campo Measurement Protocol API Secret es obligatorio. Por favor, ingrese su Measurement Protocol API Secret.';
$_['error_mp_api_secret_invalid']           = 'El formato de Measurement Protocol API Secret es incorrecto. Asegúrese de que siga el formato XXXXXXX-XXXXXXX-XXXXXX.';
$_['error_measurement_implementation']      = 'La implementación de medición no está configurada. Por favor, seleccione Global Site Tag o Google Tag Manager.';
$_['error_client_id']                       = 'Advertencia: El Client ID no fue guardado durante el proceso de pago.';
$_['error_refund_no_items']                 = 'Advertencia: No se encontró el ID de producto asociado con este pedido.';
$_['error_refund_no_order_id']              = 'Advertencia: Faltan o son incompletos los parámetros requeridos en la solicitud.';
$_['error_analytics_extension']             = 'Parece que otra herramienta de análisis ya está activa en su sitio. Tener más de una herramienta de este tipo puede generar problemas, como seguimiento duplicado o faltante. Verifique la configuración de su sitio.';
$_['error_tracking_delay']                  = 'El retraso de seguimiento debe ser al menos de 100 milisegundos para asegurar un seguimiento adecuado de los eventos.';
$_['error_wait_for_update']                 = 'El valor de Esperar para actualización debe ser un número entre 0 y 10000 milisegundos.';
