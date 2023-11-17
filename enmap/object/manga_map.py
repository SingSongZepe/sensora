from dataclasses import dataclass
import typing

from object.chapter_map import Chapter_Map

@dataclass 
class Manga_Map:
    title: str
    xid: str
    chapters_map: typing.List[Chapter_Map] 
    