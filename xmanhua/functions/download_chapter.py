import re
import execjs
import requests
from pyquery import PyQuery as pq
from urllib.parse import urljoin as uj
import typing
import threading

from mongo.get_downloaded_by_xid import get_downloaded_by_xid
from mongo.update_downloaded_by_xid import update_chapters_dd_chids
from object.dchapter import Dchapter
from object.dpicture import Dpicture
from object.chapter import Chapter
from value.strings import *
from utils.common.log import *
from utils.common.retitle import retitle
from sszp.sszpThread import sszpThread

def download_chapter(self, dchapter: Dchapter):
    ln(f'{dchapter.chid} started to download')

    chapter_req = requests.get(uj(xmanhua_root_url, dchapter.chid), verify=verify)
    if chapter_req.status_code != 200:
        le(f'error while request manga: {dchapter.manga_title} chapter url: {uj(xmanhua_root_url, dchapter.chid)}')
        return
    
    # get the title for name
    chapter_doc = pq(chapter_req.text)
    chapter_title = retitle(chapter_doc('.reader-title a:last-child').text())
    ln(f'manga: {dchapter.manga_title} chapter title {chapter_title}')

    # get picture to download
    chapter_text = chapter_req.text

    COUNT = re.search(PATTERN_XMANHUA_COUNT, chapter_text)
    if COUNT:
        total = int(COUNT.group(1))
        print('SingSongLog: total page: ' + str(total))
    else:
        total = 100
        print('SingSongLog: match of XMANHUA_IMAGE_COUNT failed')
    
    # construct params
    params = {}
    CID = re.search(PATTERN_XMANHUA_CID, chapter_text)
    if CID:
        params[CID_] = CID.group(1)
        ln(f'cid: {params[CID_]}')
    else:
        le('error: get cid failed')
        return
    MID = re.search(PATTERN_XMANHUA_MID, chapter_text)
    if MID:
        params[MID_] = MID.group(1)
        ln(f'mid: {params[MID_]}')
    else:
        le('error: get mid failed')
        return
    
    # may be unnecessary
    # two parameters
    # VIEWSIGN_DT = re.search(PATTERN_XMANHUA_VIEWSIGN_DT, chapter_text)
    # if VIEWSIGN_DT:
    #     params[_DT_] = VIEWSIGN_DT.group(1)
    #     ln(f'dt: {params[_DT_]}')
    # else:
    #     le('error: get dt failed')
    # VIEWSIGN = re.search(PATTERN_XMANHUA_VIEWSIGN, chapter_text)
    # if VIEWSIGN:
    #     params[_SIGN_] = VIEWSIGN.group(1)
    #     ln(f'sign: {params[_SIGN_]}')
    # else:
    #     le('error get sign failed')
        
    ashx_url = uj(xmanhua_root_url, f'{dchapter.chid}/{chapter_image}')
    
    count =  1
    pictures = [] # list[str] urls
    while count <=  total:
        params[PAGE_] = str(count)
        ashx_req = requests.get(ashx_url, headers=ashx_headers, params=params, verify=verify)
        if ashx_req.status_code != 200:
            le('error request picture url')
            continue

        js_str = ashx_req.text
        urls: typing.List[str] = execjs.eval(js_str)
        if urls is None or len(urls) == 0:
            le('error: get urls failed')
            continue

        for idx in range(len(urls)):
            pictures.append(Dpicture(
                url=urls[idx],
                xid=dchapter.xid,
                manga_title=dchapter.manga_title,
                chid=dchapter.chid,
                chapter_title=chapter_title,
                index=idx + count,
                pages=total,
            ))
            ln(f'get picture url: {urls[idx]}')
        
        count += len(urls)
    
    lock = threading.Lock()
    sszpthreads = []
    for _ in range(d_picture_thread_num):
        sszpthread = sszpThread(func=lambda: self.worker_download_picture(lock, pictures))
        sszpthreads.append(sszpthread)
    for sszpthread in sszpthreads:
        sszpthread.start()
    for sszpthread in sszpthreads:
        sszpthread.join()

    # set mark
    if item_dd_manga := get_downloaded_by_xid(dchapter.xid):
        # append the chid to the database
        item_dd_manga.dd_chids.append(dchapter.chid)
        item_dd_manga.chapters.append(Chapter(
            title=chapter_title,
            chid=dchapter.chid,
            index=dchapter.index,
            pages=total,
        ))
        if update_chapters_dd_chids(dchapter.xid, item_dd_manga.chapters, item_dd_manga.dd_chids):
            ln(f'downloaded chapter recorded to the database')
        else:
            lw(f'failed to update chapter record to the database')
    else:
        le(f'error after download a chapter, can\'t find the manga: {dchapter.xid} in database')

    ln(f'a chapter of {dchapter.manga_title} is downloaded')
