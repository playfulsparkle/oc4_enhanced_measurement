<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Gyakori hibák javítása';
$_['heading_getting_started']               = 'Első lépések';
$_['heading_setup']                         = '(GA4) Enhanced Measurement beállítása';
$_['heading_troubleshoot']                  = 'Gyakori hibaelhárítás';
$_['heading_faq']                           = 'GYIK';
$_['heading_contact']                       = 'Támogatás felkeresése';

// Text
$_['text_extension']                        = 'Bővítmények';
$_['text_edit']                             = '(GA4) Enhanced Measurement szerkesztése';
$_['text_success']                          = 'Sikeres módosítás: (GA4) Enhanced Measurement frissítve!';
$_['text_getting_started']                  = '<p><strong>Áttekintés:</strong> A Playful Sparkle - GA4 Enhanced Measurement bővítmény fejlett követési funkciókat kínál OpenCart 4 webáruházak számára. Többféle eseménykövetést támogat, beleértve a felhasználói interakciókat, kosárműveleteket és vásárlási eseményeket. Emellett lehetővé teszi a Google Tag Manager vagy a Global Site Tag integrációját, rugalmas méréstechnikai megoldásokat nyújtva.</p><p><strong>Követelmények:</strong> OpenCart 4.x, érvényes Google Analytics GA4-fiók, és a választott mérési implementációhoz tartozó hitelesítő adatok: a Global Site Tag (gtag.js) esetén Google Tag ID és Measurement Protocol API Secret szükséges, míg Google Tag Manager (GTM) választásakor Measurement ID szükséges. Győződjön meg arról, hogy más analitikai bővítmények nincsenek engedélyezve, hogy elkerülje a kódütközéseket.</p>';
$_['text_setup']                            = '<ul><li>Válassza ki a kívánt mérési implementációt (Global Site Tag vagy Google Tag Manager).</li><li>Ha Global Site Tag-et használ, adja meg a Google Tag ID-t és a Measurement Protocol API Secret-et. Google Tag Manager esetén adja meg a Measurement ID-t.</li><li>Állítsa be a követni kívánt eseményeket, például bejelentkezés, vásárlás vagy kosárhoz adás követése.</li><li>Ellenőrizze, hogy más bővítmények, amelyek követési kódokat injektálnak (pl. Tag Manager vagy Global Site Tag), nincsenek-e aktív állapotban, hogy elkerülje az ütközéseket.</li><li>Mentse a beállításokat, és tesztelje az implementációt a Google Analytics hibakereső eszközök segítségével.</li></ul>';
$_['text_troubleshoot']                     = '<ul><li><strong>Probléma:</strong> Az események nem láthatók a Google Analytics irányítópultján. <strong>Megoldás:</strong> Ellenőrizze, hogy a Measurement ID vagy Tag ID helyesen van-e megadva, és egyezik-e a GA4 tulajdonával. Ha Global Site Tag-et (gtag.js) használ, győződjön meg arról, hogy a Google Tag ID és a Measurement Protocol API Secret megfelelően van konfigurálva.</li><li><strong>Probléma:</strong> Duplikált események kerülnek követésre. <strong>Megoldás:</strong> Ellenőrizze, hogy más analitikai bővítmények nem injektálnak-e követési kódokat, és tiltsa le őket, ha szükséges. Ellenőrizze továbbá, hogy ugyanaz az esemény nem kerül-e több implementáción keresztül követésre (pl. mind GTM, mind gtag.js).</li><li><strong>Probléma:</strong> A követés nem működik több áruházban. <strong>Megoldás:</strong> Győződjön meg arról, hogy a helyes Tag ID vagy Measurement ID minden áruházhoz be van állítva. GTM esetén győződjön meg arról, hogy minden áruháznál a megfelelő konténer be van állítva a Google Tag Managerben.</li><li><strong>Probléma:</strong> A visszatérítési adatok nem jelennek meg a Google Analytics-ben. <strong>Megoldás:</strong> Hagyjon időt arra, hogy a visszatérítési adatok megjelenjenek a Google Analytics-ben, és győződjön meg arról, hogy a visszatérítés helyesen van konfigurálva részleges vagy teljes visszatérítésként, mivel rendelésenként csak egy beküldés fogadható el.</li></ul>';
$_['text_faq']                              = '<details><summary>Miért jelenik meg a Google Consent Mode (GCM), amikor Global Site Tag-et választok?</summary>A Global Site Tag (gtag.js) nem támogatja és nem igényli a GCM-et.</details><details><summary>Miért nincs hibakeresési mód opció a Google Tag Managerhez?</summary>A hibakeresési módot közvetlenül a Google Tag Managerben kell beállítani.</details><details><summary>Mi történik, ha olyan termékazonosítót választok, amely nem érhető el?</summary>A product_id lesz használva helyette.</details><details><summary>Mi történik, ha nem töltöm ki az Affiliation mezőt?</summary>A bolt neve lesz használva helyette.</details><details><summary>Késleltethetem az események küldését a Google Analytics-be?</summary>Igen, ellenőrizze a Követési események lapot és a Követési késleltetés mezőt.</details><details><summary>Miért nem jelennek meg a visszatérítési adatok a Google Analytics-ben?</summary>A visszatérítési adatok megjelenése időbe telhet a Google Analytics-ben.</details><details><summary>Miért nem lehet visszatérítést többször elküldeni?</summary>A Google Analytics csak egy visszatérítési beküldést fogad rendelésenként. Lehetőség van részleges vagy teljes visszatérítésre.</details><details><summary>Milyen eseményeket támogat a bővítmény?</summary>A támogatott események a következők: add_payment_info, add_shipping_info, add_to_cart, add_to_wishlist, begin_checkout, generate_lead, login, purchase, refund, remove_from_cart, search, select_item, select_promotion, sign_up, view_cart, view_item, view_item_list, view_promotion.</details><details><summary>Hogyan működik a kosárhoz adás esemény?</summary>A kosárhoz adás esemény csak akkor aktiválódik, ha a felhasználó ténylegesen hozzáad egy terméket a kosárhoz. Máskülönben a select_item vagy select_promotion esemény aktiválódik, attól függően, hogy speciális termékről van-e szó.</details>';
$_['text_contact']                          = '<p>További segítségért kérjük, vegye fel a kapcsolatot támogatói csapatunkkal:</p><ul><li><strong>Kapcsolat:</strong> <a href="mailto:%s">%s</a></li><li><strong>Dokumentáció:</strong> <a href="%s" target="_blank" rel="noopener noreferrer">Felhasználói dokumentáció</a></li></ul>';
$_['text_gtag']                             = 'Global Site Tag - gtag.js';
$_['text_gtm']                              = 'Google Tag Manager';
$_['text_item_options_group']               = 'Termékopciók csoportja';
$_['text_store_options_group']              = 'Áruházbeállítások csoportja';
$_['text_product_id']                       = 'Termékazonosító';
$_['text_model']                            = 'Modell';
$_['text_sku']                              = 'SKU';
$_['text_upc']                              = 'UPC';
$_['text_ean']                              = 'EAN';
$_['text_jan']                              = 'JAN';
$_['text_isbn']                             = 'ISBN';
$_['text_mpn']                              = 'MPN';
$_['text_default']                          = '(alapértelmezett)';
$_['text_category_option_type_1']           = 'Az összes kategória utolsó szegmense, amely a termékhez tartozik';
$_['text_category_option_type_2']           = 'Minden kategória, amely a termékhez tartozik, a kategórianevek "&gt;" szimbólummal elválasztva';
$_['text_category_option_type_3']           = 'Az aktuális kategóriák nevei, amelyek a termékhez tartoznak';
$_['text_category_option_type_4']           = 'Az aktuális kategória nevének utolsó szegmense, amely a termékhez tartozik';
$_['text_multi_currency']                   = 'Több pénznem';
$_['text_refund_quantity']                  = 'Mennyiség';
$_['text_refund_successfully_sent']         = 'Siker: A visszatérítési adatok sikeresen elküldve a Google Analyticshez.';
$_['text_group_ad_settings']                = 'Hirdetés beállításai';
$_['text_group_analytics_settings']         = 'Analitika beállításai';
$_['text_group_security_settings']          = 'Biztonsági beállítások';
$_['text_group_advanced_settings']          = 'Speciális beállítások';
$_['text_gcm_info']                         = 'A Google Consent Mode (GCM) csak akkor működik, ha a Mérési implementáció legördülő menüben a Google Tag Manager van kiválasztva. Nem működik a Global Site Tag - gtag.js használatakor. A funkció használatához szükséges egy süti-értesítési sáv telepítése. Ez a bővítmény alapértelmezés szerint egy alapvető hozzájárulási állapotot állít be, de a süti-értesítési sáv felelős a hozzájárulási állapot frissítéséért az adatok gyűjtésének engedélyezéséhez.';

