import os
from os.path import join as j
import typing
import shutil

from object.manga_map import Manga_Map
from value.strings import *
from utils.common.log import *

def rename(self, mangas_map: typing.List[Manga_Map]) -> bool:
    # confirm path exist
    if not create_path(mdp):
        return
    for manga_map in mangas_map:
        mdp_manga_path = j(mdp, manga_map.xid)
        create_path(mdp_manga_path)
        for chapter_map in manga_map.chapters_map:
            mdp_chapter_path = j(mdp_manga_path, chapter_map.chid)
            create_path(mdp_chapter_path)
            for picture_map in chapter_map.pictures_map:
                from_file = j(dp, manga_map.xid, chapter_map.chid, picture_map.title)
                to_file = j(mdp_chapter_path, picture_map.aes_encoding)
                coname(from_file, to_file)
    return True

def create_path(path) -> bool:
    if not os.path.exists(path):
        lw(f'path {path} lost, attempting to create it')
        try:
            os.mkdir(path)
            return True
        except PermissionError:
            le('not permission to create dir')
            return False
        except Exception as e:
            le(e)
            return False
    return True

# copy + rename
def coname(from_file, to_file):
    to_dir, to_name = os.path.split(to_file)
    from_dir, from_name = os.path.split(from_file)
    try:
        shutil.copy2(from_file, to_dir)
        new_file = j(to_dir, from_name)
        os.rename(new_file, to_file)
    except PermissionError:
        raise PermissionError(f'while rename copy and rename file, from file: {from_file} and to_file: {to_file}, failed by permission denied')
    except FileExistsError:
        lw(f'while rename copy and rename files, path: {to_file} exists')
    except FileNotFoundError:
        lw(f'while rename copy and rename files, path: {to_file} failed, maybe file is already copied')

# rename (move)
# if you think that your computer has not much memory,
# then use can just move those file to new dir
def move(from_file, to_file):
    try:
        os.rename(from_file, to_file)
    except PermissionError:
        raise PermissionError(f'while move files, from file: {from_file} and to_file: {to_file}, failed by permission denied')
    except FileExistsError:
        lw(f'while move files, path: {to_file} exists')
    except FileNotFoundError:
        lw(f'while move files, path: {to_file} failed, maybe file is already moved')
