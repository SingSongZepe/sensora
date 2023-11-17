import os
import requests

from object.dpicture import Dpicture
from value.strings import *
from utils.common.log import *
from utils.common.path_constructor import path_constructor, picture_constructor
from mongo.insert_undd_picture import insert_undd_picture


def download_picture(self, dpicture: Dpicture):
    ln(f'requesting picture url: {dpicture.url}')
    picture_req = requests.get(dpicture.url, headers=picture_headers, verify=verify)
    if picture_req.status_code != 200:
        le('picture request failed')
        # record to undd_picture
        insert_undd_picture(dpicture)
        return
    try:  # maybe the directory is not exist
        with open(picture_constructor(dpicture), 'wb') as pf:
            pf.write(picture_req.content)
    except FileNotFoundError:
        try: # may be the directory was created by other thread
            os.makedirs(path_constructor(dpicture))
        except FileExistsError:
            pass
        with open(picture_constructor(dpicture), 'wb') as pf:
            pf.write(picture_req.content)
            