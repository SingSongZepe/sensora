import typing

# check xid is exist ?
from value.strings import *
from mongo.mongo import *

def exist_by_xid(xid) -> bool:
    if coll_dd_mangalist.find_one({XID_: xid}):
        return True
    return False
