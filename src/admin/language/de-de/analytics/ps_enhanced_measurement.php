<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Häufige Fehler beheben';
$_['heading_getting_started']               = 'Erste Schritte';
$_['heading_setup']                         = 'Einrichtung des (GA4) Enhanced Measurement';
$_['heading_troubleshot']                   = 'Häufige Fehlersuche';
$_['heading_faq']                           = 'Häufig gestellte Fragen';
$_['heading_contact']                       = 'Support kontaktieren';

// Text
$_['text_extension']                        = 'Erweiterungen';
$_['text_edit']                             = 'Bearbeiten der (GA4) Enhanced Measurement';
$_['text_success']                          = 'Erfolg: Sie haben die (GA4) Enhanced Measurement erfolgreich bearbeitet!';
$_['text_getting_started']                  = '<p><strong>Übersicht:</strong> Die Playful Sparkle - (GA4) Enhanced Measurement für OpenCart 4 Erweiterung bietet erweiterte Tracking-Funktionen für Ihren Online-Shop. Sie unterstützt verschiedene Event-Tracking-Optionen, einschließlich Benutzerinteraktionen, Warenkorbaktionen und Kaufereignissen. Außerdem ermöglicht sie die Integration mit dem Google Tag Manager oder dem Global Site Tag und bietet Flexibilität bei der Implementierung von Messlösungen.</p><p><strong>Voraussetzungen:</strong> OpenCart 4.x, ein gültiges Google Analytics GA4-Konto und die entsprechenden Zugangsdaten basierend auf der ausgewählten Messimplementierung: Google Tag ID und Measurement Protocol API-Geheimnis sind erforderlich, wenn Sie das Global Site Tag (gtag.js) verwenden, und Measurement ID ist erforderlich, wenn Sie Google Tag Manager (GTM) auswählen. Stellen Sie sicher, dass keine anderen Analytics-Erweiterungen aktiviert sind, um Codekonflikte zu vermeiden.</p>';
$_['text_setup']                            = '<ul><li>Wählen Sie Ihre bevorzugte Messimplementierung (Global Site Tag oder Google Tag Manager) aus.</li><li>Geben Sie bei Verwendung des Global Site Tags Ihre Google Tag ID und Measurement Protocol API-Geheimnis ein. Für Google Tag Manager geben Sie Ihre Measurement ID ein.</li><li>Konfigurieren Sie die Tracking-Ereignisse, die Sie aktivieren möchten, z. B. Login, Kauf oder Hinzufügen zum Warenkorb.</li><li>Überprüfen Sie, dass keine anderen Erweiterungen, die Tracking-Codes einfügen (z. B. Tag Manager oder Global Site Tag), aktiv sind, um Konflikte zu vermeiden.</li><li>Speichern Sie die Einstellungen und testen Sie die Implementierung mit den Google Analytics-Debug-Tools.</li></ul>';
$_['text_troubleshoot']                     = '<ul><li><strong>Problem:</strong> Ereignisse sind nicht im Google Analytics Dashboard sichtbar. <strong> Lösung:</strong> Bestätigen Sie, dass die Measurement ID oder Tag ID korrekt eingegeben wurde und Ihrer GA4-Property entspricht. Wenn Sie das Global Site Tag (gtag.js) verwenden, stellen Sie sicher, dass die Google Tag ID und das Measurement Protocol API-Geheimnis korrekt konfiguriert sind.</li><li><strong>Problem:</strong> Doppelte Ereignisse werden verfolgt. <strong>Lösung:</strong> Überprüfen Sie, ob andere Analytics-Erweiterungen Tracking-Code einfügen und deaktivieren Sie diese gegebenenfalls. Stellen Sie auch sicher, dass dasselbe Ereignis nicht durch mehrere Implementierungen verfolgt wird (z. B. sowohl GTM als auch gtag.js).</li><li><strong>Problem:</strong> Das Tracking funktioniert nicht über mehrere Shops hinweg. <strong>Lösung:</strong> Stellen Sie sicher, dass für jeden Shop die richtige Tag ID oder Measurement ID konfiguriert ist. Bei GTM stellen Sie sicher, dass für jeden Shop der entsprechende Container im Google Tag Manager eingerichtet ist.</li><li><strong>Problem:</strong> Rückerstattungsdaten sind in Google Analytics nicht sichtbar. <strong>Lösung:</strong> Lassen Sie etwas Zeit für die Anzeige der Rückerstattungsdaten in Google Analytics und stellen Sie sicher, dass die Rückerstattung korrekt als teilweise oder vollständig konfiguriert ist, da nur eine Einreichung pro Bestellung akzeptiert wird.</li></ul>';
$_['text_faq']                              = '<details><summary>Warum ist der Google Consent Mode (GCM) sichtbar, wenn ich das Global Site Tag auswähle?</summary>Das Global Site Tag (gtag.js) unterstützt oder benötigt GCM nicht.</details><details><summary>Warum gibt es keine Debug-Modus-Option für Google Tag Manager?</summary>Der Debug-Modus muss direkt im Google Tag Manager eingerichtet werden.</details><details><summary>Was passiert, wenn ich eine andere Artikel-ID auswähle, die nicht verfügbar ist?</summary>Die Produkt-ID wird stattdessen verwendet.</details><details><summary>Was passiert, wenn ich die Affiliation nicht ausfülle?</summary>Der Shop-Name wird verwendet.</details><details><summary>Kann ich das Senden von Ereignissen an Google Analytics verzögern?</summary>Ja, überprüfen Sie die Registerkarte Tracking-Ereignisse und das Feld Tracking-Verzögerung.</details><details><summary>Warum erscheinen meine Rückerstattungsdaten nicht in Google Analytics?</summary>Rückerstattungsdaten können einige Zeit benötigen, um in Google Analytics angezeigt zu werden.</details><details><summary>Warum kann ich nicht mehr als einmal eine Rückerstattung vornehmen?</summary>Google Analytics akzeptiert nur eine Rückerstattungs-Einreichung pro Bestellung. Sie können entweder eine teilweise oder eine vollständige Rückerstattung bearbeiten.</details><details><summary>Welche Ereignisse werden unterstützt?</summary>Die unterstützten Ereignisse sind: add_payment_info, add_shipping_info, add_to_cart, add_to_wishlist, begin_checkout, generate_lead, login, purchase, refund, remove_from_cart, search, select_item, select_promotion, sign_up, view_cart, view_item, view_item_list, view_promotion.</details><details><summary>Wie funktioniert das Ereignis "Hinzufügen zum Warenkorb"?</summary>Das Ereignis "Hinzufügen zum Warenkorb" wird nur ausgelöst, wenn der Benutzer tatsächlich ein Produkt in den Warenkorb legt. Andernfalls wird das Ereignis "Artikel auswählen" oder "Werbung auswählen" ausgelöst, je nachdem, ob es sich um ein Sonderprodukt handelt oder nicht.</details>';
$_['text_contact']                          = '<p>Für weitere Unterstützung wenden Sie sich bitte an unser Support-Team:</p><ul><li><strong>Kontakt:</strong> <a href="mailto:%s">%s</a></li><li><strong>Dokumentation:</strong> <a href="%s" target="_blank" rel="noopener noreferrer">Benutzerdokumentation</a></li></ul>';
$_['text_gtag']                             = 'Global Site Tag - gtag.js';
$_['text_gtm']                              = 'Google Tag Manager';
$_['text_item_options_group']               = 'Artikeloptionen';
$_['text_store_options_group']              = 'Shop-Optionen';
$_['text_product_id']                       = 'Produkt-ID';
$_['text_model']                            = 'Modell';
$_['text_sku']                              = 'SKU';
$_['text_upc']                              = 'UPC';
$_['text_ean']                              = 'EAN';
$_['text_jan']                              = 'JAN';
$_['text_isbn']                             = 'ISBN';
$_['text_mpn']                              = 'MPN';
$_['text_default']                          = '(Standard)';
$_['text_category_option_type_1']           = 'Das letzte Segment aller mit dem Produkt verbundenen Kategorien';
$_['text_category_option_type_2']           = 'Alle Kategorien, Kategorienamen, die mit "&gt;" Symbol verbunden sind';
$_['text_category_option_type_3']           = 'Aktuelle Kategorienamen, die mit dem Produkt verbunden sind';
$_['text_category_option_type_4']           = 'Das letzte Segment des aktuellen Kategorie-Namens, das mit dem Produkt verbunden ist';
$_['text_multi_currency']                   = 'Mehrwährungsunterstützung';
$_['text_refund_quantity']                  = 'Menge';
$_['text_refund_successfully_sent']         = 'Erfolg: Rückerstattungsdaten wurden erfolgreich an Google Analytics gesendet.';
$_['text_group_ad_settings']                = 'Anzeige-Einstellungen';
$_['text_group_analytics_settings']         = 'Analytics-Einstellungen';
$_['text_group_security_settings']          = 'Sicherheitseinstellungen';
$_['text_group_advanced_settings']          = 'Erweiterte Einstellungen';
$_['text_product_already_refunded']         = 'Dieses Produkt wurde bereits erstattet. Weitere Aktionen sind nicht verfügbar.';
$_['text_gcm_info']                         = 'Der Google Consent Mode (GCM) funktioniert nur, wenn Sie den Google Tag Manager im Auswahlmenü für die Messimplementierung auswählen. Es funktioniert nicht mit dem Global Site Tag (gtag.js). Um diese Funktion zu nutzen, stellen Sie sicher, dass ein Cookie-Banner installiert ist. Diese Erweiterung setzt standardmäßig einen grundlegenden Zustimmungsstatus, aber das Cookie-Banner ist verantwortlich für die Aktualisierung der Zustimmung zur Datenerfassung.';

