from dataclasses import asdict

from mongo.mongo import *
from object.manga_map import Manga_Map

def insert_manga_map(manga_map: Manga_Map) -> bool:
    if coll_manga_map.insert_one(asdict(manga_map)):
        return True
    return False
