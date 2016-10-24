#GO
Renewal of [@GO_Robot](https://telegram.me/GO_Robot)

##Support

Telegram [@Murkiriel](http://telegram.me/Murkiriel)

##Setup

```bash
# Tested on Ubuntu Gnome 16.10
sudo apt-get install php7.0 php7.0-fpm php7.0-curl

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

![PHP](https://secure.php.net/images/logo.php)
