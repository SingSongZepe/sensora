from dataclasses import dataclass
import typing

from object.picture_map import Picture_Map

@dataclass
class Chapter_Map:
    title: str
    chid: str
    pictures_map: typing.List[Picture_Map]