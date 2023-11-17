import typing
import threading

from object.dchapter import Dchapter

def worker_download_chapter(self, lock: threading.Lock, dchapters: typing.List[Dchapter]):
    while True:
        lock.acquire()
        if len(dchapters) != 0:
            dchapter = dchapters.pop(0)
            lock.release()
            self.download_chapter(dchapter)
        else:
            lock.release()
            break