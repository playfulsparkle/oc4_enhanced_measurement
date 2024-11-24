<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Opravit běžné chyby';
$_['heading_getting_started']               = 'Začínáme';
$_['heading_setup']                         = 'Nastavení (GA4) Enhanced Measurement';
$_['heading_troubleshoot']                  = 'Běžné problémy';
$_['heading_faq']                           = 'Často kladené dotazy';
$_['heading_contact']                       = 'Kontaktujte podporu';

// Text
$_['text_extension']                        = 'Rozšíření';
$_['text_edit']                             = 'Upravit (GA4) Enhanced Measurement';
$_['text_success']                          = 'Úspěch: Úspěšně jste upravili (GA4) Enhanced Measurement!';
$_['text_getting_started']                  = '<p><strong>Přehled:</strong> Rozšíření Playful Sparkle - GA4 Enhanced Measurement pro OpenCart 4 poskytuje pokročilé možnosti sledování pro váš eCommerce obchod. Podporuje více možností sledování událostí, včetně interakcí uživatelů, aktivit v košíku a nákupních událostí. Dále umožňuje integraci s Google Tag Managerem nebo Global Site Tag, což nabízí flexibilitu při implementaci měřících řešení.</p><p><strong>Požadavky:</strong> OpenCart 4.x, platný účet Google Analytics GA4 a příslušné přihlašovací údaje podle vybraného měřícího řešení: Google Tag ID a Measurement Protocol API tajný klíč jsou vyžadovány při použití Global Site Tag (gtag.js), a Measurement ID je vyžadován, pokud zvolíte Google Tag Manager (GTM). Zajistěte, aby žádná jiná analytická rozšíření nebyla aktivní, aby se předešlo konfliktům kódu.</p>';
$_['text_setup']                            = '<ul><li>Vyberte preferované měřící řešení (Global Site Tag nebo Google Tag Manager).</li><li>Pokud používáte Global Site Tag, zadejte své Google Tag ID a Measurement Protocol API tajný klíč. Pro Google Tag Manager zadejte své Measurement ID.</li><li>Konfigurujte události sledování, které chcete povolit, jako přihlášení, nákup nebo přidání do košíku.</li><li>Ověřte, že žádná jiná rozšíření nezadávají sledovací kódy (např. Tag Manager nebo Global Site Tag), aby se předešlo konfliktům.</li><li>Uložte nastavení a otestujte implementaci pomocí nástrojů pro ladění Google Analytics.</li></ul>';
$_['text_troubleshoot']                     = '<ul><li><strong>Problém:</strong> Události nejsou viditelné na panelu Google Analytics. <strong>Řešení:</strong> Ujistěte se, že Measurement ID nebo Tag ID je správně zadáno a odpovídá vašemu GA4 účtu. Pokud používáte Global Site Tag (gtag.js), ujistěte se, že Google Tag ID a Measurement Protocol API tajný klíč jsou správně nakonfigurovány.</li><li><strong>Problém:</strong> Duplikované události jsou sledovány. <strong>Řešení:</strong> Zkontrolujte, zda jiná analytická rozšíření nezadávají sledovací kód a deaktivujte je, pokud je to nutné. Také ověřte, zda není stejná událost sledována prostřednictvím více implementací (např. GTM a gtag.js).</li><li><strong>Problém:</strong> Sledování nefunguje napříč více obchody. <strong>Řešení:</strong> Ujistěte se, že správné Tag ID nebo Measurement ID je nakonfigurováno pro každý obchod. Pro GTM se ujistěte, že pro každý obchod je v Google Tag Manageru nastaven správný kontejner.</li><li><strong>Problém:</strong> Data o refundacích nejsou viditelná v Google Analytics. <strong>Řešení:</strong> Nechte nějaký čas na zobrazení dat o refundacích v Google Analytics a ujistěte se, že refundace je správně nakonfigurována jako částečná nebo plná, protože je akceptováno pouze jedno podání refundace na objednávku.</li></ul>';
$_['text_faq']                              = '<details><summary>Proč je Google Consent Mode (GCM) viditelný, když vyberu Global Site Tag?</summary>Global Site Tag (gtag.js) nepodporuje ani nevyžaduje GCM.</details><details><summary>Proč není v Google Tag Manageru možnost ladícího režimu?</summary>Ladící režim je nutné nastavit přímo v Google Tag Manageru.</details><details><summary>Co se stane, když vyberu jiný ID položky, která není k dispozici?</summary>Produkt_id bude použit místo toho.</details><details><summary>Co se stane, když nevyplním Affiliation?</summary> Bude použit název obchodu.</details><details><summary>Mohou být události odesílány se zpožděním do Google Analytics?</summary>Ano, podívejte se na kartu Sledování událostí a pole Sledování zpoždění.</details><details><summary>Proč se moje data o refundacích nezobrazují v Google Analytics?</summary>Data o refundacích mohou v Google Analytics trvat delší dobu, než se objeví.</details><details><summary>Proč nemohu refundovat více než jednou?</summary>Google Analytics přijímá pouze jedno podání refundace na objednávku. Můžete zpracovat buď částečnou, nebo plnou refundaci.</details><details><summary>Jaké události jsou podporovány?</summary>Podporované události jsou: add_payment_info, add_shipping_info, add_to_cart, add_to_wishlist, begin_checkout, generate_lead, login, purchase, refund, remove_from_cart, search, select_item, select_promotion, sign_up, view_cart, view_item, view_item_list, view_promotion.</details><details><summary>Jak funguje událost přidání do košíku?</summary>Událost přidání do košíku je spuštěna pouze tehdy, když uživatel skutečně přidá produkt do košíku. Jinak je spuštěna událost select_item nebo select_promotion na základě toho, zda se jedná o speciální produkt.</details>';
$_['text_contact']                          = '<p>Pro další pomoc kontaktujte náš tým podpory:</p><ul><li><strong>Kontakt:</strong> <a href="mailto:%s">%s</a></li><li><strong>Dokumentace:</strong> <a href="%s" target="_blank" rel="noopener noreferrer">Uživatelská dokumentace</a></li></ul>';
$_['text_gtag']                             = 'Global Site Tag - gtag.js';
$_['text_gtm']                              = 'Google Tag Manager';
$_['text_item_options_group']               = 'Možnosti položek';
$_['text_store_options_group']              = 'Možnosti obchodu';
$_['text_product_id']                       = 'ID produktu';
$_['text_model']                            = 'Model';
$_['text_sku']                              = 'SKU';
$_['text_upc']                              = 'UPC';
$_['text_ean']                              = 'EAN';
$_['text_jan']                              = 'JAN';
$_['text_isbn']                             = 'ISBN';
$_['text_mpn']                              = 'MPN';
$_['text_default']                          = '(výchozí)';
$_['text_category_option_type_1']           = 'Poslední segment všech kategorií spojených s produktem';
$_['text_category_option_type_2']           = 'Všechny kategorie, názvy kategorií oddělené symbolem "&gt;" spojené s produktem';
$_['text_category_option_type_3']           = 'Aktuální názvy kategorií spojené s produktem';
$_['text_category_option_type_4']           = 'Poslední segment aktuálního názvu kategorie spojené s produktem';
$_['text_multi_currency']                   = 'Více měn';
$_['text_refund_quantity']                  = 'Množství';
$_['text_refund_successfully_sent']         = 'Úspěch: Data o refundacích byla úspěšně odeslána do Google Analytics.';
$_['text_group_ad_settings']                = 'Nastavení reklam';
$_['text_group_analytics_settings']         = 'Nastavení analytiky';
$_['text_group_security_settings']          = 'Nastavení zabezpečení';
$_['text_group_advanced_settings']          = 'Pokročilá nastavení';
$_['text_gcm_info']                         = 'Google Consent Mode (GCM) funguje pouze při výběru Google Tag Manager (GTM) nebo Global Site Tag (gtag.js). Pokud používáte jiný měřící nástroj, GCM nebude podporován.';