// Column
$_['column_refund_quantity']                = 'Visszatérített mennyiség';

// Tab
$_['tab_general']                           = 'Általános';
$_['tab_gcm']                               = 'Google Consent Mode (GCM)';
$_['tab_track_events']                      = 'Események követése';
$_['tab_help_and_support']                  = 'Segítség és támogatás';
$_['tab_gtag']                              = 'Global Site Tag - gtag.js';
$_['tab_gtm']                               = 'Google Tag Manager (GTM)';

// Entry
$_['entry_status']                          = 'Állapot';
$_['entry_measurement_implementation']      = 'Mérés megvalósítása';
$_['entry_google_tag_id']                   = 'Google Tag ID';
$_['entry_gtm_id']                          = 'Measurement ID';
$_['entry_measurement_protocol_api_secret'] = 'Measurement Protocol API Secret';
$_['entry_item_id']                         = 'Tétel azonosító';
$_['entry_item_category_option']            = 'Tételkategória';
$_['entry_tracking_delay']                  = 'Követési késleltetés';
$_['entry_affiliation']                     = 'Partnerkapcsolat';
$_['entry_location_id']                     = 'Helyazonosító';
$_['entry_item_price_tax']                  = 'Árak megjelenítése adóval';
$_['entry_currency']                        = 'Pénznem';
$_['entry_debug_mode']                      = 'Hibakeresési mód';
$_['entry_gtag_debug_mode']                 = 'Global Site Tag hibakeresése';
$_['entry_generate_lead']                   = '„Lead generálás” esemény követése';
$_['entry_sign_up']                         = '„Regisztráció” esemény követése';
$_['entry_login']                           = '„Bejelentkezés” esemény követése';
$_['entry_add_to_wishlist']                 = '„Kívánságlistához adás” esemény követése';
$_['entry_add_to_cart']                     = '„Kosárba helyezés” esemény követése';
$_['entry_remove_from_cart']                = '„Kosárból eltávolítás” esemény követése';
$_['entry_search']                          = '„Keresés” esemény követése';
$_['entry_view_item_list']                  = '„Elemlista megtekintése” esemény követése';
$_['entry_select_item']                     = '„Elem kiválasztása” esemény követése';
$_['entry_view_item']                       = '„Elem megtekintése” esemény követése';
$_['entry_select_promotion']                = '„Promóció kiválasztása” esemény követése';
$_['entry_view_promotion']                  = '„Promóció megtekintése” esemény követése';
$_['entry_view_cart']                       = '„Kosár megtekintése” esemény követése';
$_['entry_begin_checkout']                  = '„Pénztár indítása” esemény követése';
$_['entry_add_payment_info']                = '„Fizetési adatok hozzáadása” esemény követése';
$_['entry_add_shipping_info']               = '„Szállítási adatok hozzáadása” esemény követése';
$_['entry_purchase']                        = '„Vásárlás” esemény követése';
$_['entry_user_id']                         = 'Felhasználói azonosító küldése';
$_['entry_gcm_status']                      = 'GCM engedélyezése';
$_['entry_ad_storage']                      = 'Hirdetés tárolása';
$_['entry_ad_user_data']                    = 'Hirdetési felhasználói adatok';
$_['entry_ad_personalization']              = 'Hirdetési személyre szabás';
$_['entry_analytics_storage']               = 'Analitikai adatok tárolása';
$_['entry_functionality_storage']           = 'Funkcionalitási adatok tárolása';
$_['entry_personalization_storage']         = 'Személyre szabási adatok tárolása';
$_['entry_security_storage']                = 'Biztonsági adatok tárolása';
$_['entry_wait_for_update']                 = 'Várakozás frissítésre';
$_['entry_ads_data_redaction']              = 'Hirdetési adatok csökkentése';
$_['entry_url_passthrough']                 = 'URL továbbítás';
$_['entry_strict']                          = 'Szigorú';
$_['entry_balanced']                        = 'Kiegyensúlyozott';
$_['entry_custom']                          = 'Egyéni';
$_['entry_gcm_profiles']                    = 'GCM profilok';

