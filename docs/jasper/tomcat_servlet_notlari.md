## Tomcat Servlet (JsonToPdfServlet) – Gerekli Değişiklik Notları

Bu repo içinde Tomcat/Java kaynak kodu yok. Aşağıdaki notlar `com.nomaenerji.servlet.JsonToPdfServlet` ve `com.nomaenerji.jasper.JsonJasperPdfGenerator` tarafında yapılması gereken değişiklikleri tarif eder.

### Hedef
- JSON payload’daki **header** alanları Jasper **parameter**’larına map edilsin.
- JSON payload’daki listeler **JRRewindableDataSource** (örn. `JRMapCollectionDataSource`) olarak subreport’lara gönderilsin.
- JRXML tarafında JSON query executer / `JsonDataSource` cast / `subDataSource()` kullanılmasın.

### JRXML Beklentisi
`docs/jasper/nomaenerji_form.jrxml` ana raporu şu parametreleri bekler:
- `SUBREPORT_DIR` (String): form dosyalarının dizini (örn. `C:\\NomaenerjiFormlar`)
- `DS_URUN_GRUBU_MARKA` (JRRewindableDataSource): `urun_grubu_marka_toplamlari` listesi
- `DS_GENEL_TOPLAM` (JRRewindableDataSource): `genel_toplamlar` listesi
- `DS_MONTAJ` (JRRewindableDataSource): `montaj_satirlari` listesi
- Header parametreleri:
  - `P_TEKLIF_NO`, `P_REVIZE_NO`, `P_TARIH`, `P_GECERLILIK_TARIHI`
  - `P_FIRMA_KOD`, `P_FIRMA_UNVAN`, `P_FIRMA_ADRES1`, `P_FIRMA_ADRES2`, `P_FIRMA_IL_ILCE`
  - `P_YETKILI_PERSONEL`, `P_HAZIRLAYAN`, `P_PROJE_KOD`, `P_ACIKLAMA`

### Servlet / Generator tarafı örnek akış
1) JSON’u parse edin (Jackson önerilir):
```java
ObjectMapper mapper = new ObjectMapper();
Map<String,Object> payload = mapper.readValue(body, new TypeReference<Map<String,Object>>(){});
Map<String,Object> header = (Map<String,Object>) payload.getOrDefault("header", Collections.emptyMap());
```

2) Jasper parametre map’i oluşturun:
```java
Map<String,Object> params = new HashMap<>();
String formDir = String.valueOf(payload.getOrDefault("form_dosya_yolu", ""));
params.put("SUBREPORT_DIR", formDir);

params.put("P_TEKLIF_NO", String.valueOf(header.getOrDefault("teklif_no","")));
params.put("P_REVIZE_NO", String.valueOf(header.getOrDefault("revize_no","")));
params.put("P_TARIH", String.valueOf(header.getOrDefault("tarih","")));
params.put("P_GECERLILIK_TARIHI", String.valueOf(header.getOrDefault("gecerlilik_tarihi","")));

params.put("P_FIRMA_KOD", String.valueOf(header.getOrDefault("firma_kod","")));
params.put("P_FIRMA_UNVAN", String.valueOf(header.getOrDefault("firma_unvan","")));
params.put("P_FIRMA_ADRES1", String.valueOf(header.getOrDefault("firma_adres1","")));
params.put("P_FIRMA_ADRES2", String.valueOf(header.getOrDefault("firma_adres2","")));
params.put("P_FIRMA_IL_ILCE", String.valueOf(header.getOrDefault("firma_il_ilce","")));

params.put("P_YETKILI_PERSONEL", String.valueOf(header.getOrDefault("yetkili_personel","")));
params.put("P_HAZIRLAYAN", String.valueOf(header.getOrDefault("hazirlayan","")));
params.put("P_PROJE_KOD", String.valueOf(header.getOrDefault("proje_kod","")));
params.put("P_ACIKLAMA", String.valueOf(header.getOrDefault("aciklama","")));
```

3) Listeleri `JRRewindableDataSource`’a çevirin (`JRMapCollectionDataSource` uygundur):
```java
List<Map<String,Object>> summary = (List<Map<String,Object>>) payload.getOrDefault("urun_grubu_marka_toplamlari", Collections.emptyList());
List<Map<String,Object>> totals  = (List<Map<String,Object>>) payload.getOrDefault("genel_toplamlar", Collections.emptyList());
List<Map<String,Object>> montaj  = (List<Map<String,Object>>) payload.getOrDefault("montaj_satirlari", Collections.emptyList());

params.put("DS_URUN_GRUBU_MARKA", new JRMapCollectionDataSource(summary));
params.put("DS_GENEL_TOPLAM", new JRMapCollectionDataSource(totals));
params.put("DS_MONTAJ", new JRMapCollectionDataSource(montaj));
```

4) Ana raporu doldururken JSON query executer’a ihtiyaç yok:
```java
JasperReport report = (JasperReport) JRLoader.loadObject(new File(formDir, "nomaenerji_form.jasper"));
JasperPrint print = JasperFillManager.fillReport(report, params, new JREmptyDataSource(1));
byte[] pdf = JasperExportManager.exportReportToPdf(print);
```

### Önemli
- `urun_grubu_marka_toplamlari`, `genel_toplamlar`, `montaj_satirlari` listeleri JSON’da yoksa servlet tarafında üretmeniz gerekir.
- Montaj gruplaması için `montaj_satirlari` satırlarında `urun_ana_grup`, `prm3`, `prm4` alanları dolu olmalı.
