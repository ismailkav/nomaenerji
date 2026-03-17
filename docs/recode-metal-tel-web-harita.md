# RecodeMetalTel → Laravel Web Haritası (Menü & Route Planı)

Bu doküman, `RecodeMetalTel` masaüstü uygulamasındaki `AnaPencere` menü hiyerarşisini Laravel web uygulamasına taşımak için sayfa/route haritası çıkarır.

## Kaynaklar

- Proje: `C:\Users\ismailkavak\Desktop\RecodeMetalTel`
- Menü hiyerarşisi + ekran açılışları: `RecodeMetalTel/src/view/AnaPencere.java`
- SQL kaynakları (DB işlemleri): `RecodeMetalTel/src/control/*DatabaseIslem.java` ve `RecodeMetalTel/src/control/*Database.java`
- Stok fiş tür sabitleri: `RecodeMetalTel/src/genelparametre/stokfis/StokFisTurleri.java`
- DB bağlantı detayı: `RecodeMetalTel/src/control/ConnectionManager.java` (MySQL, `Latin5` karakter seti parametresi ile)

## Menü → Sayfa Haritası

- Aşağıdaki tablolar “girişten sonra sol sidebar/slide menü” hiyerarşisini birebir taşımak için temel alınabilir.
- `Route (öneri)` alanı başlangıç için öneridir; ister Türkçe slug’larla, ister İngilizce/REST yaklaşımıyla revize edilebilir.
- `Ekran` alanı, Java tarafında açılan `Frame` (veya helper çağrısı) bilgisidir; web’de aynı iş akışı için hedef sayfayı gösterir.

