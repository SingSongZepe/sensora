import typing

from mongo.mongo import *
from object.manga_map import Manga_Map
from value.strings import *

def get_all_manga_map() -> typing.List[Manga_Map]:
    mangas_map = []
    for manga_map in coll_manga_map.find({}):
        mangas_map.append(Manga_Map({key: value for key, value in manga_map.items() if key != sID_}))
    return mangas_map
