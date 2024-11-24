<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Opravenie bežných chýb';
$_['heading_getting_started']               = 'Začiatky';
$_['heading_setup']                         = 'Nastavenie (GA4) Enhanced Measurement';
$_['heading_troubleshoot']                  = 'Bežné problémy';
$_['heading_faq']                           = 'Často kladené otázky';
$_['heading_contact']                       = 'Kontaktujte podporu';

// Text
$_['text_extension']                        = 'Rozšírenie';
$_['text_edit']                             = 'Upravit (GA4) Enhanced Measurement';
$_['text_success']                          = 'Úspech: Úspešne ste upravili (GA4) Enhanced Measurement!';
$_['text_getting_started']                  = '<p><strong>Prehľad:</strong> Rozšírenie Playful Sparkle - GA4 Enhanced Measurement pre OpenCart 4 poskytuje pokročilé možnosti sledovania pre váš eCommerce obchod. Podporuje viacero možností sledovania udalostí, vrátane interakcií používateľov, aktivít v košíku a nákupných udalostí. Ďalej umožňuje integráciu s Google Tag Managerom alebo Global Site Tag, čo ponúka flexibilitu pri implementácii meracích riešení.</p><p><strong>Požiadavky:</strong> OpenCart 4.x, platný účet Google Analytics GA4 a príslušné prihlasovacie údaje podľa vybraného meracieho riešenia: Google Tag ID a Measurement Protocol API tajný kľúč sú požadované pri použití Global Site Tag (gtag.js), a Measurement ID je požadovaný, ak zvolíte Google Tag Manager (GTM). Zabezpečte, aby žiadne iné analytické rozšírenia neboli aktívne, aby sa predišlo konfliktom kódu.</p>';
$_['text_setup']                            = '<ul><li>Vyberte preferované meracie riešenie (Global Site Tag alebo Google Tag Manager).</li><li>Ak používate Global Site Tag, zadajte svoje Google Tag ID a Measurement Protocol API tajný kľúč. Pre Google Tag Manager zadajte svoje Measurement ID.</li><li>Konfigurujte udalosti sledovania, ktoré chcete povoliť, ako prihlásenie, nákup alebo pridanie do košíka.</li><li>Overte, že žiadne iné rozšírenia nezadávajú sledovacie kódy (napr. Tag Manager alebo Global Site Tag), aby sa predišlo konfliktom.</li><li>Uložte nastavenia a otestujte implementáciu pomocou nástrojov na ladenie Google Analytics.</li></ul>';
$_['text_troubleshoot']                     = '<ul><li><strong>Problém:</strong> Udalosti nie sú viditeľné na paneli Google Analytics. <strong>Riešenie:</strong> Uistite sa, že Measurement ID alebo Tag ID je správne zadané a zodpovedá vášmu účtu GA4. Ak používate Global Site Tag (gtag.js), uistite sa, že Google Tag ID a Measurement Protocol API tajný kľúč sú správne nakonfigurované.</li><li><strong>Problém:</strong> Duplikované udalosti sú sledované. <strong>Riešenie:</strong> Skontrolujte, či iné analytické rozšírenia nezadávajú sledovací kód a deaktivujte ich, ak je to potrebné. Tiež overte, či nie je tá istá udalosť sledovaná prostredníctvom viacerých implementácií (napr. GTM a gtag.js).</li><li><strong>Problém:</strong> Sledovanie nefunguje naprieč viacerými obchodmi. <strong>Riešenie:</strong> Uistite sa, že správne Tag ID alebo Measurement ID je nakonfigurované pre každý obchod. Pre GTM sa uistite, že pre každý obchod je v Google Tag Manageri nastavený správny kontajner.</li><li><strong>Problém:</strong> Dáta o refundáciách nie sú viditeľné v Google Analytics. <strong>Riešenie:</strong> Nechajte nejaký čas na zobrazenie dát o refundáciách v Google Analytics a uistite sa, že refundácia je správne nakonfigurovaná ako čiastočná alebo plná, pretože je akceptované iba jedno podanie refundácie na objednávku.</li></ul>';
$_['text_faq']                              = '<details><summary>Prečo je Google Consent Mode (GCM) viditeľný, keď vyberiem Global Site Tag?</summary>Global Site Tag (gtag.js) nepodporuje ani nevyžaduje GCM.</details><details><summary>Prečo nie je v Google Tag Manageri možnosť ladenia?</summary>Ladenie je potrebné nastaviť priamo v Google Tag Manageri.</details><details><summary>Čo sa stane, keď vyberiem iné ID položky, ktorá nie je k dispozícii?</summary>Produkt_id bude použitý namiesto toho.</details><details><summary>Čo sa stane, keď nevyplním Affiliation?</summary>Bude použitý názov obchodu.</details><details><summary>Môžu byť udalosti odosielané so oneskorením do Google Analytics?</summary>Ano, pozrite sa na kartu Sledovanie udalostí a pole Sledovanie oneskorenia.</details><details><summary>Prečo sa moje dáta o refundáciách nezobrazujú v Google Analytics?</summary>Dáta o refundáciách môžu v Google Analytics trvať dlhšie, než sa objavia.</details><details><summary>Prečo nemôžem refundovať viac ako raz?</summary>Google Analytics prijíma iba jedno podanie refundácie na objednávku. Môžete spracovať buď čiastočnú, alebo plnú refundáciu.</details><details><summary>Ako funguje udalosť pridania do košíka?</summary>Udalosť pridania do košíka je spustená iba vtedy, keď používateľ skutočne pridá produkt do košíka. Inak je spustená udalosť select_item alebo select_promotion na základe toho, či sa jedná o špeciálny produkt.</details>';
$_['text_contact']                          = '<p>Pre ďalšiu pomoc kontaktujte náš tím podpory:</p><ul><li><strong>Kontakt:</strong> <a href="mailto:%s">%s</a></li><li><strong>Dokumentácia:</strong> <a href="%s" target="_blank" rel="noopener noreferrer">Užívateľská dokumentácia</a></li></ul>';
$_['text_gtag']                             = 'Global Site Tag - gtag.js';
$_['text_gtm']                              = 'Google Tag Manager';
$_['text_item_options_group']               = 'Možnosti položiek';
$_['text_store_options_group']              = 'Možnosti obchodu';
$_['text_product_id']                       = 'ID produktu';
$_['text_model']                            = 'Model';
$_['text_sku']                              = 'SKU';
$_['text_upc']                              = 'UPC';
$_['text_ean']                              = 'EAN';
$_['text_jan']                              = 'JAN';
$_['text_isbn']                             = 'ISBN';
$_['text_mpn']                              = 'MPN';
$_['text_default']                          = '(predvolené)';
$_['text_category_option_type_1']           = 'Posledný segment všetkých kategórií spojených s produktom';
$_['text_category_option_type_2']           = 'Všetky kategórie, názvy kategórií oddelené symbolom "&gt;" spojené s produktom';
$_['text_category_option_type_3']           = 'Aktuálne názvy kategórií spojené s produktom';
$_['text_category_option_type_4']           = 'Posledný segment aktuálneho názvu kategórie spojené s produktom';
$_['text_multi_currency']                   = 'Viacero mien';
$_['text_refund_quantity']                  = 'Množstvo';
$_['text_refund_successfully_sent']         = 'Úspech: Dáta o refundáciách boli úspešne odoslané do Google Analytics.';
$_['text_group_ad_settings']                = 'Nastavenia reklám';
$_['text_group_analytics_settings']         = 'Nastavenia analytiky';
$_['text_group_security_settings']          = 'Nastavenia zabezpečenia';
$_['text_group_advanced_settings']          = 'Pokročilé nastavenia';
$_['text_gcm_info']                         = 'Google Consent Mode (GCM) funguje iba pri výbere Google Tag Manager (GTM) alebo Global Site Tag (gtag.js). Ak používate iný merací nástroj, GCM nebude podporovaný.';

