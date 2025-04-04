<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GraphService
{
    public static function getAccessToken()
    {
        $response = Http::asForm()->post(config('services.msgraph.auth_url') . '/' . config('services.msgraph.tenant_id') . '/oauth2/v2.0/token', [
            'client_id' => config('services.msgraph.client_id'),
            'client_secret' => config('services.msgraph.client_secret'),
            'scope' => config('services.msgraph.scope'),
            'grant_type' => 'client_credentials',
        ]);

        return $response->json()['access_token'] ?? null;
    }

    public static function getFileContentFromShareUrl(string $shareUrl)
    {
        $accessToken = self::getAccessToken();

        if (!$accessToken) {
            return 'アクセストークンが取得できませんでした';
        }

        // 共有リンクをBase64でエンコード（Graph API仕様）
        $encodedUrl = rtrim(strtr(base64_encode($shareUrl), '+/', '-_'), '=');

        $response = Http::withToken($accessToken)
            ->get("https://graph.microsoft.com/v1.0/shares/u!{$encodedUrl}/driveItem/content");

        if ($response->failed()) {
            return 'ファイル取得に失敗しました：' . $response->status();
        }

        return $response->body(); // ファイルの中身（バイナリまたはテキスト）
    }
}