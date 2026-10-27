<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Thin client for the ToyyibPay bill API.
 *
 * @see https://toyyibpay.com/apireference/
 */
class ToyyibPay
{
    public const STATUS_SUCCESS = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_FAILED = 3;

    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $secretKey,
        private readonly ?string $categoryCode,
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            rtrim((string) config('services.toyyibpay.url', 'https://dev.toyyibpay.com'), '/'),
            config('services.toyyibpay.secret'),
            config('services.toyyibpay.category'),
        );
    }

    /**
     * Create a bill and return its bill code.
     *
     * @throws RuntimeException when the gateway rejects the request
     */
    public function createBill(User $payer, float $amount, string $name, string $reference, string $phone, string $returnUrl, string $callbackUrl): string
    {
        $response = Http::asForm()->timeout(30)->post($this->baseUrl . '/index.php/api/createBill', [
            'userSecretKey' => $this->secretKey,
            'categoryCode' => $this->categoryCode,
            'billName' => mb_substr($name, 0, 30),
            'billDescription' => 'UiTMarketplace ' . $reference,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => (int) round($amount * 100), // in sen
            'billReturnUrl' => $returnUrl,
            'billCallbackUrl' => $callbackUrl,
            'billExternalReferenceNo' => $reference,
            'billTo' => $payer->name,
            'billEmail' => $payer->email,
            'billPhone' => self::normalisePhone($phone),
        ]);

        $billCode = $response->successful() ? ($response->json('0.BillCode') ?? null) : null;

        if (! $billCode) {
            logger()->error('ToyyibPay createBill failed', ['status' => $response->status(), 'body' => $response->body()]);

            throw new RuntimeException('Unable to create payment bill.');
        }

        return $billCode;
    }

    public function paymentUrl(string $billCode): string
    {
        return $this->baseUrl . '/' . $billCode;
    }

    /**
     * Ask ToyyibPay (not the browser or callback payload) what happened to a bill.
     *
     * @return array{status:int, amount:float, reference:?string}
     */
    public function billStatus(string $billCode): array
    {
        $response = Http::asForm()->timeout(30)->post($this->baseUrl . '/index.php/api/getBillTransactions', [
            'billCode' => $billCode,
        ]);

        $transactions = $response->successful() ? $response->json() : null;

        if (! is_array($transactions) || $transactions === []) {
            return ['status' => self::STATUS_PENDING, 'amount' => 0.0, 'reference' => null];
        }

        // A bill can have several attempts; a single success settles it.
        $paid = collect($transactions)->first(fn ($t) => (int) ($t['billpaymentStatus'] ?? 0) === self::STATUS_SUCCESS);
        $latest = $paid ?? end($transactions);

        return [
            'status' => (int) ($latest['billpaymentStatus'] ?? self::STATUS_PENDING),
            'amount' => (float) ($latest['billpaymentAmount'] ?? 0),
            'reference' => $latest['billpaymentInvoiceNo'] ?? null,
        ];
    }

    /** ToyyibPay wants a Malaysian number in 60XXXXXXXXX form. */
    public static function normalisePhone(?string $phone): string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '6' . $digits;
        }

        return strlen($digits) >= 10 ? $digits : '60100000000';
    }
}
