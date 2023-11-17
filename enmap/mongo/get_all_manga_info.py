import typing

from mongo.mongo import *
from object.manga_info import Manga_Info
from object.chapter_info import Chapter_Info

def get_all_manga_info() -> typing.List[Manga_Info]:
    mangas_info = []
    for item_manga_info in coll_manga_info.find({}):
        mangas_info.append(Manga_Info(
            title=item_manga_info[TITLE_],
            xid=item_manga_info[XID_],
            author=item_manga_info[AUTHOR_],
            status=item_manga_info[STATUS_],
            cover=item_manga_info[COVER_],
            type=item_manga_info[TYPE_],
            chapter_nums=item_manga_info[CHAPTER_NUMS_],
            chapters_info=[Chapter_Info(
                title=chapter[TITLE_],
                chid=chapter[CHID_],
                index=chapter[INDEX_],
                pages=chapter[PAGES_],
            ) for chapter in item_manga_info[CHAPTERS_INFO]],
            description=item_manga_info[DESCRIPTION_],
        ))
    return mangas_info
