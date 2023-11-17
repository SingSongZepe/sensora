from dataclasses import dataclass
import typing

from object.chapter_info import Chapter_Info

@dataclass
class Manga_Info:
    title: str
    xid: str
    author: str
    status: str
    cover: str
    type: typing.List[str] #
    chapter_nums: int
    chapters_info: typing.List[Chapter_Info]
    description: str
