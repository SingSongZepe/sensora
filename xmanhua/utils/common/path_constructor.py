from os.path import join as j

from object.dpicture import Dpicture
from value.strings import *

def picture_constructor(dpicture: Dpicture):
    return j(dp, dpicture.xid, dpicture.chid, picture_namer(dpicture.index))

def path_constructor(dpicture: Dpicture):
    return j(dp, dpicture.xid, dpicture.chid)

def picture_namer(idx):
    return f'{sszp}_{idx}.jpg'
