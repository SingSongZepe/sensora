import threading

from mongo.get_downloaded_by_xid import get_downloaded_by_xid
from utils.common.log import *
from object.dchapter import Dchapter
from value.strings import *
from sszp.sszpThread import sszpThread

def download_launcher(self):
    # launch worker threads
    # download is out of order
    d_chapter_lock = threading.Lock()
    sszpthreads: typing.List[sszpThread] = []
    for _ in range(d_chapter_thread_num):
        sszpthread = sszpThread(func=lambda: self.worker_download_chapter(d_chapter_lock, self.dchapters))
        sszpthreads.append(sszpthread)

    for manga in self.mangalist: # object.download.Download
        if item_dd_manga := get_downloaded_by_xid(manga.xid):
            # d_chids = list(set(item_dd_manga.chids) - set(item_dd_manga.dd_chids))
            d_chids = [chid for chid in item_dd_manga.chids if chid not in item_dd_manga.dd_chids]
            for index, chid in enumerate(d_chids):
                self.dchapters.append(Dchapter(
                    chid=chid,
                    xid=item_dd_manga.xid,
                    manga_title=item_dd_manga.title,
                    index=index + len(item_dd_manga.dd_chids)
                ))
        else:
            le(f'manga not found in database: {manga.xid}')

    for sszpthread in sszpthreads:
        sszpthread.start()
    for sszpthread in sszpthreads:
        sszpthread.join()
    
    ln('all manga downloaded')