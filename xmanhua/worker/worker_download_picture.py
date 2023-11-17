import typing
import threading

from object.dpicture import Dpicture

def worker_download_picture(self, lock: threading.Lock, dpictures: typing.List[Dpicture]):
    while True:
        lock.acquire()
        if len(dpictures) > 0:
            dpicture = dpictures.pop(0)
            lock.release()
            self.download_picture(dpicture)
        else:
            # release lock and return 
            lock.release()
            break