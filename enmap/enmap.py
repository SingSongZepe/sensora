
from utils.common.log import *

class ENMAP:
    # ! enmap_functions
    from enmap_functions.info import info
    from enmap_functions.map import map
    from enmap_functions.rename import rename

    def __init__(self):
        pass

    def run(self):
        if self.rename(self.map(self.info())):
            ln('successfully map mangas')
            return
        le('error occurred while mapping mangas')