// Column
$_['column_refund_quantity']                = 'Množstvo refundácie';

// Tab
$_['tab_general']                           = 'Všeobecné';
$_['tab_gcm']                               = 'Google Consent Mode (GCM)';
$_['tab_track_events']                      = 'Sledovanie udalostí';
$_['tab_help_and_support']                  = 'Nápoveda a podpora';
$_['tab_gtag']                              = 'Global Site Tag - gtag.js';
$_['tab_gtm']                               = 'Google Tag Manager (GTM)';

// Entry
$_['entry_status']                          = 'Stav';
$_['entry_measurement_implementation']      = 'Implementácia merania';
$_['entry_google_tag_id']                   = 'Google Tag ID';
$_['entry_gtm_id']                          = 'ID merania';
$_['entry_measurement_protocol_api_secret'] = 'API tajomstvo protokolu merania';
$_['entry_item_id']                         = 'ID položky';
$_['entry_item_category_option']            = 'Kategória položky';
$_['entry_tracking_delay']                  = 'Zpoždenie sledovania';
$_['entry_affiliation']                     = 'Afiliácia';
$_['entry_location_id']                     = 'ID miesta';
$_['entry_item_price_tax']                  = 'Zobraziť ceny s DPH';
$_['entry_currency']                        = 'Mena';
$_['entry_debug_mode']                      = 'Režim ladenia';
$_['entry_gtag_debug_mode']                 = 'Ladenie Global Site Tag';
$_['entry_generate_lead']                   = 'Sledovať udalosť Generovanie leadu';
$_['entry_sign_up']                         = 'Sledovať udalosť Registrácia';
$_['entry_login']                           = 'Sledovať udalosť Prihlásenie';
$_['entry_add_to_wishlist']                 = 'Sledovať udalosť Pridanie do zoznamu prianí';
$_['entry_add_to_cart']                     = 'Sledovať udalosť Pridanie do košíka';
$_['entry_remove_from_cart']                = 'Sledovať udalosť Odstránenie z košíka';
$_['entry_search']                          = 'Sledovať udalosť Vyhľadávanie';
$_['entry_view_item_list']                  = 'Sledovať udalosť Zobrazenie zoznamu položiek';
$_['entry_select_item']                     = 'Sledovať udalosť Výber položky';
$_['entry_view_item']                       = 'Sledovať udalosť Zobrazenie položky';
$_['entry_select_promotion']                = 'Sledovať udalosť Výber propagácie';
$_['entry_view_promotion']                  = 'Sledovať udalosť Zobrazenie propagácie';
$_['entry_view_cart']                       = 'Sledovať udalosť Zobrazenie košíka';
$_['entry_begin_checkout']                  = 'Sledovať udalosť Začiatok nákupu';
$_['entry_add_payment_info']                = 'Sledovať udalosť Pridanie informácií o platbe';
$_['entry_add_shipping_info']               = 'Sledovať udalosť Pridanie informácií o doručení';
$_['entry_purchase']                        = 'Sledovať udalosť Nákup';
$_['entry_user_id']                         = 'Odoslať ID používateľa';
$_['entry_gcm_status']                      = 'Povoliť GCM';
$_['entry_ad_storage']                      = 'Úložisko reklám';
$_['entry_ad_user_data']                    = 'Údaje o používateľských reklamách';
$_['entry_ad_personalization']              = 'Personalizácia reklám';
$_['entry_analytics_storage']               = 'Úložisko analytických dát';
$_['entry_functionality_storage']           = 'Úložisko funkčnosti';
$_['entry_personalization_storage']         = 'Úložisko personalizácie';
$_['entry_security_storage']                = 'Úložisko bezpečnosti';
$_['entry_wait_for_update']                 = 'Počkajte na aktualizáciu';
$_['entry_ads_data_redaction']              = 'Redakcia dát o reklamách';
$_['entry_url_passthrough']                 = 'URL Passthrough';
$_['entry_strict']                          = 'Prísny';
$_['entry_balanced']                        = 'Vyvážený';
$_['entry_custom']                          = 'Vlastný';
$_['entry_gcm_profiles']                    = 'GCM Profily';

