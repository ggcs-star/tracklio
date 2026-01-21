<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BioLinkBuilder
{
    public static function build(Request $request): array
    {
        $linksInput = $request->input('links', []);

        if (is_string($linksInput)) {
            $linksInput = json_decode($linksInput, true) ?? [];
        }

        $finalLinks = [];

        foreach ($linksInput as $key => $item) {

            if (($item['deleted'] ?? '0') === '1') {
                continue;
            }

            $type = $item['type'] ?? '';

            if ($type === 'tagline' && !empty($item['text'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'tagline',
                    'text' => $item['text'],
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'link' && !empty($item['text']) && !empty($item['url'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'link',
                    'text' => $item['text'],
                    'url' => $item['url'],
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'heading' && !empty($item['text'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'heading',
                    'text' => $item['text'],
                    'style' => $item['style'] ?? 'h5',
                    'color' => $item['color'] ?? '#000000',
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'text' && !empty($item['text'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'text',
                    'text' => $item['text'],
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'divider') {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'divider',
                    'style' => $item['style'] ?? 'solid',
                    'height' => (int) ($item['height'] ?? 1),
                    'color' => $item['color'] ?? '#000000',
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'html' && !empty($item['text'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'html',
                    'text' => $item['text'],
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'image') {
                $filePath = $item['file'] ?? null;

                if ($request->hasFile("links.$key.file")) {
                    $filePath = $request->file("links.$key.file")
                        ->store('bio/images', 'public');
                }

                if ($filePath) {
                    $finalLinks[] = [
                        'id' => $item['id'] ?? (string) Str::uuid(),
                        'type' => 'image',
                        'file' => $filePath,
                        'url' => $item['url'] ?? null,
                        'enabled' => ($item['enabled'] ?? '1') === '1',
                    ];
                }
            }

     if (
    in_array($type, ['phone_call', 'whatsapp_call', 'whatsapp_message'], true)
    && !empty($item['phone'])
) {
    $data = [
        'id' => $item['id'] ?? (string) Str::uuid(),
        'type' => $type,
        'phone' => $item['phone'],
        'enabled' => ($item['enabled'] ?? '1') === '1',
    ];

    if ($type === 'phone_call') {
        $data['label'] = $item['label'] ?? 'Call us';
    }

    if ($type === 'whatsapp_call') {
        $data['label'] = $item['label'] ?? 'Call on WhatsApp';
    }

    if ($type === 'whatsapp_message') {
        $data['message'] = $item['message'] ?? '';
        $data['label'] = $item['label'] ?? 'Message on WhatsApp';
    }

    $finalLinks[] = $data;
}


         if ($type === 'video' || $type === 'audio' || $type === 'pdf') {

    $filePath = $item['file'] ?? null;

    if ($request->hasFile("links.$key.file")) {

        if ($type === 'video') {
            $filePath = $request->file("links.$key.file")
                ->store('bio/videos', 'public');
        }

        if ($type === 'audio') {
            $filePath = $request->file("links.$key.file")
                ->store('bio/audio', 'public');
        }

        if ($type === 'pdf') {
            $filePath = $request->file("links.$key.file")
                ->store('bio/pdfs', 'public');
        }
    }

    if ($filePath) {
        $data = [
            'id' => $item['id'] ?? (string) Str::uuid(),
            'type' => $type,
            'file' => $filePath,
            'enabled' => ($item['enabled'] ?? '1') === '1',
        ];

        if ($type === 'video') {
            $data['url'] = $item['url'] ?? null;
        }

        if ($type === 'pdf') {
            $data['title'] = $item['title'] ?? 'View PDF';
        }

        $finalLinks[] = $data;
    }
}


         if (in_array($type, ['youtube', 'spotify', 'instagram'], true) && !empty($item['url'])) {
    $finalLinks[] = [
        'id' => $item['id'] ?? (string) Str::uuid(),
        'type' => $type,
        'url' => $item['url'],
        'enabled' => ($item['enabled'] ?? '1') === '1',
    ];
}


            if ($type === 'maps' && !empty($item['address'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'maps',
                    'address' => $item['address'],
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'faq' && !empty($item['question']) && !empty($item['answer'])) {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'faq',
                    'question' => $item['question'],
                    'answer' => $item['answer'],
                    'enabled' => ($item['enabled'] ?? '1') === '1',
                ];
            }

            if ($type === 'contact_form') {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'contact_form',
                    'text' => $item['text'] ?? 'Contact',
                    'disclaimer' => $item['disclaimer'] ?? null,
                    'enabled' => true,
                ];
            }

            if ($type === 'newsletter') {
                $finalLinks[] = [
                    'id' => $item['id'] ?? (string) Str::uuid(),
                    'type' => 'newsletter',
                    'text' => $item['text'] ?? 'Subscribe',
                    'description' => $item['description'] ?? null,
                    'disclaimer' => $item['disclaimer'] ?? null,
                    'enabled' => true,
                ];
            }
        }

        return $finalLinks;
    }
}