## Satış (AnaPencere: `orderMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Teklif Giriş | form | /satis/teklif-giris | orderTeklifMenuItem_actionPerformed | OrderTeklifFrame |
| Teklif Listesi | liste | /satis/teklif-listesi | orderTeklifListeMenuItem_actionPerformed | OrderTeklifListeFrame |
| Order Giriş | form | /satis/order-giris | orderGirisMenuItem_actionPerformed | OrderFrame |
| Order Listesi | liste | /satis/order-listesi | orderListeMenuItem_actionPerformed | OrderListeFrame |
| Order Termin Listesi | liste | /satis/order-termin-listesi | orderTerminListeMenuItem_actionPerformed | OrderTerminListeFrame |
| Order Takip Listesi | liste | /satis/order-takip-listesi | orderTakipListeMenuItem_actionPerformed | OrderTakipListeFrame |
| Order Termin Ajanda | ajanda | /satis/order-termin-ajanda | orderTerminAjandaMenuItem_actionPerformed | OrderAjandaFrame |
| Müşteri Görüşme Kartı | form | /satis/musteri-gorusme-karti | gorusmeKartiMenuItem_actionPerformed | GorusmeKartFrame |
| Müşteri Görüşme Listesi | liste | /satis/musteri-gorusme-listesi | gorusmeListesiMenuItem_actionPerformed | GorusmeListeFrame |
| Satış Kullanım Kılavuzu | kılavuz | /satis/satis-kullanim-kilavuzu | satisKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Satın Alma (AnaPencere: `menu_2`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Talep | sayfa | /satin-alma/talep | talepMenuItem_actionPerformed | TalepFrame |
| Talep Listesi | liste | /satin-alma/talep-listesi | talepListeMenuItem_actionPerformed | TalepListeDetayFrame |
| Talep Kontrol Listesi | liste | /satin-alma/talep-kontrol-listesi | talepKontrolListeFrame_actionPerformed | TalepOnayListeFrame |
| Talep Onay Listesi | onay | /satin-alma/talep-onay-listesi | talepOnayListeMenuItem_actionPerformed | TalepOnayListeFrame |
| Sipariş Fiş Hazırlık | form | /satin-alma/siparis-fis-hazirlik | siparisFisHazirlikMenuItem_actionPerformed | SiparisFisHazirlikListeFrame |
| Sipariş Fiş | form | /satin-alma/siparis-fis | siparisFisMenuItem_actionPerformed | SiparisFisFrame |
| Sipariş Fiş Listesi | liste | /satin-alma/siparis-fis-listesi | siparisFisListeMenuItem_actionPerformed | SiparisFisListeFrame |
| Mal Alım Fişleri | form | /satin-alma/mal-alim-fisleri | malAlimFisleriMenuItem_actionPerformed | stokFisAc(StokFisTurleri.malAlim) |
| Mal Alım Fiş Listesi | liste | /satin-alma/mal-alim-fis-listesi | malAlimFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.malAlim) |
| Satınalma Kullanım Kılavuzu | kılavuz | /satin-alma/satinalma-kullanim-kilavuzu | satinAlmaKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Planlama (AnaPencere: `planlamaMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Üretim Planlama | sayfa | /planlama/uretim-planlama | uretimPlanlamaMenuItem_actionPerformed | OrderPlanlamaFrame |
| Üretim Planlama Listesi | liste | /planlama/uretim-planlama-listesi | uretimPlanlamaListeMenuItem_actionPerformed | OrderPlanlamaListeFrame |
| Üretim Planlama Onay Listesi | onay | /planlama/uretim-planlama-onay-listesi | uretimPlanlamaUretimOnayListeMenuItem_actionPerformed | OrderPlanlamaUretimOnayListeFrame |
| Genel Makina Planlama Listesi | liste | /planlama/genel-makina-planlama-listesi | makinaPlanlamaListeMenuItem_actionPerformed | MakinaPlanlamaListeDetayFrame |
| Detaylı Makina Planlama Listesi | liste | /planlama/detayli-makina-planlama-listesi | makinaPlanlamaDurumListeMenuItem_actionPerformed | MakinaPlanlamaListeFrame |
| Planlama Kullanım Kılavuzu | kılavuz | /planlama/planlama-kullanim-kilavuzu | planlamaKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Üretim (AnaPencere: `imalatMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Üretim Deposuna Çıkış | sayfa | /uretim/uretim-deposuna-cikis | orderTedarikMenuItem_actionPerformed | OrderTedarikFrame |
| Sevkiyat Deposuna Çıkış | sayfa | /uretim/sevkiyat-deposuna-cikis | uretimGirisIslemMenuItem_actionPerformed | UretimFormFrame |
| Üretim Fişleri | form | /uretim/uretim-fisleri | imalatStokIslemleriMenuItem_actionPerformed | stokFisAc(StokFisTurleri.uretim) |
| Üretim Fiş Listesi | liste | /uretim/uretim-fis-listesi | uretimFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.uretim) |
| Günlük Üretim Listesi | liste | /uretim/gunluk-uretim-listesi | gunlukUretimMenuItem_actionPerformed | StokFisListeDetayFrame |
| Order Durum Listesi | liste | /uretim/order-durum-listesi | orderDurumListeMenuItem_actionPerformed | OrderDurumListeFrame |
| Üretim Kullanım Kılavuzu | kılavuz | /uretim/uretim-kullanim-kilavuzu | uretimKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Satış Operasyon (AnaPencere: `menu_1`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Üretim Sevk Operasyon | sayfa | /satis-operasyon/uretim-sevk-operasyon | uretimSevkOperasyonMenuItem_actionPerformed | UretimMiktarDuzeltListeFrame |
| Hazır Mamül Sevk Operasyon | sayfa | /satis-operasyon/hazir-mamul-sevk-operasyon | hazirMamulSatisMenuItem_actionPerformed | HazirMamulListeFrame |
| Mal Satış Fişleri | form | /satis-operasyon/mal-satis-fisleri | stokFisMenuItem_actionPerformed | stokFisAc(StokFisTurleri.malSatis) |
| Mal Satış Fiş Listesi | liste | /satis-operasyon/mal-satis-fis-listesi | malSatisFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.malSatis) |
| Günlük Sevkiyat Listesi | liste | /satis-operasyon/gunluk-sevkiyat-listesi | gunlukSevkiyatListesiMenuItem_actionPerformed | StokFisListeDetayFrame |
| Fason Fişleri | form | /satis-operasyon/fason-fisleri | fasonFisleriMenuItem_actionPerformed | stokFisAc(StokFisTurleri.fason) |
| Fason Fiş Listesi | liste | /satis-operasyon/fason-fis-listesi | fasonFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.fason) |
| Sepet Fişleri | form | /satis-operasyon/sepet-fisleri | sepetFisleriMenuItem_actionPerformed | stokFisAc(StokFisTurleri.sepet) |
| Sepet Fiş Listesi | liste | /satis-operasyon/sepet-fis-listesi | sepetFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.sepet) |
| Depo Transfer Fişi | form | /satis-operasyon/depo-transfer-fisi | depoTransferFisi_actionPerformed | stokFisAc(StokFisTurleri.depoTransfer) |
| Depo Transfer Fiş Listesi | liste | /satis-operasyon/depo-transfer-fis-listesi | depoTransferFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.depoTransfer) |
| Operasyon Kullanım Kılavuzu | kılavuz | /satis-operasyon/operasyon-kullanim-kilavuzu | satisOperasyonKullanimKilavuzuItemMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Yönetim (AnaPencere: `yonetimMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Teklif Onay Listesi | onay | /yonetim/teklif-onay-listesi | orderTeklifOnayListeMenuItem_actionPerformed | OrderTeklifOnayListeFrame |
| Order Onay Listesi | onay | /yonetim/order-onay-listesi | orderOnayListeMenuItem_actionPerformed | OrderOnayListeFrame |
| Sipariş Onay Listesi | onay | /yonetim/siparis-onay-listesi | siparisFisOnayListeMenuItem_actionPerformed | SiparisFisOnayListeFrame |
| Sözleşme Onay Listesi | onay | /yonetim/sozlesme-onay-listesi | sozlesmeOnayListeMenuItem_actionPerformed | SozlesmeOnayListeFrame |
| Ortalama Vade Hesaplama Onay Listesi | onay | /yonetim/ortalama-vade-hesaplama-onay-listesi | ortalamaVadeHesaplamaOnayListeMenuItemMenuItem_actionPerformed | CariVadeHesaplamaOnayListeFrame |
| Personel Maaş Onay Listesi | onay | /yonetim/personel-maas-onay-listesi | personelMaasOnayListesiMenuItem_actionPerformed | PersonelFisOnayListeFrame |
| Personel Kartı Onay Listesi | onay | /yonetim/personel-karti-onay-listesi | personelKartiOnayListesiMenuItem_actionPerformed | PersonelGenelListeFrame |
| Sayım Fişleri | form | /yonetim/sayim-fisleri | sayimFisleriMenuItem_actionPerformed | stokFisAc(StokFisTurleri.sayim) |
| Sayım Fiş Listesi | liste | /yonetim/sayim-fis-listesi | sayimFisListesiMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.sayim) |
| Cari Satış Ekstresi | rapor | /yonetim/cari-satis-ekstresi | cariSatisListeMenuItem_actionPerformed | CariSatisListeFrame |
| Stok Fiş Listesi | liste | /yonetim/stok-fis-listesi | stokFisListeDetayMenuItem_actionPerformed | stokFisListeAc(StokFisTurleri.stok) |
| Stok-İşçilik Ekstresi | rapor | /yonetim/stok-iscilik-ekstresi | stokIscilikEkstreMenuItem_actionPerformed | StokIscilikEkstreFrame |
| Sözleşme Giriş | form | /yonetim/sozlesme-giris | sozlesmeMenuItem_actionPerformed | SozlesmeFrame |
| Sözleşme Listesi | liste | /yonetim/sozlesme-listesi | sozlesmeListeMenuItem_actionPerformed | SozlesmeListeFrame |
| Yönetim Kullanım Kılavuzu | kılavuz | /yonetim/yonetim-kullanim-kilavuzu | yonetimKullanimKılavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Stok (AnaPencere: `stokMenu_1`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Stok Envanter Listesi | liste | /stok/stok-envanter-listesi | stokEnvanterListeMenuItem_actionPerformed | StokEnvanterListeFrame |
| Stok Ekstresi | rapor | /stok/stok-ekstresi | stokEkstreMenuItem_actionPerformed | StokEkstreFrame |
| Depo Ekstresi | rapor | /stok/depo-ekstresi | depoEkstreMenuItem_actionPerformed | DepoEkstreFrame |
| Depo Giriş-Çıkış Ekstresi | rapor | /stok/depo-giris-cikis-ekstresi | depoGirisCikisEkstreMenuItem_actionPerformed | DepoEkstreFrame |
| Stok Kullanım Kılavuzu | kılavuz | /stok/stok-kullanim-kilavuzu | stokKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Finans (AnaPencere: `finansMenu_1`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Kur Aktar | sayfa | /finans/kur-aktar | kurAktarMenuItem_actionPerformed | KurGirisFrame |
| Hammadde Fiyat Listesi | liste | /finans/hammadde-fiyat-listesi | hammaddeFiyatListesiMenuItem_actionPerformed | DetayGrupTanimlamaFrame |
| Ortalama Vade Hesaplama | sayfa | /finans/ortalama-vade-hesaplama | ortalamaVadeHesaplamaMenuItem_actionPerformed | CariVadeHesaplamaFrame |
| Ortalama Vade Hesaplama Listesi | liste | /finans/ortalama-vade-hesaplama-listesi | ortalamaVadeHesaplamaListeMenuItem_actionPerformed | CariVadeHesaplamaListeFrame |
| DBS Listesi | liste | /finans/dbs-listesi | dbsMenuItem_actionPerformed | DbsOdemeFrame |
| Finans Kullanım Kılavuzu | kılavuz | /finans/finans-kullanim-kilavuzu | finansKullanimKulavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Tanımlama (AnaPencere: `tanimlamaMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Personel | sayfa | /tanimlama/personel | personelMenuItem_actionPerformed | KullaniciKartFrame |
| İşçilik Tanımlama | tanim | /tanimlama/iscilik-tanimlama | iscilikTanimlamaMenuItem_actionPerformed | IscilikTanimlamaFrame |
| Alt İşçilik Tanımlama | tanim | /tanimlama/alt-iscilik-tanimlama | altIscilikTanimlamaMenuItem_actionPerformed | TekKolonTanimlamaFrame |
| Makina Kartı | form | /tanimlama/makina-karti | makinaKartiMenuItem_actionPerformed | MakinaTanimlamaFrame |
| Makina Cinsi | sayfa | /tanimlama/makina-cinsi | makinaCinsiMenuItem_actionPerformed | TekKolonTanimlamaFrame |
| Depo Tanımlama | tanim | /tanimlama/depo-tanimlama | depoMenuItem_actionPerformed | DepoTanimlamaFrame |
| Görüşme Kategori Tanımlama | tanim | /tanimlama/gorusme-kategori-tanimlama | gorusmeKategoriTanimlamaMenuItem_actionPerformed | TanimlamaFrame |
| Görüşme Öncelik Tanımlama | tanim | /tanimlama/gorusme-oncelik-tanimlama | gorusmeOncelikTanimlamaMenuItem_actionPerformed | TanimlamaFrame |
| Görüşme Şekli Tanımlama | tanim | /tanimlama/gorusme-sekli-tanimlama | gorusmeSekliTanimlamaMenuItem_actionPerformed | TanimlamaFrame |
| Görüşme Sonuçlanma Tanımlama | tanim | /tanimlama/gorusme-sonuclanma-tanimlama | sonuclanmaTanimlamaMenuItem_actionPerformed | TanimlamaFrame |
| Tablo Renk Tanımlama | tanim | /tanimlama/tablo-renk-tanimlama | tabloRenkMenuItem_actionPerformed | KullaniciRenkFrame |
| Görev Tanımla | tanim | /tanimlama/gorev-tanimla | newItemMenuItem_1_actionPerformed | TanimlamaFrame |
| Bölüm Tanımla | tanim | /tanimlama/bolum-tanimla | newItemMenuItem_2_actionPerformed | TanimlamaFrame |
| Tanımlama Kullanım Kılavuzu | kılavuz | /tanimlama/tanimlama-kullanim-kilavuzu | tanimlamaKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Cari Tanımlama (AnaPencere: `cariMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Cari Kart | form | /cari-tanimlama/cari-kart | cariKartMenuItem_actionPerformed | CariKartFrame |
| Cari Ana Grup | sayfa | /cari-tanimlama/cari-ana-grup | cariAnaGrupMenuItem_actionPerformed | TanimlamaFrame |
| Cari Alt Grup | sayfa | /cari-tanimlama/cari-alt-grup | cariAltGrupMenuItem_actionPerformed | AltGrupTanimlamaFrame |
| Cari Kart Listesi | liste | /cari-tanimlama/cari-kart-listesi | cariKartListesiMenuItem_actionPerformed | CariKartListeFrame |
| Cari Kullanım Kılavuzu | kılavuz | /cari-tanimlama/cari-kullanim-kilavuzu | cariTanimlamaKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Stok Tanımlama (AnaPencere: `stokMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Stok Kart | form | /stok-tanimlama/stok-kart | stokKartMenuItem_actionPerformed | StokKartFrame |
| Stok Kart Listesi | liste | /stok-tanimlama/stok-kart-listesi | stokKartListesiMenuItem_actionPerformed | StokKartListeFrame |
| Stok Ana Grup | sayfa | /stok-tanimlama/stok-ana-grup | stokAnaGrupMenuItem_actionPerformed | TanimlamaFrame |
| Stok Alt Grup | sayfa | /stok-tanimlama/stok-alt-grup | stokAltGrupMenuItem_actionPerformed | AltGrupTanimlamaFrame |
| Stok Detay Grup | sayfa | /stok-tanimlama/stok-detay-grup | stokDetayGrupMenuItem_actionPerformed | DetayGrupTanimlamaFrame |
| Stok Tanımlama Kullanım Kılavuzu | kılavuz | /stok-tanimlama/stok-tanimlama-kullanim-kilavuzu | stokTanimlamaKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## CRM (AnaPencere: `crmMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Kullanıcı Notları | sayfa | /crm/kullanici-notlari | kullaniciNotMenuItem_actionPerformed | KullaniciNotFrame |
| Ajanda | ajanda | /crm/ajanda | ajandaMenuItem_actionPerformed | AjandaFrame |
| CRM Kullanım Kılavuzu | kılavuz | /crm/crm-kullanim-kilavuzu | crmKullanimKilavuzuMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Personel (AnaPencere: `personelMenu`)

| Sayfa | Tür | Route (öneri) | AnaPencere method | Ekran |
|---|---|---|---|---|
| Personel İşlem Fişi | form | /personel/personel-islem-fisi | personelFisMenuItem_actionPerformed | PersonelFisFrame |
| Personel İşlem Fiş Liste | liste | /personel/personel-islem-fis-liste | personelListeMenuItem_actionPerformed | PersonelIslemFisListeFrame |
| Personel Maaş Borç/Alacak Listesi | liste | /personel/personel-maas-borcalacak-listesi | maasRaporuMenuItem_actionPerformed | PersonelMaasBorcAlacakListeFrame |
| Personel Tanımlama | tanim | /personel/personel-tanimlama | personelTanimlamaMenuItem_actionPerformed | PersonelKartFrame |
| Personel Liste (Maaş) | liste | /personel/personel-liste-maas | personelListeMaasMenuItem_actionPerformed | PersonelGenelListeFrame |
| Personel Liste (Bedenler) | liste | /personel/personel-liste-bedenler | personelListeBedenlerMenuItem_actionPerformed | PersonelGenelListeFrame |
| Personel Liste (Genel) | liste | /personel/personel-liste-genel | personelListeGenelMenuItem_actionPerformed | PersonelGenelListeFrame |
| Personel Kullanım Kılavuzu | kılavuz | /personel/personel-kullanim-kilavuzu | personelFisAciklamaMenuItem_actionPerformed | PersonelKullanimKilavuzuFrame |

## Stok Fişleri: Tür Parametresi (Web’de tek modül)

`AnaPencere` içinde bazı menüler doğrudan `StokFisFrame` / `StokFisListeDetayFrame` açmıyor; `stokFisAc(fisTur)` ve `stokFisListeAc(fisTur)` helper’ları ile “fiş türü” parametresiyle aynı ekranı farklı modda açıyor.

`RecodeMetalTel/src/genelparametre/stokfis/StokFisTurleri.java` içindeki anahtarlar:

- `stokFis`
- `uretimStokFis`
- `malAlimStokFis`
- `malSatisStokFis`
- `fasonStokFis`
- `sepetStokFis`
- `sayimStokFis`
- `depoTransferStokFis`
- `ozelFisCikis`
- `ozelFisGiris`

Web tarafında bunları tek bir modülde toplamak pratik olur:

- Form: `/stok-fis/{tur}` (create/edit)
- Liste: `/stok-fis/{tur}/liste`

## SQL Kaynakları (Hızlı Referans)

SQL’lerin ana kaynağı `RecodeMetalTel/src/control/` altındaki sınıflar:

- Satış / Order: `OrderDatabaseIslem`, `OrderDetayDatabaseIslem`, `OrderTeklifDatabaseIslem`, `OrderTeklifDetayDatabaseIslem`, `OrderPlanlamaDatabaseIslem`, `OrderTedarikDatabaseIslem`, `OrderMakinaPlanlamaDatabaseIslem`
- Satın Alma: `TalepDatabaseIslem`, `SiparisFisDatabaseIslem`, `SiparisFisDetayDatabaseIslem`, `SiparisFisHazirlikDatabaseIslem`
- Stok: `StokFisDatabaseIslem`, `StokKartDatabaseIslem`, `StokKartMuadilDatabaseIslem`, `StokEnvanterDatabaseIslem`, `StokTahsisDatabaseIslem`
- Finans: `KurDatabaseIslem`, `KurIslemDatabaseIslem`, `CariVadeHesaplamaDatabaseIslem`, `DbsOdemeDatabaseIslem`, `FiyatListesiDatabase`
- Ajanda / CRM / Görüşme: `AjandaDatabaseIslem`, `GorusmeDatabaseIslem`, `KullaniciNotDatabaseIslem`
- Personel: `PersonelFisDatabaseIslem`, `PersonelKartDatabaseIslem`, `PersonelGorevDatabaseIslem`
- Tanımlama: `TanimlamaDatabaseIslem`, `KolonAyarlaDatabaseIslem`

## Notlar

- `TanimlamaFrame`, `TekKolonTanimlamaFrame`, `AltGrupTanimlamaFrame`, `DetayGrupTanimlamaFrame` gibi ekranlar web’de “konfigürasyonla çalışan tek CRUD sayfası” olarak konsolide edilebilir.
- Masaüstündeki tablo kolon genişliği/renk gibi “kolon ayarları” web’de farklı bir yaklaşımla (kullanıcı bazlı tablo görünümü ayarı) ele alınabilir.
