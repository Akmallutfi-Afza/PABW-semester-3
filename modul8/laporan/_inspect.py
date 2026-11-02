from docx import Document
from docx.shared import Emu
from docx.oxml.ns import qn
import glob, os

doc = Document("707012500064_Akmallutfi Afza Gusty_Laporan Praktikum 08.docx")
i = 0
for p in doc.paragraphs:
    for pic in p._element.findall('.//' + qn('pic:pic')):
        blip = pic.find('.//' + qn('a:blip'))
        rid = blip.get(qn('r:embed'))
        part = doc.part.related_parts[rid]
        ext = pic.find('.//' + qn('a:ext'))
        print(i, "w=", ext.get('cx'), "h=", ext.get('cy'), part.partname, len(part.blob))
        i += 1
print("---- ray png sizes")
for f in sorted(glob.glob("laporan-assets/ray/*.png")):
    print(os.path.basename(f))
