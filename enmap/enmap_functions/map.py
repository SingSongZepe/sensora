from cryptography.fernet import Fernet
from os.path import join as j
import typing

from mongo.exist_by_xid import exist_map_by_xid
from mongo.get_all_manga_info import get_all_manga_info
from mongo.insert_manga_map import insert_manga_map
from object.manga_info import Manga_Info
from object.manga_map import Manga_Map
from object.chapter_map import Chapter_Map
from object.picture_map import Picture_Map
from utils.common.path_constructor import picture_namer
from utils.encrypt.AES_encryptor import load_fernet, encrypt_file_name
from utils.common.log import lw, ln

def map(self, mangas_info: typing.List[Manga_Info]) -> typing.List[Manga_Map]:
    fernet: Fernet = load_fernet()
    mangas_map = []
    for manga_info in mangas_info:
        if exist_map_by_xid(manga_info.xid):
            lw('manga map already exists')
            continue
        chapters_map = []
        for chapter_info in manga_info.chapters_info:
            # get all picture_map
            pictures_map = [] # 
            for idx in range(chapter_info.pages):
                pictures_map.append(Picture_Map(
                    title=picture_namer(idx + 1),
                    index=idx,
                    aes_encoding=f'{encrypt_file_name(file_name=f"{j(manga_info.xid, chapter_info.chid, picture_namer(idx + 1))}", fernet=fernet)}.jpg'
                ))
            chapters_map.append(Chapter_Map(
                title=chapter_info.title,
                chid=chapter_info.chid,
                pictures_map=pictures_map,
            ))
        # insert the manga_map to 
        manga_map = Manga_Map(
            title=manga_info.title,
            xid=manga_info.xid,
            chapters_map=chapters_map,
        )
        insert_manga_map(manga_map)
        ln(f'insert manga map successfully, manga xid: {manga_map.xid}')
        mangas_map.append(manga_map)
    return mangas_map


