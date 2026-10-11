<?php
/* =========================================================
   M-Pesa Daraja — STK Push service (with i18n + safe fallback)
   ========================================================= */

require_once __DIR__ . '/config.php';   // session + lang + t() + helpers
require_once __DIR__ . '/env.php';

class Mpesa
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $callbackUrl;
    private string $baseUrl;
    private bool   $credentialsReady;

    public function __construct()
    {
        $this->consumerKey    = getenv('MPESA_CONSUMER_KEY')    ?: '';
        $this->consumerSecret = getenv('MPESA_CONSUMER_SECRET') ?: '';
        $this->shortcode      = getenv('MPESA_SHORTCODE')       ?: '174379';
        $this->passkey        = getenv('MPESA_PASSKEY')         ?: '';
        $this->callbackUrl    = getenv('MPESA_CALLBACK_URL')    ?: '';

        $env = getenv('MPESA_ENV') ?: 'sandbox';
        $this->baseUrl = $env === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';

        $this->credentialsReady =
            $this->consumerKey !== ''
            && $this->consumerSecret !== ''
            && $this->passkey !== ''
            && $this->callbackUrl !== '';
    }

    /* ---------------------------------------------------------
       Get OAuth access token (cached for its lifetime)
       --------------------------------------------------------- */
    public function token(): string
    {
        if (!empty($_SESSION['mpesa_token']) && $_SESSION['mpesa_token_exp'] > time() + 30) {
            return $_SESSION['mpesa_token'];
        }

        $ch = curl_init("$this->baseUrl/oauth/v1/generate?grant_type=client_credentials");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => "{$this->consumerKey}:{$this->consumerSecret}",
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_TIMEOUT        => 20,
        ]);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException(t('mpesa_net_error') . ': ' . $err);
        }

        $data = json_decode($res, true);
        if (empty($data['access_token'])) {
            $msg = $data['errorMessage']
                ?? $data['error_description']
                ?? t('mpesa_auth_failed');
            throw new RuntimeException($msg);
        }

        $_SESSION['mpesa_token']     = $data['access_token'];
        $_SESSION['mpesa_token_exp'] = time() + (int)($data['expires_in'] ?? 3599);
        return $data['access_token'];
    }

    /* ---------------------------------------------------------
       Push STK to customer phone
       --------------------------------------------------------- */
    public function stkPush(
        string $phone,
        int    $amount,
        string $reference,
        string $description = 'GlowBeauty order'
    ): array {
        // If credentials are missing, return a simulated response
        // so the checkout flow doesn't break during development.
        if (!$this->credentialsReady) {
            return [
                'CheckoutRequestID'   => 'SIM-' . strtoupper(bin2hex(random_bytes(6))),
                'ResponseCode'        => '0',
                'ResponseDescription' => t('mpesa_simulated'),
                'simulated'           => true,
            ];
        }

        $phone     = $this->normalizePhone($phone);
        $timestamp = date('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $phone,
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => $reference,
            'TransactionDesc'   => $description,
        ];

        $ch = curl_init("$this->baseUrl/mpesa/stkpush/v1/processrequest");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->token(),
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT        => 30,
        ]);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException(t('mpesa_net_error') . ': ' . $err);
        }

        $data = json_decode($res, true);
        if (!is_array($data)) {
            throw new RuntimeException(t('mpesa_bad_response'));
        }

        // Surface Safaricom's actual error message if the request failed
        if (($data['ResponseCode'] ?? '1') !== '0') {
            $msg = $data['errorMessage']
                ?? $data['ResponseDescription']
                ?? t('mpesa_push_failed');
            throw new RuntimeException($msg);
        }

        return $data;
    }

    /* ---------------------------------------------------------
       Normalise Kenyan phone: 07XXXXXXXX → 2547XXXXXXXX
       --------------------------------------------------------- */
    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($phone, '0'))   $phone = '254' . substr($phone, 1);
        if (str_starts_with($phone, '7') || str_starts_with($phone, '1')) $phone = '254' . $phone;
        return $phone;
    }
}