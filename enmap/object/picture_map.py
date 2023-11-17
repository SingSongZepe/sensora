from dataclasses import dataclass
import typing

@dataclass
class Picture_Map:
    title: str # picture title / file name
    index: str
    aes_encoding: str # AES encrypted and then base64 encoding 
