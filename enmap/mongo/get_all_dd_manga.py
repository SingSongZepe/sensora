import typing

from mongo.mongo import *
from object.manga_info import Manga_Info
from object.chapter_info import Chapter_Info

# from 
def get_all_dd_manga() -> typing.List[Manga_Info]:
    mangas_info = []
    for item_dd_manga in coll_dd_mangalist.find({}):
        mangas_info.append(Manga_Info(
            title=item_dd_manga[TITLE_],
            xid=item_dd_manga[XID_],
            author=item_dd_manga[AUTHOR_],
            status=item_dd_manga[STATUS_],
            cover=item_dd_manga[COVER_],
            type=item_dd_manga[TYPE_],
            chapter_nums=item_dd_manga[CHAPTER_NUMS_],
            chapters_info=[Chapter_Info(
                title=chapter[TITLE_],
                chid=chapter[CHID_],
                index=chapter[INDEX_],
                pages=chapter[PAGES_],
            ) for chapter in item_dd_manga[CHAPTERS_]],
            description=item_dd_manga[DESCRIPTION_],
        ))
    return mangas_info
