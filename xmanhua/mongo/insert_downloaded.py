
from mongo.mongo import *
from object.downloaded import Downloaded

# insert and not check
def insert_downloaded(downloaded: Downloaded) -> bool:
    if coll_dd_mangalist.insert_one(downloaded.__dict__):
        return True
    return False
