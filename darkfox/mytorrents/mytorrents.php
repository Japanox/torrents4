<?php

namespace darkfox\mytorrents\event;

use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class mytorrents implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            'core.posting_modify_template_vars' => 'posting_modify_template_vars',
        );
    }

    public function posting_modify_template_vars($event)
    {
        $template_vars = $event['template_vars'];
        $post_data = $template_vars['POST_DATA'];

        if (preg_match('/\[mytorrent\](.*?)\[\/mytorrent\]/i', $post_data['message'], $matches)) {
            $magnetlink = $matches[1];

            // Obter informações do magnetlink
            $torrent_info = $this->getTorrentInfo($magnetlink);

            // Adicionar informações do torrent ao post
            $template_vars['MYTORRENT_SEEDERS'] = $torrent_info['seeders'];
            $template_vars['MYTORRENT_LEECHERS'] = $torrent_info['leechers'];
            $template_vars['MYTORRENT_SIZE'] = $torrent_info['size'];
            $template_vars['MYTORRENT_FILES'] = $torrent_info['files'];
        }
    }

    private function getTorrentInfo($magnetlink)
    {
        // Configurar o cliente HTTP
        $client = new Client([
            'base_uri' => 'https://api.1377x.to/',
            'timeout' => 5.0,
        ]);

        // Fazer a solicitação HTTP para obter as informações do torrent
        $response = $client->request('GET', 'magnet', [
            'query' => [
                'info_hash' => urlencode($magnetlink),
                'with_peers' => '1',
                'with_files' => '1',
            ],
        ]);

        // Analisar a resposta JSON
        $data = json_decode($response->getBody(), true);

        // Obter informações do torrent
        $torrent_info = array(
            'seeders' => $data['peers']['seeds'],
            'leechers' => $data['peers']['leeches'],
            'size' => $data['info']['size'],
            'files' => array(),
        );

        foreach ($data['info']['files'] as $file) {
            $torrent_info['files'][] = $file['name'];
        }

        return $torrent_info;
    }
}
