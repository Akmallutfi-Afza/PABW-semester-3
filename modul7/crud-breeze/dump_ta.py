from docx import Document
import sys
for f in [r"D:\laragon\www\PABW\Praktikum\modul6\laporan\707012500064_Akmallutfi Afza Gusty_Laporan Tugas Akhir 06.docx"]:
    d = Document(f)
    print("FILE", f)
    for i,p in enumerate(d.paragraphs[:40]):
        print(i, repr(p.text[:120]))
