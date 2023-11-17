from dataclasses import dataclass

@dataclass
class Chapter_Info:
    title: str
    # pictures: typing.List[Picture] # it is unnecessary to record the pictures
    chid: str
    index: int
    pages: int
    