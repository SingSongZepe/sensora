# A Web project of SingSongZepe
__but not just a web project__
__this project will be deployed to linux server at SingSongZepe__

#### What is needed for the server in your linux

- python 3.11(or 3.10 or other version) and its all dependencies as listed below
- php (dynamic html file)
- go (to compile the server go file into executable file that can be running on linux)
- nginx server, and redirect request to the port of 8080, on which port the go server receives requests
- mongodb server running on port 27017

#### Python Dependencies
- pymongo
- Crypto   ??
- cryptography  ??
- base64
- json
- smtplib
- email
- requests
- re
- execjs    pip install PyExecJS ??
- pyquery
- urllib
- typing
- threading
- dataclasses
- shutil
- pandas

#### Project Preview
This project can be divided into several parts
- python requests, download mangas (picture) from [XMANHUA](https://www.xmanhua.com)
- python os pymongo, construct them in mongodb (rust version, But I haven't implement it yet)
- python pymongo, map them in mongodb
- go server, handling all HTTP requests from users
- php html javascript css vue jquery, constructing frontend and interact with the backend (go server, don't ask me why don't use springboot as backend).


__attempt to test in your linux whether those modules of python work__

if you want to know more how to run it in your server
contact me, and provide new idea for me.

