from dataclasses import dataclass

@dataclass
class Dchapter:
    chid: str
    xid: str           # which manga belong to
    manga_title: str   # the manga_title, don't get it from database, it's too slow
    index: int         # the index of the chapter in the manga
    