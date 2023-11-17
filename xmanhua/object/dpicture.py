from dataclasses import dataclass

@dataclass
class Dpicture:
    url: str 
    xid: str
    manga_title: str  # from which manga
    chid: str
    chapter_title: str  # from which chapter
    index: int # pic index
    pages: int
