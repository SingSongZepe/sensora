from mongo.mongo import *
import pandas as pd

from value.strings import *
from utils.log import *

class TEXTUAL:
    def __init__(self):
        self.file = TEXTUAL_FILE

    def run(self):
        df = pd.read_excel(self.file, engine='openpyxl')
        for index, row in df.iterrows():
            filter = {
                TEXTUAL_: row[TEXTUAL_],
            }
            textual = {
                TEXTUAL_: row[TEXTUAL_],
                AUTHOR_: row[AUTHOR_],
                TIME_: row[TIME_],
            }
            if not coll_textual.find_one(filter):
                coll_textual.insert_one(textual)
                ln('textual append to the database: ' + textual.__str__())
            else:
                lw('maybe the textual already append to the database: ' + textual.__str__())
