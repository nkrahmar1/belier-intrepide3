<?php

namespace App\Services;

use CinetPay\CinetPay;
use Exception;

class CinetPayService
{
    private $apiKey;
    private $siteId;
    private $mode;
    private $notifyUrl;
    private $returnUrl;
    private $cancelUrl;

    public function __construct()
    {
        $this->apiKey = env('CINETPAY_API_KEY');
        $this->siteId = env('CINETPAY_SITE_ID');
        $this->mode = env('CINETPAY_MODE', 'test'); // test ou live
        $this->notifyUrl = env('CINETPAY_NOTIFY_URL');
        $this->returnUrl = env('CINETPAY_RETURN_URL');
        $this->cancelUrl = env('CINETPAY_CANCEL_URL');
    }

    /**
     * Initier un paiement CinetPay (Version Legacy)
     */
    public function initiatePayment($transactionId, $amount, $currency, $description, $customerName, $customerEmail, $customerPhone, $paymentMethod)
    {
        try {
            $CinetPay = new CinetPay($this->siteId, $this->apiKey);
            
            // Configuration de la transaction pour la version legacy
            $CinetPay->setTransId($transactionId)
                    ->setAmount($amount)
                    ->setCurrency($currency)
                    ->setDesignation($description)
                    ->setCustom($customerName . '|' . $customerEmail)
                    ->setNotifyUrl($this->notifyUrl)
                    ->setReturnUrl($this->returnUrl)
                    ->setCancelUrl($this->cancelUrl)
                    ->setTransDate(date('Y-m-d H:i:s'));

            // Configuration pour mobile money si spécifié
            if (in_array($paymentMethod, ['ORANGE_MONEY_CI', 'MTN_MONEY_CI', 'MOOV_MONEY_CI'])) {
                // Extraire le numéro de téléphone pour mobile money
                $phone = preg_replace('/[^0-9]/', '', $customerPhone);
                if (strlen($phone) >= 8) {
                    $CinetPay->setCelPhoneNum(substr($phone, -8))
                            ->setPhonePrefixe('+225');
                }
            }

            if ($this->mode === 'test') {
                $CinetPay->setDebug(true);
            }

            // Générer le formulaire de paiement
            $paymentButton = $CinetPay->getPayButton('cinetpay_form', 1, 'large');
            
            // Extraire l'URL de paiement du formulaire généré
            preg_match('/action=\'([^\']+)\'/', $paymentButton, $matches);
            $paymentUrl = isset($matches[1]) ? $matches[1] : null;
            
            return [
                'success' => true,
                'payment_url' => $paymentUrl,
                'payment_form' => $paymentButton,
                'transaction_id' => $transactionId
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier le statut d'une transaction (Version Legacy)
     */
    public function checkTransactionStatus($transactionId)
    {
        try {
            $CinetPay = new CinetPay($this->siteId, $this->apiKey);
            $CinetPay->setTransId($transactionId);
            
            if ($this->mode === 'test') {
                $CinetPay->setDebug(true);
            }

            $result = $CinetPay->getPayStatus();

            if (isset($result['status']) && $result['status'] == '00') {
                return [
                    'success' => true,
                    'status' => 'ACCEPTED',
                    'amount' => $result['amount'] ?? null,
                    'currency' => $result['currency'] ?? null,
                    'operator_id' => $result['operator_id'] ?? null,
                    'payment_method' => $result['payment_method'] ?? null,
                    'payment_date' => $result['payment_date'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Transaction non trouvée'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Générer un ID de transaction unique
     */
    public function generateTransactionId($prefix = 'SUB')
    {
        return $prefix . '_' . time() . '_' . rand(1000, 9999);
    }

    /**
     * Convertir le montant en centimes pour CinetPay
     */
    public function convertAmountToCents($amount)
    {
        return intval($amount);
    }

    /**
     * Valider la signature de notification (webhook)
     */
    public function validateNotificationSignature($data, $signature)
    {
        // Pour la version legacy, on peut utiliser une validation simple
        // ou implémenter selon la documentation spécifique
        return true; // À adapter selon la documentation CinetPay legacy
    }

    /**
     * Valider si le paiement est valide (méthode legacy)
     */
    public function isValidPayment()
    {
        try {
            $CinetPay = new CinetPay($this->siteId, $this->apiKey);
            return $CinetPay->isValidPayment();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Générer un ID de transaction avec la méthode CinetPay
     */
    public function generateCinetPayTransactionId()
    {
        return CinetPay::generateTransId();
    }
}