// Button
$_['button_fix_event_handler']              = 'Opraviť obslužnú rutinu udalostí';
$_['button_refund']                         = 'Refundácia';
$_['button_refund_all']                     = 'Refundovať všetko';

// Help
$_['help_google_tag_id_locate']             = 'Ak chcete nájsť svoje Google Tag ID, prihláste sa do svojho <a href="https://analytics.google.com" target="_blank" rel="external noopener noreferrer">účtu Analytics</a>. Prejdite do sekcie Admin, vyberte vlastnosť, ktorú chcete sledovať, a nájdite svoje Google Tag ID. Začína písmenom "G-" nasledovaným jedinečnou kombináciou písmen a číslic, napr. "G-XXXXXXXXXX". <a href="https://support.google.com/analytics/answer/9539598?hl=sk" target="_blank" rel="external noopener noreferrer">Podrobnejší návod tu</a>.';
$_['help_gtm_id_locate']                    = 'Ak chcete nájsť svoje ID merania pre svoj <a href="https://tagmanager.google.com" target="_blank" rel="external noopener noreferrer">účet Google Tag Manager</a>, vyhľadajte ID v hornej časti panela pracovného priestoru – začína písmenami "GTM-" nasledovanými jedinečnou sériou písmen a číslic, napr. "GTM-XXXXXXXX". <a href="https://support.google.com/analytics/answer/12270356?hl=sk" target="_blank" rel="external noopener noreferrer">Podrobnejší návod tu</a>.';
$_['help_mp_api_secret_locate']             = 'Ak chcete nájsť svoje API tajomstvo protokolu merania, prejdite do svojho <a href="https://analytics.google.com/" target="_blank" rel="external noopener noreferrer">účtu Google Analytics</a>. Prejdite do sekcie Admin v ľavom menu, potom v nastavení vlastnosti vyberte Data Streams. Vyberte svoj dátový tok a zrolujte nadol na sekciu API tajomstvo protokolu merania. Tu môžete vytvoriť nové API tajomstvo alebo nájsť existujúce. API tajomstvo je jedinečný reťazec, napr. XXXXXXX-XXXXXXX-XXXXXX, slúžiaci na autentifikáciu požiadaviek na server.';
$_['help_affiliation']                      = 'Zadajte názov obchodu alebo oddelenia pre časť <strong>afiliácie</strong> sledovania e-commerce. Ak to necháte prázdne, automaticky sa použije predvolený názov obchodu z nastavení.';
$_['help_location_id']                      = 'Fyzická lokalita položky, napríklad obchod, kde je predávaná. Najlepšie je použiť <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="external noopener noreferrer">Google Place ID</a> pre túto lokalitu, ale môžete použiť aj vlastné ID miesta.';
$_['help_tracking_delay']                   = 'Zadajte oneskorenie (v milisekundách), ktoré má byť vyčkávané pred vykonaním predvolenej akcie (napr. prechod na odkaz alebo odoslanie formulára) po odoslaní udalosti GA4. Tým sa zabezpečí správne sledovanie udalosti pred dokončením akcie. Nechajte prázdne pre použitie predvolenej hodnoty.';
$_['help_generate_lead']                    = 'Táto udalosť meria, kedy bol vytvorený lead, konkrétne sleduje prihlásenie na odber newsletteru a odoslanie kontaktného formulára. Použite ju na pochopenie účinnosti vašich marketingových kampaní a ako sa mnoho zákazníkov opäť zapája s vašou firmou po remarketingu.';
$_['help_sign_up']                          = 'Táto udalosť označuje, že používateľ sa zaregistroval pre účet. Použite ju na pochopenie rôznych správaní prihlásených a neprihlásených používateľov.';
$_['help_login']                            = 'Odošlite túto udalosť na označenie, že používateľ sa prihlásil na vaše webové stránky alebo aplikáciu.';
$_['help_add_to_wishlist']                  = 'Táto udalosť označuje, že položka bola pridaná do zoznamu prianí. Použite ju na identifikáciu populárnych darčekových predmetov vo vašej aplikácii.';
$_['help_add_to_cart']                      = 'Táto udalosť označuje, že položka bola pridaná do nákupného košíka.';
$_['help_remove_from_cart']                 = 'Táto udalosť označuje, že položka bola odstránená z nákupného košíka.';
$_['help_search']                           = 'Zaznamenajte túto udalosť na označenie, že používateľ použil funkciu vyhľadávania.';
$_['help_view_item_list']                   = 'Zaznamenajte túto udalosť na označenie, že používateľ zobrazil zoznam položiek, ako je zoznam produktov alebo výsledky vyhľadávania.';
$_['help_select_item']                      = 'Táto udalosť označuje, že používateľ vybral konkrétnu položku zo zoznamu.';
$_['help_view_item']                        = 'Táto udalosť označuje, že používateľ zobrazil podrobnosti konkrétnej položky.';
$_['help_select_promotion']                 = 'Zaznamenajte túto udalosť, keď používateľ vyberie propagačnú ponuku, napríklad výpredaje.';
$_['help_view_promotion']                   = 'Táto udalosť označuje, že používateľ videl propagačnú ponuku, napríklad výpredaje.';
$_['help_view_cart']                        = 'Zaznamenajte túto udalosť, keď používateľ navštívi stránku košíka.';
$_['help_begin_checkout']                   = 'Táto udalosť označuje, že používateľ začal nákup.';
$_['help_add_payment_info']                 = 'Táto udalosť označuje, že používateľ pridal platobné informácie.';
$_['help_add_shipping_info']                = 'Táto udalosť označuje, že používateľ pridal informácie o doručení.';
$_['help_purchase']                         = 'Zaznamenajte túto udalosť, keď používateľ dokončí nákup.';
$_['help_user_id']                          = 'Táto možnosť umožňuje sledovanie ID prihlásených používateľov, čo vám umožní lepšie pochopiť správanie používateľov naprieč reláciami a zariadeniami, čo poskytuje presnejšiu a podrobnejšiu analytiku.';
$_['help_ad_storage']                       = 'Ovláda, či je povolené ukladať dáta na účely súvisiace s reklamami, ako je sledovanie kliknutí na reklamy alebo konverzie.';
$_['help_ad_user_data']                     = 'Určuje, či sú ukladané dáta o používateľoch, ktorí interagujú s reklamami, čo zlepšuje schopnosti cielenej reklamy.';
$_['help_ad_personalization']               = 'Umožňuje personalizáciu reklám na základe údajov o používateľoch, čo poskytuje relevantnejšie reklamy pre používateľov.';
$_['help_analytics_storage']                = 'Povoľuje ukladanie dát používaných na analytické účely, čo pomáha sledovať výkon webu a správanie používateľov.';
$_['help_functionality_storage']            = 'Umožňuje ukladanie dát na podporu funkcionality, ako sú užívateľské preferencie alebo funkcie webu, ktoré zlepšujú používateľský zážitok.';
$_['help_personalization_storage']          = 'Ovláda ukladanie dát na personalizáciu užívateľského zážitku, ako sú odporúčaný obsah alebo nastavenia.';
$_['help_security_storage']                 = 'Zabezpečuje ukladanie bezpečnostných dát, napríklad na prevenciu podvodov a bezpečnú kontrolu prístupu.';
$_['help_wait_for_update']                  = 'Nastavuje čas (v milisekundách), ktorý sa má čakať pred aktualizovaním stavu súhlasu, aby bola zabezpečená aplikácia všetkých nastavení.';
$_['help_ads_data_redaction']               = 'Rediguje užívateľské dáta súvisiace s reklamami, čím zaisťuje ochranu súkromia skrytím niektorých identifikovateľných informácií.';
$_['help_url_passthrough']                  = 'Umožňuje, aby URL prešla kontrolami súhlasu, čo je užitočné na sledovanie konkrétnych užívateľských ciest bez ukládania osobných údajov.';
$_['help_gcm_status']                       = 'Povoľuje režim súhlasu Google, čo umožňuje vašej stránke prispôsobiť správanie Google značiek na základe nastavení súhlasu používateľa. Tento režim poskytuje sledovanie šetrné k súkromiu, čo umožňuje, aby analytika a reklamy fungovali v súlade so súhlasnými preferenciami.';

