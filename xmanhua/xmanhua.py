import typing

from value.strings import *
from object.download import Download

class XMANHUA:
    # ! xmanhua_functions
    from xmanhua_functions.collect import collect
    from xmanhua_functions.download_launcher import download_launcher
    # ! worker
    from worker.worker_download_chapter import worker_download_chapter
    from worker.worker_download_picture import worker_download_picture
    # ! functions
    from functions.download_chapter import download_chapter
    from functions.download_picture import download_picture

    def __init__(self, mangalist: typing.List[typing.Dict[str, str]]):
        self.mangalist = [] # typing.List[Download]
        self.dchapters = [] # typing.List[Dchapter]
        for manga in mangalist:
            self.mangalist.append(Download(
                xid=manga[XID_]
            ))

    def run(self):
        # self.read()
        self.collect()
        self.download_launcher()
        # self.save()
