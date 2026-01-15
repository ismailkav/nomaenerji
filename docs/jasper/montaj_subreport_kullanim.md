# Montaj subreport (JRXML) kullanımı

Bu repo tarafında `resources/views/offers/create.blade.php` içinde PDF/print JSON payload'ına montaj detayları eklenir:

- `payload.header.montaj_var` (boolean)
- `payload.header.montaj_satir_sayisi` (int)
- `payload.montaj_satirlari` (array) → tüm montaj satırları (flat)
- Her teklif satırında: `line.montaj_groups`, `line.montaj_satirlari`, `line.montaj_satir_sayisi`

## JRXML tarafı (Tomcat/JasperReports)

Bu repo Tomcat tarafındaki ana `teklif` raporunu içermediği için burada sadece subreport şablonu verildi:

- `docs/jasper/teklif_montaj_subreport.jrxml`

Önerilen yaklaşım:

1. Ana raporda (teklif) Summary band veya ayrı bir group footer içinde **page break** ekleyin.
2. Aynı band içine `teklif_montaj_subreport.jrxml` subreport’unu ekleyin.
3. Subreport’un datasource’u olarak JSON içindeki `montaj_satirlari` array’ini verin.

Not: JSON datasource oluşturma biçimi Tomcat servisinizin implementasyonuna bağlıdır.

