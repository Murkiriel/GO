#GO
Renewal of [@GO_Robot](https://telegram.me/GO_Robot)

##News

Telegram [@Murkiriel](http://telegram.me/Murkiriel)

##Setup

```bash
# Tested on Ubuntu Gnome 16.10
sudo ./php7_zts_pthreads.sh

git clone https://github.com/Murkiriel/GO.git
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

* * *

![PHP](http://blog.continued-learning.com/wp-content/uploads/2016/06/php7.jpg)