// Button
$_['button_fix_event_handler']              = 'Eseménykezelő javítása';
$_['button_refund']                         = 'Visszatérítés';
$_['button_refund_all']                     = 'Összes visszatérítése';

// Help
$_['help_google_tag_id_locate']             = 'A Google Tag ID azonosítót a <a href="https://analytics.google.com" target="_blank" rel="external noopener noreferrer">Analytics-fiókjába</a> való bejelentkezéssel találhatja meg. Lépjen az Admin szekcióba, válassza ki a nyomon követni kívánt tulajdont, és keresse meg a Google Tag ID-t. Az azonosító "G-" betűvel kezdődik, majd betűk és számok egyedi kombinációja követi, például "G-XXXXXXXXXX." <a href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank" rel="external noopener noreferrer">Részletes útmutató itt</a>.';
$_['help_gtm_id_locate']                    = 'A Measurement ID azonosítót a <a href="https://tagmanager.google.com" target="_blank" rel="external noopener noreferrer">Google Tag Manager fiókjában</a> találhatja meg a munkaterület irányítópultjának tetején. Az azonosító "GTM-" betűkkel kezdődik, amelyet betűk és számok egyedi kombinációja követ, például "GTM-XXXXXXXX". <a href="https://support.google.com/analytics/answer/12270356?hl=en" target="_blank" rel="external noopener noreferrer">Részletes útmutató itt</a>.';
$_['help_mp_api_secret_locate']             = 'A Measurement Protocol API Secret azonosítót a <a href="https://analytics.google.com/" target="_blank" rel="external noopener noreferrer">Google Analytics-fiókjában</a> találhatja meg. Az Admin menüpontra kattintva, a Tulajdon beállításai között válassza az Adatfolyamokat. Válassza ki az adatfolyamát, majd görgessen le a Measurement Protocol API secrets szekcióhoz. Itt új API Secret-t hozhat létre, vagy meglévőket találhat. Az API Secret egy egyedi karakterlánc, például XXXXXXX-XXXXXXX-XXXXXX, amelyet szerveroldali kérések hitelesítésére használnak.';
$_['help_affiliation']                      = 'Adja meg az üzlet vagy részleg nevét az e-kereskedelmi nyomkövetés <strong>affiliation</strong> mezőjéhez. Ha üresen hagyja, az alapértelmezett üzletnév kerül felhasználásra a beállításokból.';
$_['help_location_id']                      = 'A termék fizikai helye, például az üzlet, ahol értékesítik. Javasolt a <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="external noopener noreferrer">Google Place ID</a> használata, de egyéni helyazonosítót is megadhat.';
$_['help_tracking_delay']                   = 'Adja meg a késleltetés időtartamát (ezredmásodpercben) a GA4 esemény elküldése előtt végrehajtott alapértelmezett művelet (pl. linkre navigálás vagy űrlap beküldése) előtt. Ez biztosítja, hogy az esemény megfelelően legyen rögzítve, mielőtt a művelet befejeződik. Ha üresen hagyja, az alapértelmezett érték kerül alkalmazásra.';
$_['help_generate_lead']                    = 'Ez az esemény azt méri, amikor egy lead létrejött, például hírlevél-feliratkozás vagy kapcsolati űrlap beküldése során. Használja ezt az eseményt, hogy megértse a marketingkampányok hatékonyságát és azt, hány ügyfél tér vissza az üzletéhez remarketing után.';
$_['help_sign_up']                          = 'Ez az esemény azt jelzi, hogy egy felhasználó regisztrált egy fiókot. Használja ezt az eseményt a bejelentkezett és kijelentkezett felhasználók különböző viselkedésének megértésére.';
$_['help_login']                            = 'Ezt az eseményt küldje el, hogy jelezze, amikor egy felhasználó bejelentkezett a webhelyére vagy alkalmazásába.';
$_['help_add_to_wishlist']                  = 'Ez az esemény azt jelzi, hogy egy tételt hozzáadtak a kívánságlistához. Használja ezt, hogy azonosítsa a népszerű ajándéktételeket az alkalmazásában.';
$_['help_add_to_cart']                      = 'Ez az esemény azt jelzi, hogy egy tételt a kosárhoz adtak vásárlás céljából.';
$_['help_remove_from_cart']                 = 'Ez az esemény azt jelzi, hogy egy tételt eltávolítottak a kosárból.';
$_['help_search']                           = 'Jelentse ezt az eseményt, amikor a felhasználó keresést végez. Használja ezt az eseményt annak megállapítására, hogy mit keresnek a felhasználók a webhelyén vagy alkalmazásában. Például küldje el ezt az eseményt, amikor a felhasználó megtekint egy keresési eredmények oldalt a keresés után.';
$_['help_view_item_list']                   = 'Jelentse ezt az eseményt, amikor egy adott kategóriából származó elemek listáját mutatják be a felhasználónak.';
$_['help_select_item']                      = 'Ez az esemény azt jelzi, hogy egy elemet választottak ki egy listából.';
$_['help_view_item']                        = 'Ez az esemény azt jelzi, hogy egy tartalom megjelent a felhasználónak. Használja ezt, hogy felfedezze a legnépszerűbb megtekintett tételeket.';
$_['help_select_promotion']                 = 'Ez az esemény azt jelzi, hogy egy promóciót választottak ki egy listából.';
$_['help_view_promotion']                   = 'Ez az esemény azt jelzi, hogy egy promóciót megtekintettek egy listából.';
$_['help_view_cart']                        = 'Ez az esemény azt jelzi, hogy a felhasználó megtekintette a kosarát.';
$_['help_begin_checkout']                   = 'Ez az esemény azt jelzi, hogy a felhasználó megkezdte a fizetési folyamatot.';
$_['help_add_payment_info']                 = 'Ez az esemény azt jelzi, hogy a felhasználó megadta a fizetési adatait az e-kereskedelmi fizetési folyamat során.';
$_['help_add_shipping_info']                = 'Ez az esemény azt jelzi, hogy a felhasználó megadta a szállítási adatait az e-kereskedelmi fizetési folyamat során.';
$_['help_purchase']                         = 'Ez az esemény azt jelzi, hogy egy vagy több tételt megvásárolt egy felhasználó.';
$_['help_user_id']                          = 'Ez az opció engedélyezi a bejelentkezett felhasználói azonosítók követését, lehetővé téve a felhasználói viselkedés jobb megértését munkamenetek és eszközök között, pontosabb és részletesebb elemzéseket nyújtva.';
$_['help_ad_storage']                       = 'Szabályozza, hogy az adatokat reklámokkal kapcsolatos célokra, például kattintások vagy konverziók nyomon követésére tárolják-e.';
$_['help_ad_user_data']                     = 'Meghatározza, hogy tárolják-e az adatokat a reklámokkal interakcióban álló felhasználókról, növelve a reklámcélzási lehetőségeket.';
$_['help_ad_personalization']               = 'Lehetővé teszi a hirdetések személyre szabását a felhasználói adatok alapján, relevánsabb reklámokat biztosítva.';
$_['help_analytics_storage']                = 'Engedélyezi az analitikai célokra használt adatok tárolását, segítve a webhely teljesítményének és a felhasználói viselkedés nyomon követését.';
$_['help_functionality_storage']            = 'Lehetővé teszi az adatok tárolását a funkciók támogatása érdekében, például a felhasználói preferenciák vagy a webhely funkcióinak javítása céljából.';
$_['help_personalization_storage']          = 'Szabályozza az adatok tárolását a felhasználói élmény személyre szabása érdekében, például ajánlott tartalmak vagy beállítások.';
$_['help_security_storage']                 = 'Biztosítja a biztonsági célú adatok, például csalás megelőzési és támadás felderítési adatok tárolását.';
$_['help_wait_for_update']                  = 'Beállítja az időt (ezredmásodpercben), amely késlelteti a hozzájárulási állapot frissítését annak érdekében, hogy minden beállítás érvényesüljön.';
$_['help_ads_data_redaction']               = 'Azonosítható információk elrejtésével biztosítja a hirdetési adatok védelmét és a felhasználói adatok titkosságát.';
$_['help_url_passthrough']                  = 'Lehetővé teszi az URL átengedését a hozzájárulás-ellenőrzéseken, hasznos a felhasználói útvonalak követéséhez személyes adatok tárolása nélkül.';
$_['help_gcm_status']                       = 'Engedélyezi a Google Consent Mode használatát, amely lehetővé teszi a webhely számára, hogy a Google címkék viselkedését a felhasználói hozzájárulási beállításokhoz igazítsa. Ez a mód adatvédelmi szempontból barátságos nyomkövetést biztosít, amely megfelel a hozzájárulási preferenciáknak, miközben lehetővé teszi az analitikát és a hirdetések működését.';