// Column
$_['column_refund_quantity']                = 'Množství refundace';

// Tab
$_['tab_general']                           = 'Obecné';
$_['tab_gcm']                               = 'Google Consent Mode (GCM)';
$_['tab_track_events']                      = 'Sledování událostí';
$_['tab_help_and_support']                  = 'Nápověda a podpora';
$_['tab_gtag']                              = 'Global Site Tag - gtag.js';
$_['tab_gtm']                               = 'Google Tag Manager (GTM)';

// Entry
$_['entry_status']                          = 'Stav';
$_['entry_measurement_implementation']      = 'Implementace měření';
$_['entry_google_tag_id']                   = 'Google Tag ID';
$_['entry_gtm_id']                          = 'ID měření';
$_['entry_measurement_protocol_api_secret'] = 'API tajemství protokolu měření';
$_['entry_item_id']                         = 'ID položky';
$_['entry_item_category_option']            = 'Kategorie položky';
$_['entry_tracking_delay']                  = 'Zpoždění sledování';
$_['entry_affiliation']                     = 'Afiliace';
$_['entry_location_id']                     = 'ID místa';
$_['entry_item_price_tax']                  = 'Zobrazit ceny s DPH';
$_['entry_currency']                        = 'Měna';
$_['entry_debug_mode']                      = 'Režim ladění';
$_['entry_gtag_debug_mode']                 = 'Ladění Global Site Tag';
$_['entry_generate_lead']                   = 'Sledovat událost Generování leadu';
$_['entry_sign_up']                         = 'Sledovat událost Registrace';
$_['entry_login']                           = 'Sledovat událost Přihlášení';
$_['entry_add_to_wishlist']                 = 'Sledovat událost Přidání do seznamu přání';
$_['entry_add_to_cart']                     = 'Sledovat událost Přidání do košíku';
$_['entry_remove_from_cart']                = 'Sledovat událost Odebrání z košíku';
$_['entry_search']                          = 'Sledovat událost Vyhledávání';
$_['entry_view_item_list']                  = 'Sledovat událost Zobrazení seznamu položek';
$_['entry_select_item']                     = 'Sledovat událost Výběr položky';
$_['entry_view_item']                       = 'Sledovat událost Zobrazení položky';
$_['entry_select_promotion']                = 'Sledovat událost Výběr propagace';
$_['entry_view_promotion']                  = 'Sledovat událost Zobrazení propagace';
$_['entry_view_cart']                       = 'Sledovat událost Zobrazení košíku';
$_['entry_begin_checkout']                  = 'Sledovat událost Zahájení nákupu';
$_['entry_add_payment_info']                = 'Sledovat událost Přidání informací o platbě';
$_['entry_add_shipping_info']               = 'Sledovat událost Přidání informací o doručení';
$_['entry_purchase']                        = 'Sledovat událost Nákup';
$_['entry_user_id']                         = 'Odeslat ID uživatele';
$_['entry_gcm_status']                      = 'Povolit GCM';
$_['entry_ad_storage']                      = 'Úložiště reklam';
$_['entry_ad_user_data']                    = 'Údaje o uživatelských reklamách';
$_['entry_ad_personalization']              = 'Personalizace reklam';
$_['entry_analytics_storage']               = 'Úložiště analytických dat';
$_['entry_functionality_storage']           = 'Úložiště funkcionalit';
$_['entry_personalization_storage']         = 'Úložiště personalizace';
$_['entry_security_storage']                = 'Úložiště bezpečnosti';
$_['entry_wait_for_update']                 = 'Počkejte na aktualizaci';
$_['entry_ads_data_redaction']              = 'Redakce dat o reklamách';
$_['entry_url_passthrough']                 = 'URL Passthrough';
$_['entry_strict']                          = 'Přísný';
$_['entry_balanced']                        = 'Vyvážený';
$_['entry_custom']                          = 'Vlastní';
$_['entry_gcm_profiles']                    = 'GCM Profily';

