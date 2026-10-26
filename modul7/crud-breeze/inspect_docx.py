from docx import Document
from docx.shared import Emu
from docx.oxml.ns import qn
import sys

SRC = r"707012500064_Akmallutfi Afza Gusty_Laporan Praktikum 06.docx"
doc = Document(SRC)

print("== paragraphs ==")
for i, p in enumerate(doc.paragraphs):
    has_img = bool(p._element.findall('.//' + qn('pic:pic')))
    bold = [r.text for r in p.runs if r.bold]
    fonts = set()
    for r in p.runs:
        if r.font.name:
            fonts.add(r.font.name)
        if r.font.size:
            fonts.add(str(r.font.size.pt))
    print(f"{i:3d} | style={p.style.name} | align={p.alignment} | img={has_img} | bold={bool(bold)} | fonts={fonts} | {p.text[:100]!r}")

print("\n== tables ==", len(doc.tables))
print("== sections ==", len(doc.sections))
for s in doc.sections:
    print(s.page_width, s.page_height, s.left_margin, s.right_margin)

print("\n== inline shapes ==", len(doc.inline_shapes))
for sh in doc.inline_shapes:
    print(sh.type, sh.width, sh.height)
