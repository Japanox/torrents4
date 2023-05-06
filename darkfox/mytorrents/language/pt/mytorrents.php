<?php

/**
*
* myTorrents extension for the phpBB Forum Software package.
* Portuguese translation by DarkFox
* Brazilian Portuguese translation by DarkFox
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
    'MYTORRENT_SEEDERS' => 'Seeders',
    'MYTORRENT_LEECHERS' => 'Leechers',
    'MYTORRENT_SIZE' => 'Tamanho',
    'MYTORRENT_FILES' => 'Arquivos',
));