// Column
$_['column_refund_quantity']                = 'Rückerstattungsmenge';

// Tab
$_['tab_general']                           = 'Allgemein';
$_['tab_gcm']                               = 'Google Consent Mode (GCM)';
$_['tab_track_events']                      = 'Verfolgende Ereignisse';
$_['tab_help_and_support']                  = 'Hilfe &amp; Support';
$_['tab_gtag']                              = 'Global Site Tag - gtag.js';
$_['tab_gtm']                               = 'Google Tag Manager (GTM)';

// Entry
$_['entry_status']                          = 'Status';
$_['entry_measurement_implementation']      = 'Messimplementierung';
$_['entry_google_tag_id']                   = 'Google Tag ID';
$_['entry_gtm_id']                          = 'Mess-ID';
$_['entry_measurement_protocol_api_secret'] = 'Measurement Protocol API Secret';
$_['entry_item_id']                         = 'Artikel-ID';
$_['entry_item_category_option']            = 'Artikelkategorie';
$_['entry_tracking_delay']                  = 'Verzögerung der Verfolgung';
$_['entry_affiliation']                     = 'Zugehörigkeit';
$_['entry_location_id']                     = 'Standort-ID';
$_['entry_item_price_tax']                  = 'Preise mit Steuer anzeigen';
$_['entry_currency']                        = 'Währung';
$_['entry_debug_mode']                      = 'Debugging aktivieren';
$_['entry_gtag_debug_mode']                 = 'Debuggen für Global Site Tag';
$_['entry_generate_lead']                   = 'Verfolge Generierung von Leads';
$_['entry_sign_up']                         = 'Verfolge Registrierungsevent';
$_['entry_login']                           = 'Verfolge Login-Ereignis';
$_['entry_add_to_wishlist']                 = 'Verfolge Hinzufügen zur Wunschliste';
$_['entry_add_to_cart']                     = 'Verfolge Hinzufügen zum Warenkorb';
$_['entry_remove_from_cart']                = 'Verfolge Entfernen aus dem Warenkorb';
$_['entry_search']                          = 'Verfolge Suchanfrage';
$_['entry_view_item_list']                  = 'Verfolge Ansicht der Artikel-Liste';
$_['entry_select_item']                     = 'Verfolge Auswahl eines Artikels';
$_['entry_view_item']                       = 'Verfolge Ansicht eines Artikels';
$_['entry_select_promotion']                = 'Verfolge Auswahl einer Promotion';
$_['entry_view_promotion']                  = 'Verfolge Ansicht einer Promotion';
$_['entry_view_cart']                       = 'Verfolge Ansicht des Warenkorbs';
$_['entry_begin_checkout']                  = 'Verfolge Beginn des Checkouts';
$_['entry_add_payment_info']                = 'Verfolge Hinzufügen von Zahlungsinformationen';
$_['entry_add_shipping_info']               = 'Verfolge Hinzufügen von Versandinformationen';
$_['entry_purchase']                        = 'Verfolge Kauf-Ereignis';
$_['entry_user_id']                         = 'Benutzer-ID senden';
$_['entry_gcm_status']                      = 'GCM aktivieren';
$_['entry_ad_storage']                      = 'Werbespeicherung';
$_['entry_ad_user_data']                    = 'Werbebenutzerdaten';
$_['entry_ad_personalization']              = 'Werbepersonalisierung';
$_['entry_analytics_storage']               = 'Analysenspeicherung';
$_['entry_functionality_storage']           = 'Funktionalitätsspeicherung';
$_['entry_personalization_storage']         = 'Personalisierungsspeicherung';
$_['entry_security_storage']                = 'Sicherheitsspeicherung';
$_['entry_wait_for_update']                 = 'Auf Update warten';
$_['entry_ads_data_redaction']              = 'Werbedatenredaktion';
$_['entry_url_passthrough']                 = 'URL-Durchleitung';
$_['entry_strict']                          = 'Strikt';
$_['entry_balanced']                        = 'Ausgewogen';
$_['entry_custom']                          = 'Benutzerdefiniert';
$_['entry_gcm_profiles']                    = 'GCM-Profile';

