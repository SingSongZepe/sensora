
from mongo.mongo import *
from object.dpicture import Dpicture

def insert_undd_picture(dpicture: Dpicture) -> bool:
    if coll_undd_picture.insert_one(dpicture.__dict__):
        return True
    return False