// Button
$_['button_fix_event_handler']              = 'Opravit obslužnou rutinu událostí';
$_['button_refund']                         = 'Refundace';
$_['button_refund_all']                     = 'Refundovat vše';

// Help
$_['help_google_tag_id_locate']             = 'Chcete-li najít své Google Tag ID, přihlaste se do svého <a href="https://analytics.google.com" target="_blank" rel="external noopener noreferrer">účtu Analytics</a>. Přejděte do sekce Admin, vyberte vlastnost, kterou chcete sledovat, a najděte své Google Tag ID. Začne písmenem "G-" následovaným unikátní kombinací písmen a čísel, např. "G-XXXXXXXXXX". <a href="https://support.google.com/analytics/answer/9539598?hl=cs" target="_blank" rel="external noopener noreferrer">Podrobnější návod zde</a>.';
$_['help_gtm_id_locate']                    = 'Chcete-li najít své ID měření pro svůj <a href="https://tagmanager.google.com" target="_blank" rel="external noopener noreferrer">účet Google Tag Manager</a>, hledejte ID v horní části panelu pracovního prostoru – začíná písmeny "GTM-" následovanými unikátní sérií písmen a čísel, například "GTM-XXXXXXXX". <a href="https://support.google.com/analytics/answer/12270356?hl=cs" target="_blank" rel="external noopener noreferrer">Podrobnější návod zde</a>.';
$_['help_mp_api_secret_locate']             = 'Chcete-li najít své API tajemství protokolu měření, přejděte do svého <a href="https://analytics.google.com/" target="_blank" rel="external noopener noreferrer">účtu Google Analytics</a>. Přejděte do sekce Admin v levém menu, poté v nastavení vlastnosti vyberte Data Streams. Vyberte svůj datový tok a sjeďte dolů na sekci API tajemství protokolu měření. Zde můžete vytvořit nové API tajemství nebo najít existující. API tajemství je unikátní řetězec, např. XXXXXXX-XXXXXXX-XXXXXX, sloužící k autentifikaci požadavků na server.';
$_['help_affiliation']                      = 'Zadejte název obchodu nebo oddělení pro část <strong>afiliace</strong> sledování e-commerce. Pokud to necháte prázdné, automaticky se použije výchozí název obchodu z nastavení.';
$_['help_location_id']                      = 'Fyzická lokalita položky, například obchod, kde je prodávána. Nejlepší je použít <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="external noopener noreferrer">Google Place ID</a> pro tuto lokalitu, ale můžete použít i vlastní ID místa.';
$_['help_tracking_delay']                   = 'Zadejte zpoždění (v milisekundách), které má být vyčkáváno před provedením výchozí akce (např. přechod na odkaz nebo odeslání formuláře) po odeslání události GA4. Tím se zajistí správné sledování události před dokončením akce. Nechte prázdné pro použití výchozí hodnoty.';
$_['help_generate_lead']                    = 'Tato událost měří, kdy byl vytvořen lead, konkrétně sleduje přihlášení k odběru newsletteru a odeslání kontaktního formuláře. Použijte ji k pochopení účinnosti vašich marketingových kampaní a jak mnoho zákazníků se opět zapojuje s vaší firmou po remarketingu.';
$_['help_sign_up']                          = 'Tato událost označuje, že uživatel se zaregistroval pro účet. Použijte ji k pochopení různých chování přihlášených a neprihlášených uživatelů.';
$_['help_login']                            = 'Odeslat tuto událost k označení, že uživatel se přihlásil na vaše webové stránky nebo aplikaci.';
$_['help_add_to_wishlist']                  = 'Tato událost označuje, že položka byla přidána do seznamu přání. Použijte ji k identifikaci populárních dárkových předmětů ve vaší aplikaci.';
$_['help_add_to_cart']                      = 'Tato událost označuje, že položka byla přidána do nákupního košíku.';
$_['help_remove_from_cart']                 = 'Tato událost označuje, že položka byla odstraněna z nákupního košíku.';
$_['help_search']                           = 'Zaznamenejte tuto událost k označení, že uživatel použil funkci vyhledávání.';
$_['help_view_item_list']                   = 'Zaznamenejte tuto událost k označení, že uživatel zobrazil seznam položek, jako je seznam produktů nebo výsledky hledání.';
$_['help_select_item']                      = 'Tato událost označuje, že uživatel vybral konkrétní položku ze seznamu.';
$_['help_view_item']                        = 'Tato událost označuje, že uživatel zobrazil podrobnosti konkrétní položky.';
$_['help_select_promotion']                 = 'Zaznamenejte tuto událost, když uživatel vybere propagační nabídku, například výprodeje.';
$_['help_view_promotion']                   = 'Tato událost označuje, že uživatel viděl propagační nabídku, například výprodeje.';
$_['help_view_cart']                        = 'Zaznamenejte tuto událost, když uživatel navštíví stránku košíku.';
$_['help_begin_checkout']                   = 'Tato událost označuje, že uživatel zahájil nákup.';
$_['help_add_payment_info']                 = 'Tato událost označuje, že uživatel přidal platební informace.';
$_['help_add_shipping_info']                = 'Tato událost označuje, že uživatel přidal informace o doručení.';
$_['help_purchase']                         = 'Zaznamenejte tuto událost, když uživatel dokončí nákup.';
$_['help_user_id']                          = 'Tato možnost umožňuje sledování ID přihlášených uživatelů, což vám umožní lépe pochopit chování uživatelů napříč relacemi a zařízeními, což poskytuje přesnější a podrobnější analytiku.';
$_['help_ad_storage']                       = 'Ovládá, zda je povoleno ukládání dat pro účely související s reklamami, jako je sledování kliknutí na reklamy nebo konverze.';
$_['help_ad_user_data']                     = 'Určuje, zda jsou ukládána data o uživatelích, kteří interagují s reklamami, což zlepšuje schopnosti cílení reklam.';
$_['help_ad_personalization']               = 'Umožňuje personalizaci reklam na základě uživatelských dat, což poskytuje relevantnější reklamy pro uživatele.';
$_['help_analytics_storage']                = 'Povoluje ukládání dat používaných pro analytické účely, což pomáhá sledovat výkon webu a chování uživatelů.';
$_['help_functionality_storage']            = 'Umožňuje ukládání dat pro podporu funkcionality, jako jsou uživatelské preference nebo funkce webu, které zlepšují uživatelský zážitek.';
$_['help_personalization_storage']          = 'Ovládá ukládání dat pro personalizaci uživatelského zážitku, jako jsou doporučený obsah nebo nastavení.';
$_['help_security_storage']                 = 'Zajišťuje ukládání bezpečnostních dat, například pro prevenci podvodů a bezpečnou kontrolu přístupu.';
$_['help_wait_for_update']                  = 'Nastavuje čas (v milisekundách), který se má čekat před aktualizací stavu souhlasu, aby byla zajištěna aplikace všech nastavení.';
$_['help_ads_data_redaction']               = 'Rediguje uživatelská data související s reklamami, čímž zajišťuje ochranu soukromí skrytím některých identifikovatelných informací.';
$_['help_url_passthrough']                  = 'Umožňuje, aby URL prošla kontrolami souhlasu, což je užitečné pro sledování konkrétních uživatelských cest bez ukládání osobních údajů.';
$_['help_gcm_status']                       = 'Povoluje režim souhlasu Google, což umožňuje vaší stránce přizpůsobit chování Google značek na základě nastavení souhlasu uživatele. Tento režim poskytuje sledování šetrné k soukromí, což umožňuje, aby analytika a reklamy fungovaly v souladu se souhlasnými preferencemi.';