// Button
$_['button_fix_event_handler']              = 'Ereignishandler beheben';
$_['button_refund']                         = 'Rückerstattung';
$_['button_refund_selected']                = 'Ausgewählte erstatten';
$_['button_refund_all']                     = 'Alle rückerstatten';

// Help
$_['help_google_tag_id_locate']             = 'Um Ihre Google Tag-ID zu finden, melden Sie sich bei Ihrem <a href="https://analytics.google.com" target="_blank" rel="external noopener noreferrer">Analytics-Konto</a> an. Gehen Sie zum Admin-Bereich, wählen Sie die zu verfolgende Property aus und suchen Sie nach Ihrer Google Tag-ID. Diese beginnt mit "G-" gefolgt von einer einzigartigen Kombination aus Buchstaben und Zahlen, z. B. "G-XXXXXXXXXX". <a href="https://support.google.com/analytics/answer/9539598?hl=de" target="_blank" rel="external noopener noreferrer">Detaillierte Anweisungen hier</a>.';
$_['help_gtm_id_locate']                    = 'Um Ihre Mess-ID für Ihr <a href="https://tagmanager.google.com" target="_blank" rel="external noopener noreferrer">Google Tag Manager-Konto</a> zu finden, suchen Sie nach der ID oben im Dashboard des Arbeitsbereichs. Sie beginnt mit "GTM-" gefolgt von einer einzigartigen Reihe von Buchstaben und Zahlen, wie "GTM-XXXXXXXX". <a href="https://support.google.com/analytics/answer/12270356?hl=de" target="_blank" rel="external noopener noreferrer">Detaillierte Anweisungen hier</a>.';
$_['help_mp_api_secret_locate']             = 'Um Ihr Measurement Protocol API-Geheimnis zu finden, gehen Sie zu Ihrem <a href="https://analytics.google.com/" target="_blank" rel="external noopener noreferrer">Google Analytics-Konto</a>. Navigieren Sie im linken Menü zu "Admin" und wählen Sie unter den Property-Einstellungen "Datenströme" aus. Wählen Sie Ihren Datenstrom aus und scrollen Sie nach unten zum Abschnitt "Measurement Protocol API-Geheimnisse". Hier können Sie ein neues API-Geheimnis erstellen oder Ihre vorhandenen finden. Das API-Geheimnis ist eine einzigartige Zeichenfolge, z. B. XXXXXXX-XXXXXXX-XXXXXX, die zur Authentifizierung serverseitiger Anfragen verwendet wird.';
$_['help_affiliation']                      = 'Geben Sie den Namen des Geschäfts oder der Abteilung für den <strong>Affiliation</strong>-Teil des eCommerce-Trackings ein. Wenn Sie dies leer lassen, wird automatisch der Standardgeschäftsname aus den Einstellungen verwendet.';
$_['help_location_id']                      = 'Der physische Standort des Artikels, z. B. das Geschäft, in dem er verkauft wird. Es ist am besten, die <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="external noopener noreferrer">Google Place-ID</a> für diesen Standort zu verwenden, aber Sie können auch eine benutzerdefinierte Standort-ID verwenden.';
$_['help_tracking_delay']                   = 'Geben Sie die Verzögerung (in Millisekunden) an, die gewartet werden soll, bevor die Standardaktion ausgeführt wird (z. B. das Navigieren zu einem Link oder das Absenden eines Formulars), nachdem das GA4-Ereignis gesendet wurde. Dadurch wird sichergestellt, dass das Ereignis ordnungsgemäß erfasst wird, bevor die Aktion abgeschlossen ist. Lassen Sie es leer, um den Standardwert zu verwenden.';
$_['help_generate_lead']                    = 'Dieses Ereignis misst, wann ein Lead generiert wurde, insbesondere das Verfolgen von Newsletter-Abonnements und Formularübermittlungen. Verwenden Sie dies, um die Effektivität Ihrer Marketingkampagnen zu verstehen und wie viele Kunden nach Remarketing erneut mit Ihrem Unternehmen interagieren.';
$_['help_sign_up']                          = 'Dieses Ereignis zeigt an, dass sich ein Benutzer für ein Konto angemeldet hat. Verwenden Sie dies, um das Verhalten von angemeldeten und nicht angemeldeten Benutzern zu verstehen.';
$_['help_login']                            = 'Senden Sie dieses Ereignis, um anzuzeigen, dass sich ein Benutzer auf Ihrer Website oder in Ihrer App angemeldet hat.';
$_['help_add_to_wishlist']                  = 'Dieses Ereignis zeigt an, dass ein Artikel zu einer Wunschliste hinzugefügt wurde. Verwenden Sie dies, um beliebte Geschenkartikel in Ihrer App zu identifizieren.';
$_['help_add_to_cart']                      = 'Dieses Ereignis zeigt an, dass ein Artikel zum Warenkorb hinzugefügt wurde.';
$_['help_remove_from_cart']                 = 'Dieses Ereignis zeigt an, dass ein Artikel aus dem Warenkorb entfernt wurde.';
$_['help_search']                           = 'Protokollieren Sie dieses Ereignis, um anzuzeigen, wann der Benutzer eine Suche durchgeführt hat. Verwenden Sie dies, um zu verstehen, wonach Benutzer auf Ihrer Website oder in Ihrer App suchen. Zum Beispiel, wenn der Benutzer nach der Durchführung einer Suche die Suchergebnisseite ansieht.';
$_['help_view_item_list']                   = 'Protokollieren Sie dieses Ereignis, wenn dem Benutzer eine Liste von Artikeln aus einer bestimmten Kategorie angezeigt wird.';
$_['help_select_item']                      = 'Dieses Ereignis zeigt an, dass ein Artikel aus einer Liste ausgewählt wurde.';
$_['help_view_item']                        = 'Dieses Ereignis zeigt an, dass ein Inhalt dem Benutzer angezeigt wurde. Verwenden Sie dies, um die beliebtesten angesehenen Artikel zu entdecken.';
$_['help_select_promotion']                 = 'Dieses Ereignis zeigt an, dass eine Promotion aus einer Liste ausgewählt wurde.';
$_['help_view_promotion']                   = 'Dieses Ereignis zeigt an, dass eine Promotion aus einer Liste angezeigt wurde.';
$_['help_view_cart']                        = 'Dieses Ereignis zeigt an, dass ein Benutzer seinen Warenkorb angesehen hat.';
$_['help_begin_checkout']                   = 'Dieses Ereignis zeigt an, dass ein Benutzer den Checkout-Prozess begonnen hat.';
$_['help_add_payment_info']                 = 'Dieses Ereignis zeigt an, dass ein Benutzer seine Zahlungsinformationen im Rahmen des eCommerce-Checkout-Prozesses übermittelt hat.';
$_['help_add_shipping_info']                = 'Dieses Ereignis zeigt an, dass ein Benutzer seine Versandinformationen im Rahmen des eCommerce-Checkout-Prozesses übermittelt hat.';
$_['help_purchase']                         = 'Dieses Ereignis zeigt an, wenn ein oder mehrere Artikel von einem Benutzer gekauft wurden.';
$_['help_user_id']                          = 'Diese Option ermöglicht das Tracking der Benutzer-IDs von angemeldeten Benutzern, sodass Sie das Verhalten der Benutzer über Sitzungen und Geräte hinweg besser verstehen und genauere sowie detailliertere Analysen erhalten.';
$_['help_ad_storage']                       = 'Steuert, ob die Datenspeicherung für werbezwecke, wie das Verfolgen von Klicks oder Konversionen, erlaubt ist.';
$_['help_ad_user_data']                     = 'Bestimmt, ob Daten über Benutzer, die mit Anzeigen interagieren, gespeichert werden, um die Anzeigenzielgruppenerstellung zu verbessern.';
$_['help_ad_personalization']               = 'Ermöglicht die Personalisierung von Anzeigen basierend auf Benutzerdaten, um den Benutzern relevantere Werbung anzuzeigen.';
$_['help_analytics_storage']                = 'Ermöglicht die Speicherung von Daten für Analysezwecke, um die Leistung der Website und das Benutzerverhalten zu verfolgen.';
$_['help_functionality_storage']            = 'Ermöglicht die Datenspeicherung zur Unterstützung von Funktionen wie Benutzerpräferenzen oder Website-Funktionen, die das Benutzererlebnis verbessern.';
$_['help_personalization_storage']          = 'Steuert die Speicherung von Daten zur Personalisierung des Benutzererlebnisses, wie z. B. empfohlene Inhalte oder Einstellungen.';
$_['help_security_storage']                 = 'Stellt die Speicherung von sicherheitsrelevanten Daten sicher, wie z. B. für Betrugsprävention und sichere Zugriffskontrolle.';
$_['help_wait_for_update']                  = 'Setzt die Zeit (in Millisekunden), die gewartet werden soll, bevor der Zustimmungsstatus aktualisiert wird, um sicherzustellen, dass alle Einstellungen angewendet werden.';
$_['help_ads_data_redaction']               = 'Reduziert Benutzerdaten, die mit Anzeigen in Verbindung stehen, und sorgt für Datenschutz, indem bestimmte identifizierbare Informationen verborgen werden.';
$_['help_url_passthrough']                  = 'Ermöglicht es der URL, die Zustimmungsprüfung zu passieren, was nützlich ist, um bestimmte Benutzerpfade zu verfolgen, ohne persönliche Daten zu speichern.';
$_['help_gcm_status']                       = 'Aktiviert den Google Consent Mode, der es Ihrer Website ermöglicht, das Verhalten von Google-Tags basierend auf den Zustimmungspräferenzen der Benutzer anzupassen. Dieser Modus bietet datenschutzfreundliches Tracking, das die Funktionalität von Analysen und Werbung im Einklang mit den Zustimmungspräferenzen ermöglicht.';

