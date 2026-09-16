<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TsuErrorHandlerService
{
    /**
     * Parse exception message to extract TSU error code and user message.
     */
    private static function parseError(Exception $e, string $defaultErrorCode, string $defaultUserMsg): array
    {
        $rawMessage = $e->getMessage();
        $errorCode  = $defaultErrorCode;
        $userMsg    = $defaultUserMsg;

        if (preg_match('/\[TSU_.*?\]/', $rawMessage, $matches)) {
            $errorCode = $matches[0];
            $cleanMsg = trim(str_replace($errorCode, '', $rawMessage));
            if (!empty($cleanMsg)) {
                $userMsg = $cleanMsg;
            }
        }

        return [$errorCode, $userMsg, $rawMessage];
    }

    /**
     * Handle error and return a redirect response with an HTML error message.
     */
    public static function handleHtml(Exception $e, string $defaultErrorCode, string $defaultUserMsg, string $logPrefix = '', Request $request = null)
    {
        list($errorCode, $userMsg, $rawMessage) = self::parseError($e, $defaultErrorCode, $defaultUserMsg);

        $logMsg = $logPrefix ? "$errorCode $logPrefix" : "$errorCode Gagal memproses permintaan.";
        
        Log::error($logMsg, [
            'original_error' => $rawMessage,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        $finalErrorMsg = "<div class='text-center'>
                            <h4 class='text-bold text-danger mb-2'>$errorCode</h4>
                            <p class='mb-2 text-bold' style='font-size: 1.1em;'>$userMsg</p>
                            <p class='text-muted small mb-0'>Silakan screenshot pesan ini dan laporkan ke PIKDI jika masalah berlanjut.</p>
                          </div>";

        $response = back()->with('error', $finalErrorMsg);
        
        if ($request) {
            $response = $response->withInput($request->all());
        }

        return $response;
    }

    /**
     * Handle error and return a JSON response (used by APIs and AJAX).
     */
    public static function handleJson(Exception $e, string $defaultErrorCode, string $defaultUserMsg, string $logPrefix = '')
    {
        list($errorCode, $userMsg, $rawMessage) = self::parseError($e, $defaultErrorCode, $defaultUserMsg);

        $logMsg = $logPrefix ? "$errorCode $logPrefix" : "$errorCode Gagal memproses permintaan AJAX.";
        
        Log::error($logMsg, [
            'original_error' => $rawMessage,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return response()->json([
            'success' => false,
            'message' => $userMsg,
            'data'    => null,
            'errors'  => [
                'code' => $errorCode
            ]
        ], 500);
    }
}
