import requests
from urllib.parse import urljoin as uj
from pyquery import PyQuery as pq

from value.strings import *
from utils.common.log import le, lw, ln
from utils.common.retitle import retitle
from object.downloaded import Downloaded
from mongo.exist_by_xid import exist_by_xid
from mongo.get_downloaded_by_xid import get_downloaded_by_xid
from mongo.update_downloaded_by_xid import update_chapter_nums_chids
from mongo.insert_downloaded import insert_downloaded

def collect(self):
    #  process all the manga 
    for manga in self.mangalist: # object.download.Download
        main_req = requests.get(uj(xmanhua_root_url, manga.xid))
        if main_req.status_code != 200:
            le(f'error while requesting {manga.xid} failed')
            return
        main_doc = pq(main_req.text)

        #
        chapterlist = main_doc('#chapterlistload a')
        chapterlist.reverse()
        chapter_nums = len(chapterlist)

        chids = []
        for chapter in chapterlist:
            chids.append(chapter.attrib['href'].replace('/', ''))

        # xid exist or not
        if exist_by_xid(manga.xid):
            ln(f'manga: xid: {manga.xid} already downloaded')
            if get_downloaded_by_xid(manga.xid).chapter_nums != chapter_nums:
                # update new chids and chapter_nums to the database
                update_chapter_nums_chids(manga.xid, chapter_nums, chids)
                ln(f'manga: xid: {manga.xid} chapter chids updated newly')
        else: # not exist, get more info to append
            title = retitle(main_doc('.detail-info-title').text())
            ln(f'manga title: {title}')
            cover = main_doc('.detail-info-cover').attr('src')
            ln(f'manga cover url: {cover}') 
            author = main_doc('.detail-info-tip span a').text()
            ln(f'manga author: {author}')
            status = main_doc('.detail-info-tip span span:not([class])').text()
            ln(f'manga status: {status}')
            type = [ span.text for span in main_doc('.detail-info-tip span.item')]
            ln(f'manga type: {" ".join(type)}')
            description = main_doc('.detail-info-content').text().replace('[+展開]', '').replace('[-折疊]', '')
            ln(f'manga description: {description}')

            insert_downloaded(downloaded=Downloaded(
                title=title,
                xid=manga.xid,
                author=author,
                status=status,
                cover=cover,
                type=type,
                chapter_nums=chapter_nums,
                chapters=[],
                chids=chids,
                dd_chids=[], # new manga no download
                description=description,
            ))
