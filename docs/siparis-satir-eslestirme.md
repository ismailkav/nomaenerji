## Sipariş satır eşleştirme

Amaç: Alım siparişi satırları ile satış siparişi satırlarını eşleştirmek.

- Tablo: `siparis_satir_eslestirmeleri`
- İlişki: **1 alım satırı** → **çok satış satırı** (pivot / ilişki tablosu)
- Alanlar:
  - `alim_detay_id`: `siparis_detaylari.id` (alım siparişi satırı)
  - `satis_detay_id`: `siparis_detaylari.id` (satış siparişi satırı)
  - `miktar`: (opsiyonel) eşleştirilen miktar

Uygulama kuralı (DB dışı): `alim_detay_id` bağlandığı siparişin `siparis_turu=alim`, `satis_detay_id` bağlandığı siparişin `siparis_turu=satis` olmalı.

