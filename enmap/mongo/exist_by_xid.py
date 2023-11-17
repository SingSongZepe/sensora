
from mongo.mongo import *
from value.strings import *

# check in coll manga_info
def exist_by_xid(xid) -> bool:
    if coll_manga_info.find_one({XID_: xid}):
        return True
    return False

# check in coll manga_map
def exist_map_by_xid(xid) -> bool:
    if coll_manga_map.find_one({XID_: xid}):
        return True
    return False
