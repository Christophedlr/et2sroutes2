<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\BBCode;


class BBCodeParser
{
    public function parser(string $text): string
    {
        $oldText = $text;

        $newText = preg_replace('#\[b\](.+)\[\/b\]#Ui', '<b>$1</b>', $oldText);
        $newText = preg_replace('#\[i\](.+)\[\/i\]#Ui', '<i>$1</i>', $newText);
        $newText = preg_replace('#\[u\](.+)\[\/u\]#Ui', '<u>$1</u>', $newText);
        $newText = preg_replace('#\[s\](.+)\[\/s\]#Ui', '<s>$1</s>', $newText);

        $newText = preg_replace(
            '#\[img\](.+)\[\/img\]#Ui',
            '<img src="$1" class="img-fluid"></img>',
            $newText
        );

        $newText = preg_replace('#\[url\](.+)\[\/url\]#Ui', '<a href="$1">$1</a>', $newText);
        $newText = preg_replace('#\[url=(.+)\](.+)\[\/url\]#Ui', '<a href="$1">$2</a>', $newText);
        $newText = preg_replace('#\[p\](.+)\[\/p\]#Ui', '<p>$1</p>', $newText);
        $newText = preg_replace('#\[color=(.+)\](.+)\[\/color\]#Ui', '<span style="color: $1">$2</span>', $newText);

        $newText = preg_replace(
            '#\[list\](.+)\[\/list\]#Usi',
            '<ul>$1</ul>',
            $newText
        );

        $newText = preg_replace(
            '#\[list=([1|A|a|I|i])\](.+)\[\/list\]#Usi',
            '<ol type="$1">$2</ol>',
            $newText
        );

        $newText = preg_replace('#\[\*\](.+)#i','<li>$1</li>', $newText);

        $newText = preg_replace(
            '#\[left\](.+)\[\/left\]#i',
            '<div style="text-align: left">$1</div>',
            $newText);

        $newText = preg_replace(
            '#\[right\](.+)\[\/right\]#i',
            '<div style="text-align: right">$1</div>',
            $newText);

        $newText = preg_replace(
            '#\[center\](.+)\[\/center\]#i',
            '<div style="text-align: center">$1</div>',
            $newText);

        $newText = preg_replace(
            '#\[justify\](.+)\[\/justify\]#i',
            '<div style="text-align: justify">$1</div>',
            $newText);

        $newText = preg_replace(
            '#\[quote\](.+)\[\/quote\]#i',
            '<blockquote>$1</blockquote>',
            $newText);

        $newText = preg_replace(
            '#\[quote=(.+)\](.+)\[\/quote\]#i',
            '<blockquote><cite>$1 dit :</cite><br />$2</blockquote>',
            $newText);

        return $newText;
    }
}