// Error
$_['error_permission']                      = 'Varování: Nemáte oprávnění upravit nastavení (GA4) vylepšeného měření!';
$_['error_refund_send']                     = 'Varování: Nepodařilo se odeslat data o vrácení peněz do Google Analytics (GA4). Zkontrolujte svá nastavení a zkuste to znovu.';
$_['error_google_tag_id']                   = 'Pole Google Tag ID je povinné. Zadejte své Google Analytics ID.';
$_['error_google_tag_id_invalid']           = 'Formát Google Tag ID je neplatný. Ujistěte se, že následuje formát G-XXXXXXXXXX.';
$_['error_gtm_id']                          = 'Pole GTM ID je povinné. Zadejte své měřicí ID.';
$_['error_gtm_id_invalid']                  = 'Formát GTM ID je neplatný. Ujistěte se, že následuje formát GTM-XXXXXXXX.';
$_['error_mp_api_secret']                   = 'Pole tajného klíče API Measurement Protocol je povinné. Zadejte svůj tajný klíč API Measurement Protocol.';
$_['error_mp_api_secret_invalid']           = 'Formát tajného klíče API Measurement Protocol je neplatný. Ujistěte se, že následuje formát XXXXXXX-XXXXXXX-XXXXXX.';
$_['error_measurement_implementation']      = 'Implementace měření není nakonfigurována. Vyberte buď Global Site Tag nebo Google Tag Manager.';
$_['error_client_id']                       = 'Varování: ID klienta nebylo uloženo během pokladny.';
$_['error_order_product_id']                = 'Varování: Produkt ID spojené s touto objednávkou nebylo nalezeno.';
$_['error_request_parameters']              = 'Varování: Chybí nebo jsou neúplné požadované parametry žádosti.';
$_['error_analytics_extension']             = 'Vypadá to, že na vašem webu je již aktivní jiný analytický nástroj. Mít více než jeden nástroj tohoto typu může způsobit problémy, jako jsou duplicitní nebo chybějící sledování. Zkontrolujte nastavení svého webu.';
$_['error_tracking_delay']                  = 'Zpoždění sledování musí být alespoň 100 milisekund, aby bylo zajištěno správné sledování událostí.';
$_['error_wait_for_update']                 = 'Hodnota pro Počkejte na aktualizaci musí být číslo mezi 0 a 10000 milisekundami.';