// Error
$_['error_permission']                      = 'Warnung: Sie haben keine Berechtigung, die Einstellungen für (GA4) Enhanced Measurement zu ändern!';
$_['error_refund_send']                     = 'Warnung: Die Rückerstattungsdaten konnten nicht an Google Analytics (GA4) gesendet werden. Bitte überprüfen Sie Ihre Einstellungen und versuchen Sie es erneut.';
$_['error_no_refundable_selected']          = 'Warnung: Es wurden keine Produkte für die Rückerstattung ausgewählt. Bitte wählen Sie mindestens ein Produkt aus, um die Rückerstattung zu bearbeiten.';
$_['error_google_tag_id']                   = 'Das Feld Google Tag ID ist erforderlich. Bitte geben Sie Ihre Google Analytics ID ein.';
$_['error_google_tag_id_invalid']           = 'Das Format der Google Tag ID ist ungültig. Stellen Sie sicher, dass es dem Format G-XXXXXXXXXX entspricht.';
$_['error_gtm_id']                          = 'Das Feld GTM ID ist erforderlich. Bitte geben Sie Ihre Measurement ID ein.';
$_['error_gtm_id_invalid']                  = 'Das Format der GTM ID ist ungültig. Stellen Sie sicher, dass es dem Format GTM-XXXXXXXX entspricht.';
$_['error_mp_api_secret']                   = 'Das Feld für das Measurement Protocol API Secret ist erforderlich. Bitte geben Sie Ihr Measurement Protocol API Secret ein.';
$_['error_mp_api_secret_invalid']           = 'Das Format des API-Geheimnisses des Measurement Protocol ist ungültig. Stellen Sie sicher, dass es dem Format XXXXXXX-XXXXXXX-XXXXXX entspricht.';
$_['error_measurement_implementation']      = 'Die Implementierung des Measurement Protocol API Secret ist nicht konfiguriert. Bitte wählen Sie entweder Global Site Tag oder Google Tag Manager aus.';
$_['error_refund_no_items']                 = 'Warnung: Die Produkt-ID, die mit dieser Bestellung verknüpft ist, wurde nicht gefunden.';
$_['error_refund_no_order_id']              = 'Warnung: Erforderliche Anforderungsparameter fehlen oder sind unvollständig.';
$_['error_analytics_extension']             = 'Es scheint, dass bereits ein anderes Analysetool auf Ihrer Seite aktiv ist. Mehr als ein solches Tool kann Probleme verursachen, wie z.B. doppelte oder fehlende Tracking-Daten. Bitte überprüfen Sie die Einstellungen Ihrer Seite.';
$_['error_tracking_delay']                  = 'Die Tracking-Verzögerung muss mindestens 100 Millisekunden betragen, um eine ordnungsgemäße Ereignisverfolgung sicherzustellen.';
$_['error_wait_for_update']                 = 'Der Wert „Warten auf Aktualisierung“ muss eine Zahl zwischen 0 und 10000 Millisekunden sein.';
