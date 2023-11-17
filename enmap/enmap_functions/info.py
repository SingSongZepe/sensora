import typing

from utils.common.log import *
from value.strings import *
from mongo.get_all_dd_manga import get_all_dd_manga
from mongo.exist_by_xid import exist_by_xid
from mongo.insert_manga_info import insert_manga_info
from object.manga_info import Manga_Info

# manga info is not need to put picture info, but need pages info
def info(self) -> typing.List[Manga_Info]:
    mangas_info = []
    for manga_info in get_all_dd_manga(): 
        if not exist_by_xid(manga_info.xid): # existence in coll_manga_info
            # there if you are downloading mangas, will not record to-come manga chapters
            # so you must after completion of downloading manga, then use this function

            # sort the manga_info 
            manga_info.chapters_info = sorted(manga_info.chapters_info, key=lambda obj: obj.index)
            if insert_manga_info(manga_info): #
                mangas_info.append(manga_info)
                ln(f'insert manga info successfully, manga xid: {manga_info.xid}')
    return mangas_info
    