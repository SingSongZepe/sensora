from dataclasses import dataclass

@dataclass
class Chapter:
    title: str
    # pictures: typing.List[Picture] # it is unnecessary to record the pictures
    chid: str
    index: int
    pages: int # total page of the chapter