// Error
$_['error_permission']                      = 'Figyelem: Nincs jogosultsága a (GA4) Enhanced Measurement beállításainak módosításához!';
$_['error_refund_send']                     = 'Figyelem: Nem sikerült elküldeni a visszatérítési adatokat a Google Analytics (GA4) számára. Kérjük, ellenőrizze a beállításokat, és próbálja újra.';
$_['error_google_tag_id']                   = 'A Google Tag ID mező kitöltése kötelező. Kérjük, adja meg Google Analytics azonosítóját.';
$_['error_google_tag_id_invalid']           = 'A Google Tag ID formátuma helytelen. Győződjön meg róla, hogy a formátum megfelel: G-XXXXXXXXXX.';
$_['error_gtm_id']                          = 'A GTM ID mező kitöltése kötelező. Kérjük, adja meg Measurement ID-ját.';
$_['error_gtm_id_invalid']                  = 'A GTM ID formátuma helytelen. Győződjön meg róla, hogy a formátum megfelel: GTM-XXXXXXXX.';
$_['error_mp_api_secret']                   = 'A Measurement Protocol API secret mező kitöltése kötelező. Kérjük, adja meg Measurement Protocol API titkát.';
$_['error_mp_api_secret_invalid']           = 'A Measurement Protocol API secret formátuma helytelen. Győződjön meg róla, hogy a formátum megfelel: XXXXXXX-XXXXXXX-XXXXXX.';
$_['error_measurement_implementation']      = 'A mérési implementáció nincs beállítva. Kérjük, válasszon a Global Site Tag vagy a Google Tag Manager közül.';
$_['error_client_id']                       = 'Figyelem: A vásárlás során a Client ID nem került mentésre.';
$_['error_order_product_id']                = 'Figyelem: A megrendeléshez tartozó termékazonosító nem található.';
$_['error_request_parameters']              = 'Figyelem: Hiányoznak vagy hiányosak a szükséges kérési paraméterek.';
$_['error_analytics_extension']             = 'Úgy tűnik, egy másik analitikai eszköz már aktív az oldalán. Több ilyen eszköz használata problémákat okozhat, például duplikált vagy hiányzó nyomkövetést. Kérjük, ellenőrizze webhelye beállításait.';
$_['error_tracking_delay']                  = 'A nyomkövetési késleltetésnek legalább 100 ezredmásodpercnek kell lennie az események megfelelő nyomon követése érdekében.';
$_['error_wait_for_update']                 = 'A várakozás a frissítésre értékének 0 és 10000 ezredmásodperc közé kell esnie.';

