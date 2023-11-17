import typing

from mongo.mongo import *
from value.strings import *
from object.chapter import Chapter

def update_chapter_nums_chids(xid, chapter_nums, chids: typing.List[str]) -> bool:
    if coll_dd_mangalist.update_one({XID_: xid}, {sSET_: {CHAPTER_NUMS_: chapter_nums, CHIDS_: chids}}):
        return True
    return False

def update_chapters_dd_chids(xid, chapters: typing.List[Chapter] ,dd_chids: typing.List[str]) -> bool:
    if coll_dd_mangalist.update_one({XID_: xid}, {sSET_: {CHAPTERS_: [chapter.__dict__ for chapter in chapters], DD_CHIDS_: dd_chids}}):
        return True
    return False

