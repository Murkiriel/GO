#GO
Renewal of [@GO_Robot](https://telegram.me/GO_Robot)

##News

Telegram [@Murkiriel](http://telegram.me/Murkiriel)

##Setup

```bash
# Tested on Ubuntu Gnome 16.10
git clone https://github.com/Murkiriel/GO.git

cd GO

sudo ./php7_zts_pthreads.sh
```

**Before you do anything, open config.php in a text editor and make the following changes:**

> • Set $bot to the authentication token you received from the [@BotFather](https://telegram.me/BotFather).
>
> • Set RAIZ to the bot installation folder.
>
> • Use [@GO_Robot](https://telegram.me/GO_Robot) to find your telegram ID and add it to sudo list.
>
> • Set sudo as your Telegram ID.

To start the bot, run `./iniciar.sh`. To stop the bot, press Ctrl+C twice.

You may also start the bot with `php bot.php`, but then it will not restart automatically.

```bash
# To run a nohup background process
nohup ./iniciar.sh &
```

##Credits

[krakjoe](https://github.com/krakjoe), for [pthreads](https://github.com/krakjoe/pthreads)

[emiglobetrotting](https://github.com/emiglobetrotting), for [php7_zts_pthreads.sh](https://gist.github.com/emiglobetrotting/4663ffc4484e9384a261)

* * *

![PHP](https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTBH_4vDQM_B15zUpwJevkIp8aIFO6cHR54qrztVCCMAFd1os05)
