<?php
/**
 * Currency Conversion Helper
 * Uses ExchangeRate-API for currency conversion
 */

class CurrencyConverter {
    private $apiKey = 'YOUR_API_KEY_HERE'; // Get free key from https://www.exchangerate-api.com/
    private $baseUrl = 'https://api.exchangerate-api.com/v4/latest/';
    private $baseCurrency = 'LKR'; // Sri Lankan Rupee as base
    private $cacheFile = __DIR__ . '/../cache/exchange_rates.json';
    private $cacheTime = 86400; // 24 hours
    
    public function __construct() {
        // Create cache directory if it doesn't exist
        $cacheDir = dirname($this->cacheFile);
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
    }
    
    /**
     * Get exchange rates from cache or API
     */
    private function getExchangeRates() {
        // Check cache first
        if (file_exists($this->cacheFile)) {
            $cacheData = json_decode(file_get_contents($this->cacheFile), true);
            if (isset($cacheData['timestamp']) && (time() - $cacheData['timestamp']) < $this->cacheTime) {
                return $cacheData['rates'];
            }
        }
        
        // Fetch from API
        try {
            $response = @file_get_contents($this->baseUrl . $this->baseCurrency);
            if ($response === false) {
                // Return cached data even if expired
                if (isset($cacheData['rates'])) {
                    return $cacheData['rates'];
                }
                return null;
            }
            
            $data = json_decode($response, true);
            if (isset($data['rates'])) {
                // Cache the rates
                $cacheData = [
                    'timestamp' => time(),
                    'rates' => $data['rates']
                ];
                file_put_contents($this->cacheFile, json_encode($cacheData));
                return $data['rates'];
            }
        } catch (Exception $e) {
            error_log("Currency API Error: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Convert amount from LKR to target currency
     */
    public function convert($amount, $toCurrency = 'USD') {
        if ($toCurrency === 'LKR') {
            return $amount;
        }
        
        $rates = $this->getExchangeRates();
        if (!$rates || !isset($rates[$toCurrency])) {
            return $amount; // Return original if conversion fails
        }
        
        return round($amount * $rates[$toCurrency], 2);
    }
    
    /**
     * Format price with currency symbol
     */
    public function formatPrice($amount, $currency = 'LKR') {
        $symbols = [
            'LKR' => 'Rs',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£'
        ];
        
        $symbol = $symbols[$currency] ?? $currency;
        $convertedAmount = $this->convert($amount, $currency);
        
        return $symbol . number_format($convertedAmount, 2);
    }
    
    /**
     * Get selected currency from session
     */
    public static function getSelectedCurrency() {
        return $_SESSION['currency'] ?? 'LKR';
    }
    
    /**
     * Set selected currency in session
     */
    public static function setSelectedCurrency($currency) {
        $allowedCurrencies = ['LKR', 'USD', 'EUR', 'GBP'];
        if (in_array($currency, $allowedCurrencies)) {
            $_SESSION['currency'] = $currency;
            return true;
        }
        return false;
    }
}
?>