// Error
$_['error_permission']                      = 'Upozornenie: Nemáte oprávnenie upravovať nastavenia (GA4) vylepšeného merania!';
$_['error_refund_send']                     = 'Upozornenie: Nepodarilo sa odoslať údaje o vrátení peňazí do Google Analytics (GA4). Skontrolujte svoje nastavenia a skúste to znova.';
$_['error_google_tag_id']                   = 'Pole Google Tag ID je povinné. Zadajte svoje Google Analytics ID.';
$_['error_google_tag_id_invalid']           = 'Formát Google Tag ID je neplatný. Uistite sa, že nasleduje formát G-XXXXXXXXXX.';
$_['error_gtm_id']                          = 'Pole GTM ID je povinné. Zadajte svoje meracie ID.';
$_['error_gtm_id_invalid']                  = 'Formát GTM ID je neplatný. Uistite sa, že nasleduje formát GTM-XXXXXXXX.';
$_['error_mp_api_secret']                   = 'Pole tajného kľúča API Measurement Protocol je povinné. Zadajte svoj tajný kľúč API Measurement Protocol.';
$_['error_mp_api_secret_invalid']           = 'Formát tajného kľúča API Measurement Protocol je neplatný. Uistite sa, že nasleduje formát XXXXXXX-XXXXXXX-XXXXXX.';
$_['error_measurement_implementation']      = 'Implementácia merania nie je nakonfigurovaná. Vyberte buď Global Site Tag alebo Google Tag Manager.';
$_['error_client_id']                       = 'Upozornenie: ID klienta nebolo uložené počas pokladne.';
$_['error_order_product_id']                = 'Upozornenie: Produktové ID spojené s touto objednávkou nebolo nájdené.';
$_['error_request_parameters']              = 'Upozornenie: Chýbajú alebo sú neúplné požadované parametre žiadosti.';
$_['error_analytics_extension']             = 'Zdá sa, že na vašom webe je už aktívny iný analytický nástroj. Používanie viacerých nástrojov tohto typu môže spôsobiť problémy, ako sú duplicitné alebo chýbajúce sledovania. Skontrolujte nastavenia svojho webu.';
$_['error_tracking_delay']                  = 'Zdržanie sledovania musí byť najmenej 100 milisekúnd, aby bolo zabezpečené správne sledovanie udalostí.';
$_['error_wait_for_update']                 = 'Hodnota pre Počkať na aktualizáciu musí byť číslo medzi 0 a 10000 milisekundami.';
