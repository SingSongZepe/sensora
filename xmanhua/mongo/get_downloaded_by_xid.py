import typing

from object.downloaded import Downloaded
from object.chapter import Chapter
from mongo.mongo import *

def get_downloaded_by_xid(xid) -> typing.Union[Downloaded, None]:
    if item_dd_manga := coll_dd_mangalist.find_one({XID_: xid}):
        return Downloaded(
            title=item_dd_manga[TITLE_],
            xid=item_dd_manga[XID_],
            author=item_dd_manga[AUTHOR_],
            status=item_dd_manga[STATUS_],
            cover=item_dd_manga[COVER_],
            type=item_dd_manga[TYPE_],
            chapter_nums=item_dd_manga[CHAPTER_NUMS_],
            chapters=[Chapter(
                title=chapter[TITLE_],
                chid=chapter[CHID_],
                index=chapter[INDEX_],
                pages=chapter[PAGES_]
            ) for chapter in item_dd_manga[CHAPTERS_]],
            chids=item_dd_manga[CHIDS_],
            dd_chids=item_dd_manga[DD_CHIDS_],
            description=item_dd_manga[DESCRIPTION_],
        )
    return None