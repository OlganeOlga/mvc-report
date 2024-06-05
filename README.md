# MVC-project

The project is a study-project for the cours MVC (Objektorienterade webbteknologier)
Main programming language PHP

Project is made on the basis of Symphony framework

## Authors
the project is made by Olga Egorova

# Scrutinizer

<div class="scrutiniser">
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/quality-score.png?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/coverage.png?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/build.png?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/code-intelligence.svg?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
</div>

## Installation
1. Prerequisites
To intsl Symphony and use this project you need:
PHP version higher than 8.2
Composer (for managing PHP dependencies)
Web server (Apache, Firwox)
2. Installation Steps
- copy the symfony-project from the projects dir mvc-report/:
<code>rsync -av mvc-report/* {path-to-your-dir}/</code>
- install requaires:
<code>composer create-project symfony/skeleton:"7.0.*" app</code>
<code>cd app</code>
<code>composer require webapp</code>
<code>composer require twig</code>
<code>composer require symfony/webpack-encore-bundle</code>
<code>npm install</code>

Now you can run the application locally from the directory pf the application: 
<code>php -S localhost:8888 -t public</code>

3. This project use requireties: 
- webapp
- twig
- symfony/webpack-encore-bundle
AND if you want to run test and othe fiatures:
- php-cs-fixer
- phpmd
- phpstan
- test-pack/ PHPunuit
- symfony/orm-pack
- symfony/maker-bundle

RUN npm istall to get the requireties


## Usage

You can navigate to all pages through links in the headers 


# Scrutinizer

<div class="scrutiniser">
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/quality-score.png?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/coverage.png?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/build.png?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
    <li>
        <a href='https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/?branch=godTest'</a>
            <img src="https://scrutinizer-ci.com/g/OlganeOlga/mvc-report/badges/code-intelligence.svg?b=godTest" alt="Scrutinizer Code Build">
        </a>
    </li>
</div>

commit d1bfd2270d33e3d0a898a820b687dd1353cfeea8 (HEAD -> master, tag: 3.0.0)
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Sun Apr 21 07:24:53 2024 +0200

    Kmom03. All game 21 works well

commit 59a57028948747925109c15d01bedc028d540f62
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Fri Apr 19 13:22:06 2024 +0200

    Kmom03. Gives card to the player, get bet from the player. give more cards untill player get mote than 20 points.

commit 01931dcd32966055c2a460d2e53df72eea40f844
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Thu Apr 18 21:12:21 2024 +0200

    Kmom03. Can shuffle desk and bank kan do set.

commit 6bfd2478c90959f81cac7c032988214dfc040158
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Thu Apr 18 16:37:16 2024 +0200

    Kmom03 can get Desk and give a card to Player.

commit 4e9dc9a7afb6cde2fc14def58a944204799d6f5a
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Thu Apr 18 08:49:00 2024 +0200

    Kmom03 first page and classes: Card, CardGraphics, Hand, Desk, Palyer, Bank.

commit 1ac438d9fb3c17b7fb9082087f1db1cb021ee6ab (tag: 2.0.0)
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Fri Apr 12 23:45:55 2024 +0200

    Kmom02 färdigt.

commit ca3eaaa3ebe38717d47eb2d04d5ad2109b20f1d3
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Fri Apr 12 23:42:28 2024 +0200

    Commit me sista json route 'dela-kort.

commit b33875acd87bc9dad7c64603a38c40a496deb9bc
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Fri Apr 12 21:42:15 2024 +0200

    Almoust all routes.

commit 2d8526ae1330fcc545af79cf7999e323107dcafe
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Fri Apr 12 08:52:25 2024 +0200

    Smal changes in the style after tagg 1.0.0. Cortplay for kmom02 i complete. Works with json.

commit 5772b0e1691de7000795e6fb371b8ab010f18475 (tag: 1.0.0, origin/master)
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Tue Apr 9 12:14:07 2024 +0200

    commiting for kmom01 while working on kmom02

commit 1ca247e4180f98fdb31ab970de766c698d7eadb8
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Mon Apr 1 09:11:25 2024 +0200

    Start with CardPlay, router /card, name card_play

commit b760facfe29d034e090b836d9ad512b568e95118
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Fri Mar 29 21:11:40 2024 +0100

    Kmom01 report sida var1

commit 453d727e09958ab3dcc1642e62c339791f5340d7
Author: OlganeOlga <o_yegorovar@yahoo.com>
Date:   Thu Mar 28 10:00:41 2024 +0100

    First commit
