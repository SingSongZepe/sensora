from dataclasses import asdict

from mongo.mongo import *
from object.manga_info import Manga_Info

def insert_manga_info(manga_info: Manga_Info) -> bool:
    if coll_manga_info.insert_one(asdict(manga_info)):
        return True
    return False
